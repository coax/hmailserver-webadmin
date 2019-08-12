<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$dnsBlacklists = $obSettings->AntiSpam->DNSBlackLists;
$Count = $dnsBlacklists->Count();
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");
?>
    <div class="box large">
      <h2><?php EchoTranslation("DNS blacklists") ?> <span>(<?php echo $Count ?>)</span></h2>
      <table class="tablesort">
        <thead>
          <tr>
            <th data-sort="string"><?php EchoTranslation("Name")?></th>
            <th style="width:15%;" data-sort="int"><?php EchoTranslation("Score")?></th>
            <th style="width:15%;" data-sort="string"><?php EchoTranslation("Enabled")?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$dnsBlackList = $dnsBlacklists->Item($i);
		$id = $dnsBlackList->ID;
		$name = $dnsBlackList->DNSHost;
		$enabled = $dnsBlackList->Active ? Translate("Yes") : Translate("No");
		$inactive = $dnsBlackList->Active ? $inactive = '' : $inactive = ' class="red"';
		$Score = $dnsBlackList->Score; //added
		$name = PreprocessOutput($name);
		if (strlen($name)==0) $name = "(unnamed)";

		echo '          <tr>
            <td><a href="?page=dnsblacklist&action=edit&id=' . $id . '"' . $inactive . '>' . $name . '</a></td>
            <td>' . $Score . '</td>
            <td>' . $enabled . '</td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_dnsblacklist_save&csrftoken=' . $csrftoken . '&action=delete&id=' . $id . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="4">' . Translate("You haven't added any blacklists.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=dnsblacklist&action=add" class="button"><?php EchoTranslation("Add new blacklist") ?></a></div>
    </div>