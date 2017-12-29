<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obSecurityRanges = $obSettings->SecurityRanges();
$Count = $obSecurityRanges->Count();

$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");
?>
    <div class="box large">
      <h2><?php EchoTranslation("IP Ranges") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Name")?></th>
              <th style="width:20%;"><?php EchoTranslation("IP address") ?></th>
              <th style="width:10%;"><?php EchoTranslation("Priority") ?></th>
              <th style="width:20%;"><?php EchoTranslation("Expires") ?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
function humanTiming($time) {
	global $obLanguage;
	$time = $time - time();
	if($time < 1) return $obLanguage->String("Expired");
	$tokens = array (
		31536000 => array('year','years'),
		2592000 => array('month','months'),
		604800 => array('week','weeks'),
		86400 => array('day','days'),
		3600 => array('hour','hours'),
		60 => array('minute','minutes'),
		1 => array('second','seconds')
	);
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits . ' ' . $obLanguage->String($numberOfUnits>1 ? $text[1] : $text[0]);
	}
}

for ($i = 0; $i < $Count; $i++) {
	$obSecurityRange = $obSecurityRanges->Item($i);
	$securityrangename = $obSecurityRange->Name;
	$securityrangeid = $obSecurityRange->ID;

	$securityrangename = PreprocessOutput($securityrangename);
	$securityrangepriority = $obSecurityRange->Priority;
	$ExpiresTime = $obSecurityRange->Expires ? humanTiming(strtotime(makeIsoDate($obSecurityRange->ExpiresTime))) : $obLanguage->String("Never");
	$LowerIp = $obSecurityRange->LowerIP;


	echo '            <tr>
              <td><a href="?page=securityrange&action=edit&securityrangeid=' . $securityrangeid . '"' . (strpos($securityrangename,'Auto-ban:')!==false?' class="red"':'') . '>' . $securityrangename . '</a></td>
              <td>' . $LowerIp . '</td>
              <td>' . $securityrangepriority . '</td>
              <td>' . $ExpiresTime . '</td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $securityrangename . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_securityrange_save&csrftoken=' . $csrftoken . '&action=delete&securityrangeid=' . $securityrangeid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=securityrange&action=add" class="button"><?php EchoTranslation("Add new range") ?></a></div>
      </div>
    </div>