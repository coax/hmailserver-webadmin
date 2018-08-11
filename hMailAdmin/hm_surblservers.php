<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$dnsBlacklists = $obSettings->AntiSpam->SURBLServers;
$Count = $dnsBlacklists->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("SURBL servers") ?> <span>(<?php echo $Count ?>)</span></h2>
      <table class="tablesort">
        <thead>
          <tr>
            <th><?php EchoTranslation("Name") ?></th>
            <th style="width:15%;"><?php EchoTranslation("Score") ?></th>
            <th style="width:15%;"><?php EchoTranslation("Enabled") ?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");

if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$dnsBlackList = $dnsBlacklists->Item($i);
		$id = $dnsBlackList->ID;
		$name = PreprocessOutput($dnsBlackList->DNSHost);
		$enabled = $dnsBlackList->Active ? $str_yes : $str_no;
		$inactive = $dnsBlackList->Active ? $inactive = '' : $inactive = ' class="red"';
		$name = PreprocessOutput($name);
		$Score = $dnsBlackList->Score; //added
		if (strlen($name)==0) $name = "(unnamed)";

		echo '          <tr>
            <td><a href="?page=surblserver&action=edit&id=' . $id . '"' . $inactive . '>' . $name . '</a></td>
            <td>' . $Score . '</td>
            <td>' . $enabled . '</td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_surblserver_save&csrftoken=' . $csrftoken . '&action=delete&id=' . $id . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="4">' . Translate("You haven't added any SURBL servers.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=surblserver&action=add" class="button"><?php EchoTranslation("Add new SURBL server") ?></a></div>
    </div>