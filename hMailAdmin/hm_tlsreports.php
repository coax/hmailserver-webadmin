<?php
if (!defined('IN_WEBADMIN'))
    exit();

if (hmailGetAdminLevel() != 2)
    hmailHackingAttemp();

if (empty($hmail_config['tlsreport_enable'])) exit('<div class="box large"><h2>' . Translate("TLS reports") . '</h2><p>&nbsp;</p><p class="warning">' . Translate("TLS reports are not enabled in config.php") . '</p></div>') . PHP_EOL;


function get_reports() {
    global $hmail_config;
    if (!extension_loaded('imap'))
        return Translate("IMAP extension not enabled in php.ini");

    if($hmail_config['tlsreport_encryption'])
        $hmail_config['tlsreport_encryption'] = '/' . $hmail_config['tlsreport_encryption'];
    $hostname = '{' . $hmail_config['tlsreport_hostip'] . ':' . $hmail_config['tlsreport_port'] . $hmail_config['tlsreport_encryption'] . '/novalidate-cert}INBOX';

    /* try to connect */
    if (!$inbox = @imap_open($hostname, $hmail_config['tlsreport_username'], $hmail_config['tlsreport_password']))
        return Translate("Cannot connect to server") . ': ' . imap_last_error();

    $folder = './logs/tls';
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
                    $count += save_json_attachment($inbox, $email_number, $structure->parts[$i], $i, $folder);
                }
            } else {
                $count += save_json_attachment($inbox, $email_number, $structure, 0, $folder);
            }

            /* mark for delete */
            imap_delete($inbox, $email_number);
        }
    }

    /* close the connection and delete marked messages */
    imap_close($inbox, CL_EXPUNGE);

    return $count;
}

function save_json_attachment($inbox, $email_number, $part, $index, $folder) {
    $is_attachment = false;
    $filename = '';

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

        $data = imap_fetchbody($inbox, $email_number, $index + 1);

        /* 3 = BASE64 encoding */
        if ($part->encoding == 3)
            $data = base64_decode($data);
        /* 4 = QUOTED-PRINTABLE encoding */
        elseif ($part->encoding == 4)
            $data = quoted_printable_decode($data);

        if (!is_dir($folder))
            mkdir($folder);

        $filename = str_replace('.gz', '', $filename); // Remove .gz extension if present
        if ($data = gzdecode($data)) {
            file_put_contents($folder . '/' . $filename, $data);
        }
        else {
            file_put_contents($folder . '/' . $filename, $data);
        }

        return 1;
    }

    return 0;
}


/* Search directory for reports. */
$new_report_count = get_reports();
$files = glob('./tlsreports/*.json');
$reports_count = count($files);
if (!empty($files)) $reports = parse($files);
else $reports = array();

function parse($files){
    $out = array();
    
    if (!is_array($files))
        $files = array($files);
    
    foreach($files as $file){
        $json = file_get_contents($file);
        
        $data = json_decode($json, true);
        
        $out[] = array(
            'filename' => $file,
            'org' => $data['organization-name'],
            'domain' => explode('!', $file)[1], // this shouldnt be an issue if the report name is constructed as recommended in the RFC
            'date-range' => array(
                'date-begin' => $data['date-range']['start-datetime'],
                'date-end' => $data['date-range']['end-datetime'],
            ),
            'contact-info' => $data['contact-info'],
            'report-id' => $data['report-id'],
            'policies' => $data['policies']
        );
    }
    	// Sort by date, newest first.
	usort($out, function($a, $b) {
		return strtotime($b['date-range']['date-begin']) - strtotime($a['date-range']['date-begin']);
	});
    
    return $out;
}
//print_r( $reports );
?><script type="text/javascript">
$(document).ready(function(){
    if($('a.toggle').length){
        $('a.toggle').on('click', function() {
            var id = $(this).attr('id');
            console.log('Toggle Clicked:', id);
            var sign = $(this).text();
            if(sign == '+'){
                $('#' + id + '-t').show().find('table.hidden').slideDown(150);
                $(this).text('-');
            } else {
                $('#' + id + '-t').hide().find('table.hidden').slideUp(150);
                $(this).text('+');
            }
            return false;
        })
    }
});

</script>
<style>
td.aligned {background-color:#9f9; padding-left:5px;}
td.unaligned {background-color:#f99; padding-left:5px;}
table.tls tbody tr:nth-child(3n) {background: #f9fafa}
table.tls tbody tr:nth-child(2n) {background: #eaeaea}
table.tls tbody tr:hover {background: #f2f2f2}
.tls table {margin:15px 0}
div.tls {overflow-x:auto}
</style>
    <div class="box large">
      <h2><?php EchoTranslation("TLS reports") ?> <span>(<?php echo $reports_count ?>)</span></h2>
<?php
    if(!empty($new_report_count))
    {
        if(is_int($new_report_count))
            echo '<div>' . str_replace('#',$new_report_count,Translate("# new reports added.")) . '</div>';
        else
            echo '<p class="warning">' . $new_report_count . '</p>';
    }
    $id = 0;
    foreach( $reports as $report ) {
        echo '<h3><a href="#">'.$report['domain'].' &#8211; '.$report['org'].' &#8211; '.date('Y-m-d', strtotime($report['date-range']['date-begin'])).'</a></h3>';
?>
      <div class="hidden tls">
        <div class="buttons"><a class="button" href="#" onclick="return Confirm('<?php EchoTranslation("Confirm delete") ?> <b><?php EchoTranslation("TLS report") ?></b>','<?php EchoTranslation("Yes") ?>','<?php EchoTranslation("No") ?>','?page=background_tlsreports&tls=<?php echo $report['filename'] ?>&csrftoken=<?php echo $csrftoken ?>');"><?php EchoTranslation("Delete TLS report") ?></a></div>
        <h4 style="margin-top:18px;"><?php EchoTranslation("TLS Report Details") ?></h4>
        <table>
          <tr>
            <th><?php EchoTranslation("Provider") ?>:</th>
            <td><?= $report['org'] ?></td>
            <th><?php EchoTranslation("Report ID") ?>:</th>
            <td><?= $report['report-id'] ?></td>
          </tr>
          <tr>
            <th><?php EchoTranslation("Coverage") ?>:</th>
            <td><?= date('Y-m-d H:i:s', strtotime($report['date-range']['date-begin'])) ?> - <?= date('Y-m-d H:i:s', strtotime($report['date-range']['date-end'])) ?></td>
            <th><?php EchoTranslation("Extra contact") ?>:</th>
            <td><?= $report['contact-info'] ?></td>
          </tr>
        </table>
        <?php foreach( $report['policies'] as $policy){
                if(isset($policy['summary']['total-successful-session-count'], $policy['summary']['total-failure-session-count'])){
                    $successfulSessions = $policy['summary']['total-successful-session-count'];
                    $failedSessions = $policy['summary']['total-failure-session-count'];
                    $percentageSuccessful = round(($successfulSessions / ($successfulSessions + $failedSessions)) * 100, 1);
                    $percentageFailed = round(($failedSessions / ($failedSessions + $successfulSessions)) * 100, 1);
                } else {
                    $failedSessions = 0;
                    $successfulSessions = 0;
                }
            ?>
        <h4><?php EchoTranslation('Summary') ?></h4>
        <table class="tls">
          <tr>
            <th><?php EchoTranslation('Total Successful Sessions') ?></th>
            <td style="width:10%" class="<?= $successfulSessions > 0 ? 'aligned' : 'unaligned'; ?>"><?= $successfulSessions . ' - ' . $percentageSuccessful . '%' ?></td>
            <th><?php EchoTranslation('Total Failed Sessions') ?></th>
            <td style="width:10%" class="<?= $failedSessions > 0 ? 'unaligned' : 'aligned'; ?>"><?= $failedSessions . ' - ' . $percentageFailed . '%' ?></td>
          </tr>
        </table>
        <h4><?php EchoTranslation("Policy Details") ?> <a href="#" class="toggle" id="dm<?= $id ?>">+</a></h4><br>
        <table class="hidden" id="dm<?= $id ?>-t">
          <tr>
            <th><?php EchoTranslation('Policy Type') ?></th>
            <td><?= $policy['policy']['policy-type'] ?></td>
            <th><?php EchoTranslation('Policy') ?></th>
            <!-- policy-string is an array of strings, interesting name choice -->
            <td><?= implode(', ', $policy['policy']['policy-string']) ?></td>
          </tr>
          <tr>
            <th><?php EchoTranslation('Policy Domain') ?></th>
            <td><?php if(isset($policy['policy']['policy-domain'])) print $policy['policy']['policy-domain'] ?></td>
            <th><?php EchoTranslation('MX Hosts') ?></th>
            <td><?php if(isset($policy['policy']['mx-host'])) print implode(', ', $policy['policy']['mx-host']) ?></td>
          </tr>
        </table>
    <?php if(isset($policy['failure-details'])) {
            $id++;
        ?>
        <h4><?php EchoTranslation('Failure Details') ?> <a href="#" class="toggle" id="dm<?= $id ?>">+</a></h4><br>
            <table class="hidden" id="dm<?= $id ?>-t">
        <?php foreach( $policy['failure-details'] as $failuredetail ) { ?>
              <tr>
                <th><?php EchoTranslation('Result') ?></th>
                <td><?= isset($failuredetail['result-type'])?$failuredetail['result-type']:'N/A'; ?></td>
                <th><?php EchoTranslation('Sending MTA IP') ?></th>
                <td><?= isset($failuredetail['sending-mta-ip'])?$failuredetail['sending-mta-ip']:'N/A'; ?></td>
                <th><?php EchoTranslation('Additional Information') ?></th>
                <td><?= isset($failuredetail['additional-information'])? $failuredetail['additional-information'] :'N/A'; ?></td>
                <th><?php EchoTranslation('Failure Reason Code') ?></th>
                <td><?= isset($failuredetail['failure-reason-code'])? $failuredetail['failure-reason-code'] :'N/A'; ?></td>
              </tr>
              <tr>
                <th><?php EchoTranslation('Receiving IP') ?></th>
                <td><?= isset($failuredetail['receiving-ip'])? $failuredetail['receiving-ip'] :'N/A'; ?></td>
                <th><?php EchoTranslation('Receiving MX Hostname') ?></th>
                <td><?= isset($failuredetail['receiving-mx-hostname'])? $failuredetail['receiving-mx-hostname'] :'N/A';?></td>
                <th><?php EchoTranslation('Receiving MX HELO') ?></th>
                <td><?= isset($failuredetail['receiving-mx-helo'])? $failuredetail['receiving-mx-hostname'] :'N/A';?></td>
                <th><?php EchoTranslation('Failed Session Count') ?></th>
                <td><?= isset($failuredetail['failed-session-count'])? $failuredetail['failed-session-count']:'N/A'; ?></td>
              </tr>
            <?php
                $id++;
                }
            ?>
        </table>
        <?php
            }
        $id++;
        }
    ?>
        </div>
<?php
    }
?>