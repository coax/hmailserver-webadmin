<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

if (empty($hmail_config['dmarc_enable'])) exit('<div class="box large"><h2>' . Translate("DMARC reports") . '</h2><p class="warning" style="margin-top:18px;">' . Translate("DMARC reports are not enabled in config.php") . '</p></div>') . PHP_EOL;

$folder = './logs/dmarc/';

/* Download new reports from IMAP.
 * Unpack and/or save in DMARC log folder. */

function get_reports($folder) {
	global $hmail_config;

	if (!extension_loaded('imap')) {
		return Translate("IMAP extension not enabled in php.ini");
		exit();
	}

	if($hmail_config['dmarc_encryption'])
		$hmail_config['dmarc_encryption'] = '/' . $hmail_config['dmarc_encryption'];
	$hostname = '{' . $hmail_config['dmarc_hostip'] . ':' . $hmail_config['dmarc_port'] . $hmail_config['dmarc_encryption'] . '/novalidate-cert}INBOX';

	/* try to connect */
	set_error_handler(function() { /* Ignore errors */ });
	try {
		if (!$inbox = @imap_open($hostname, $hmail_config['dmarc_username'], $hmail_config['dmarc_password']))
			return imap_last_error();
	} catch (Exception $e) {
		// Handle the error if necessary
	}
	restore_error_handler();

	$count = 0;
	//$emails = imap_search($inbox, 'UNSEEN');
	$emails = imap_search($inbox, 'ALL');

	/* if any emails found, iterate through each email */
	if ($emails) {
		/* for every email... */
		foreach ($emails as $email_number) {
			/* get mail structure */
			$structure = imap_fetchstructure($inbox, $email_number);

			$attachments = array();

			/* if any attachments found... */
			if (isset($structure->parts) && count($structure->parts)) {
				for ($i = 0; $i < count($structure->parts); $i++) {
					$count += save_attachment($inbox, $email_number, $structure->parts[$i], $i, $folder);
				}
			} else {
				$count += save_attachment($inbox, $email_number, $structure, 0, $folder);
			}

			/* mark for delete */
			imap_delete($inbox, $email_number);
		}
	}

	/* close the connection and delete marked messages */
	imap_close($inbox, CL_EXPUNGE);

	return $count;
}

function save_attachment($inbox, $email_number, $part, $part_number, $folder) {
	$is_attachment = false;
	$filename = '';
	$name = '';

	if ($part->ifdparameters) {
		foreach ($part->dparameters as $object) {
			if (strtolower($object->attribute) == 'filename') {
				$is_attachment = true;
				$filename = strtolower($object->value);
			}
		}
	}

	if ($part->ifparameters) {
		foreach ($part->parameters as $object) {
			if (strtolower($object->attribute) == 'name') {
				$is_attachment = true;
				$name = strtolower($object->value);
			}
		}
	}

	if ($is_attachment) {
		if (empty($filename))
			$filename = $name;
		if ((substr($filename, -4) === '.zip') || (substr($filename, -4) === '.xml') || (substr($filename, -3) === '.gz')) {
			$data = imap_fetchbody($inbox, $email_number, $part_number+1);

			/* 3 = BASE64 encoding */
			if ($part->encoding == 3)
				$data = base64_decode($data);
			/* 4 = QUOTED-PRINTABLE encoding */
			elseif ($part->encoding == 4)
				$data = quoted_printable_decode($data);

			if (!is_dir($folder))
				mkdir($folder);

			switch (true) {
				case substr( $filename, -4 ) === '.zip':
					$tempfile = $folder . 'temp' . $part_number;
					file_put_contents($tempfile, $data);
					$zip = new ZipArchive;
					if($zip->open($tempfile)){
						$zip->extractTo($folder);
						$zip->close();
					}
					unlink($tempfile);
					break;
				case substr( $filename, -3 ) === '.gz':
					if ($data = gzdecode($data)) {
						$filename = str_replace(array('.xml.gz', '.gz'), '.xml', $filename);
						file_put_contents($folder . $filename, $data);
					}
					break;
				default:
					file_put_contents($folder . $filename, $data);
			}
			return 1;
		}
	}
	return 0;
}

/* Search directory for reports. */
$new_report_count = get_reports($folder);
$files = glob($folder . '*.xml');
$reports_count = count($files);
if (!empty($files)) {
	$reports = parse($files);
} else {
	$reports = array();
}

/* Open xml and phrase them. */
function parse($files) {
	$out = array();

	if (!is_array($files))
		$files = array($files);

	foreach($files as $file) {
		$data = file_get_contents($file);

		$xml = new SimpleXMLElement($data);
		$records = array();

		// parse records
		foreach ($xml->record as $record) {
			$row = $record->row;
			$results = $record->auth_results;

			foreach (array('dkim', 'spf') as $type) {
				if (!property_exists($row->policy_evaluated, $type))
						$row->policy_evaluated->{$type} = 'none';
			}

			// Google incorrectly uses "hardfail" in SPF results
			if ($results->spf->result == 'hardfail')
				$results->spf->result = 'fail';

			$rptresult = array();
			foreach (array('dkim', 'spf') as $type) {
				//foreach($results->{$type} as $result)
				if (property_exists($results, $type)) {
					$rptresult[$type] = array('type' => $type, 'domain' => (string) $results->{$type}->domain, 'result' => (string) $results->{$type}->result);
				} else {
					global $obLanguage;
					$rptresult[$type] = array('type' => $type, 'domain' => Translate("No result"), 'result' => '');
				}
			}
			$ip = (string) $row->source_ip;
			if (!isset($records[$ip]))
				$records[$ip] = array('ip' => $ip, 'count' => 0, 'rows' => array());

			$records[$ip]['count'] +=  (int) $row->count;

			$records[$ip]['rows'][] = array(
				'ip' => (string) $row->source_ip,
				'count' => (int) $row->count,
				'disposition' => (string) $row->policy_evaluated->disposition,
				'reason' => (string) $row->policy_evaluated->reason->type,
				'dkim_result' => (string) $row->policy_evaluated->dkim,
				'spf_result' => (string) $row->policy_evaluated->spf,
				'identifiers' => (string) $record->identifiers->header_from,
				'result' => $rptresult
				);
		}

		$out[] = array(
			'filename' => $file,
			'org' => (string) $xml->report_metadata->org_name,
			'email' => (string) $xml->report_metadata->email,
			'extra_contact_info' => (string) $xml->report_metadata->extra_contact_info,
			'date_begin' => (int) $xml->report_metadata->date_range->begin,
			'date_end' => (int) $xml->report_metadata->date_range->end,
			'errors' => (string) $xml->report_metadata->errors,
			'id' => (string) $xml->report_metadata->report_id,
			//Policy
			'domain' => (string) $xml->policy_published->domain,
			'adkim' => (string) $xml->policy_published->adkim,
			'aspf' => (string) $xml->policy_published->aspf,
			'p' => (string) $xml->policy_published->p,
			'sp' => (string) $xml->policy_published->sp,
			'pct' => (int) $xml->policy_published->pct,

			'records' => $records,
			);
	}

	// Sort by date, newest first.
	usort($out, function($a, $b) {
		return $b['date_begin'] - $a['date_begin'];
	});

	return $out;
}
?>
<style>
.dmarc-report {margin:8px; padding:16px 16px 0; background:#fff; border-radius:2px; border:1px solid #ccc;}
.dmarc-report > svg {position:absolute; left:-14px; top:-6px; width:14px; height:14px; color:#646a6f; vertical-align:-4px;}
.dmarc-report a > svg {vertical-align:-2px; margin-right:4px; color:#1784c7;}
.dmarc-report h4 {margin-bottom:8px;}
.dmarc-report p {margin:6px 0;}
.dmarc-report th {width:15%;}
.dmarc-report td.aligned {background:#c8fd87;}
.dmarc-report td.unaligned {background:#f69195;}
table .hidden:hover {background:none;}
.dmarc-report table table th, .dmarc-report table table td {text-indent:10px;}
}
</style>
    <div class="box large">
      <h2><?php EchoTranslation("DMARC reports") ?> <span>(<?php echo $reports_count ?>)</span></h2>
<?php
	$message = '';
	if (!empty($new_report_count)) {
		if (is_int($new_report_count)) {
			$message = str_replace('#', $new_report_count, Translate('# new reports added.'));
		} else {
			$message = $new_report_count;
		}
	} else {
		if (empty($reports))
			$message = 'No reports.';
	}

	if ($message) {
?>
      <p class="warning bottom" style="margin-top:18px;"><?php echo Translate($message); ?></p>
<?php
	}

	$id = 0;

	if ($reports) {
?>
      <table class="tablesort">
        <thead>
          <tr>
            <th data-sort="string">Name</th>
            <th style="width:25%;" data-sort="int">Report ID</th>
            <th style="width:25%;" data-sort="int">Date</th>
            <th style="width:5%;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
		foreach( $reports as $report ) {
			$report_id = $report['id'];
			$report_name = $report['domain'] . ' &ndash; ' . $report['org'];
			$report_unixtime = $report['date_begin'];
			$report_date = date('d.m.Y H:i:s', $report_unixtime);
?>
          <tr>
            <td>
              <a href="#"><span>[+]</span> <?php echo $report_name; ?></a>
            </td>
            <td><?php echo $report_id; ?></td>
            <td data-sort-value="<?php echo $report_unixtime; ?>"><?php echo $report_date; ?></td>
            <td><a href="#" onclick="return Confirm('<?php EchoTranslation("Confirm delete"); ?> <b><?php echo $report_name; ?></b>:','<?php EchoTranslation("Yes") ?>','<?php EchoTranslation("No") ?>','?page=background_dmarcreports&dfn=<?php echo $report['filename'] ?>&csrftoken=<?php echo $csrftoken ?>');" class="delete" title="Remove">Remove</a></td>
          </tr>
          <tr class="hidden"><td colspan="4"></td></tr>
          <tr class="hidden">
            <td colspan="4">

<div class="dmarc-report">
  <h4><?php EchoTranslation("DMARC Report Details") ?></h4>
  <table class="no-sort">
    <tr>
      <th><?php EchoTranslation("Provider") ?></th>
      <td><?php echo $report['org'] ?></td>
      <th><?php EchoTranslation("Report ID") ?></th>
      <td><?php echo $report['id'] ?></td>
    </tr>
    <tr>
      <th><?php EchoTranslation("Coverage") ?></th>
      <td><?php echo date('Y-m-d H:i:s',$report['date_begin']) ?> - <?php echo date('Y-m-d H:i:s', $report['date_end']) ?></td>
      <th><?php EchoTranslation("Extra contact") ?></th>
      <td><?php echo $report['extra_contact_info'] ?></td>
    </tr>
    <tr>
      <th><?php EchoTranslation("Email contact") ?></th>
      <td><?php echo $report['email'] ?></td>
      <th><?php EchoTranslation("Errors") ?></th>
      <td><?php echo $report['errors'] ?>&nbsp;</td>
    </tr>
  </table>
  <h4><?php EchoTranslation("Policy Details") ?></h4>
  <table class="no-sort">
    <tr>
      <th><?php EchoTranslation("Policy") ?></span></th>
      <td><?php EchoTranslation(ucfirst($report['p'])) ?></td>
      <th><?php EchoTranslation("DKIM alignment") ?></th>
      <td><?php EchoTranslation(($report['adkim']=='s'?'Strict':'Relaxed')) ?></td>
      <th><?php EchoTranslation("Percentage") ?></th>
      <td><?php echo $report['pct'] ?></td>
    </tr>
    <tr>
      <th><?php EchoTranslation("Sub-domain Policy") ?></th>
      <td><?php EchoTranslation(ucfirst($report['sp'])) ?></td>
      <th><?php EchoTranslation("SPF alignment") ?></th>
      <td colspan="3"><?php EchoTranslation(($report['aspf']=='s'?'Strict':'Relaxed')) ?></td>
    </tr>
  </table>
  <h4><?php EchoTranslation("Identified Sources") ?></h4>
  <table class="no-sort">
    <thead>
      <tr>
        <th style="width:45%"><?php EchoTranslation("Server") ?></th>
        <th style="width:10%"><?php EchoTranslation("Count") ?></th>
        <th style="width:15%">DMARC</th>
        <th style="width:15%">DKIM</th>
        <th style="width:15%">SPF</th>
      </tr>
    </thead>
    <tbody>
<?php
		foreach( $report['records'] as $record ) {
			//print_r($record);
			$rows = '      <tr id="dm' . $id . '-d" class="hidden">
        <td colspan="5" style="padding-left:16px;">
          <div>
            <table>
              <thead>
                <tr>
                  <th style="width:13%">IP</th>
                  <th style="width:7%">' . Translate("Count") . '</th>
                  <th style="width:10%">' . Translate("Disposition") . '</th>
                  <th style="width:5%">DKIM</th>
                  <th style="width:5%">SPF</th>
                  <th>' . Translate("From") . '</th>
                  <th style="width:20%">' . Translate("DKIM domain (result)") . '</th>
                  <th style="width:20%">' . Translate("SPF domain (result)") . '</th>
                </tr>
              </thead>
              <tbody>';
			$dmarc = 0;
			$dkim = 0;
			$spf = 0;
			foreach( $record['rows'] as $row ) {
				if($row['dkim_result']=='pass' && $row['result']['dkim']['result']=='pass')$dkim += $row['count'];
				if($row['spf_result']=='pass' && $row['result']['spf']['result']=='pass')$spf += $row['count'];
				if($row['disposition']=='none')$dmarc += $row['count'];
				$rows .= '                <tr>
                  <td>' . $row['ip'] . '</td>
                  <td>' . $row['count'] . '</td>
                  <td>' . Translate(ucfirst($row['disposition'])) . '</td>
                  <td>' . Translate(ucfirst($row['dkim_result'])) . '</td>
                  <td>' . Translate(ucfirst($row['spf_result'])) . '</td>
                  <td>' . $row['identifiers'] . '</td>
                  <td class="' . ($row['result']['dkim']['result']==$row['dkim_result']?'aligned':'unaligned') . '">' . $row['result']['dkim']['domain'] . ' (' . Translate(ucfirst($row['result']['dkim']['result'])) . ')</td>
                  <td class="' . ($row['result']['spf']['result']==$row['spf_result']?'aligned':'unaligned') . '">' . $row['result']['spf']['domain'] . ' (' . Translate(ucfirst($row['result']['spf']['result'])) . ')</td>
                </tr>';
			}
			$rows .= '              </tbody>
            </table>
          </div>
        </td>
      </tr>';

			echo '      <tr>
        <td><a href="#" class="toggle"><span>[+]</span> ' . $record['ip'] . '</a></td>
        <td>' . $record['count'] . '</td>
        <td>' . round(($dmarc / $record['count'] * 100),2) . '%</td>
        <td>' . round(($dkim / $record['count'] * 100),2) . '%</td>
        <td>' . round(($spf / $record['count'] * 100),2) . '%</td>
      </tr>
      <tr class="hidden"><td colspan="5"></td></tr>' . $rows;
			$id++;
		}
?>
    <tbody>
  </table>
</div>
            </td>
          </tr>
<?php
		}
?>
        </tbody>
      </table>
<?php
	}
?>
    </div>
<script>
$(document).ready(function(){
	// Show-hide for DMARC reports
	if ($('td a').length) {
		$('td a').on('click', function() {
			var t = $(this),
				button = t.find('span');

			if (button.text()==='[+]') {
				button.text('[-]');
			} else {
				button.text('[+]');
			}

			t.parent().parent().next().next().toggle();
			return false;
		});
	}
});
</script>