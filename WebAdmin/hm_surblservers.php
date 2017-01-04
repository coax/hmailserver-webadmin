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
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:65%;"><?php EchoTranslation("Name") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Score") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Enabled") ?></th>
              <th style="width:5%;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$dnsBlackList = $dnsBlacklists->Item($i);
	$id = $dnsBlackList->ID;
	$name = $dnsBlackList->DNSHost;
	$enabled = $dnsBlackList->Active ? $obLanguage->String("Yes") : $obLanguage->String("No");
	$name = PreprocessOutput($name);
	$Score = $dnsBlackList->Score; //added
	if (strlen($name)==0) $name = "(unnamed)";

	echo '            <tr>
              <td><a href="?page=surblserver&action=edit&id=' . $id . '">' . $name . '</a></td>
              <td>' . $Score . '</td>
              <td>' . $enabled . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $name . '</b>:\',\'Yes\',\'?page=background_surblserver_save&action=delete&id=' . $id . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
      </div>
      <div class="buttons center"><a href="?page=surblserver&action=add" class="button">Add new SURBL</a></div>
    </div>