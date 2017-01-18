<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$dnsBlacklists = $obSettings->AntiSpam->DNSBlackLists;
$Count = $dnsBlacklists->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("DNS blacklists") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Name")?></th>
              <th style="width:15%;"><?php EchoTranslation("Score")?></th>
              <th style="width:15%;"><?php EchoTranslation("Enabled")?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$dnsBlackList = $dnsBlacklists->Item($i);
	$id = $dnsBlackList->ID;
	$name = $dnsBlackList->DNSHost;
	$enabled = $dnsBlackList->Active ? $obLanguage->String("Yes") : $obLanguage->String("No");
	$Score = $dnsBlackList->Score; //added
	$name = PreprocessOutput($name);
	if (strlen($name)==0) $name = "(unnamed)";

	echo '            <tr>
              <td><a href="?page=dnsblacklist&action=edit&id=' . $id . '">' . $name . '</a></td>
              <td>' . $Score . '</td>
              <td>' . $enabled . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $name . '</b>:\',\'Yes\',\'?page=background_dnsblacklist_save&action=delete&id=' . $id . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
      </div>
      <div class="buttons center"><a href="?page=dnsblacklist&action=add" class="button"><?php EchoTranslation("Add new blacklist") ?></a></div>
    </div>