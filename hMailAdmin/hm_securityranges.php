<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obSecurityRanges = $obSettings->SecurityRanges();
$Count = $obSecurityRanges->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("IP Ranges") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Name")?></th>
              <th style="width:10%;"><?php EchoTranslation("Priority") ?></th>
              <th style="width:20%;"><?php EchoTranslation("Expires") ?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
function humanTiming($time) {
	$time = $time - time();
	if($time < 1) return 'Expired';
	$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits . ' ' . $text . (($numberOfUnits>1) ? 's' : '');
	}
}

for ($i = 0; $i < $Count; $i++) {
	$obSecurityRange = $obSecurityRanges->Item($i);
	$securityrangename = $obSecurityRange->Name;
	$securityrangeid = $obSecurityRange->ID;

	$securityrangename = PreprocessOutput($securityrangename);
	$securityrangepriority = $obSecurityRange->Priority;
	$ExpiresTime = $obSecurityRange->Expires ? humanTiming(strtotime(makeIsoDate($obSecurityRange->ExpiresTime))) : 'Never';
	$LowerIp = $obSecurityRange->LowerIP;


	echo '            <tr>
              <td><a href="?page=securityrange&action=edit&securityrangeid=' . $securityrangeid . '"' . (strpos($securityrangename,'Auto-ban:')!==false?' class="red"':'') . '>' . $securityrangename . '</a></td>
              <td>' . $securityrangepriority . '</td>
              <td>' . $ExpiresTime . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $securityrangename . '</b>:\',\'Yes\',\'?page=background_securityrange_save&csrftoken=' . $csrftoken . '&action=delete&securityrangeid=' . $securityrangeid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=securityrange&action=add" class="button"><?php EchoTranslation("Add new range") ?></a></div>
      </div>
    </div>