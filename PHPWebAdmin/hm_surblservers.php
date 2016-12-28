<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // Users are not allowed to show this page.

?>
    <div class="box large">
      <h2><?php EchoTranslation("SURBL servers") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:75%;">Name</th>
            <th style="width:10%;">Score</th>
            <th style="width:10%;">Enabled</th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$obSettings = $obBaseApp->Settings();
$dnsBlacklists = $obSettings->AntiSpam->SURBLServers;
$Count = $dnsBlacklists->Count();
$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++) {
	$dnsBlackList = $dnsBlacklists->Item($i);
	$id = $dnsBlackList->ID;
	$name = $dnsBlackList->DNSHost;
	$enabled = $dnsBlackList->Active ? $obLanguage->String("Yes") : $obLanguage->String("No");
	$name = PreprocessOutput($name);
	$Score = $dnsBlackList->Score; //added
	if (strlen($name)==0) $name = "(unnamed)";

	echo '          <tr>
            <td><a href="?page=surblserver&action=edit&id=' . $id . '">' . $name . '</a></td>
            <td>' . $Score . '</td>
            <td>' . $enabled . '</td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $name . '</b>:\',\'Yes\',\'?page=background_surblserver_save&action=delete&id=' . $id . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
      </div>
      <div class="buttons center"><a href="?page=surblserver&action=add" class="button">Add new SURBL</a></div>
    </div>