<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // Users are not allowed to show this page.

$obSettings  = $obBaseApp->Settings();
$dnsBlacklists = $obSettings->AntiSpam->DNSBlackLists;
$Count = $dnsBlacklists->Count();
$str_delete = $obLanguage->String("Remove");
?>
    <div class="box large">
      <h2><?php EchoTranslation("DNS blacklists") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:75%;">Name</th>
              <th style="width:10%;">Score</th>
              <th style="width:10%;">Enabled</th>
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
      <div class="buttons center"><a href="?page=dnsblacklist&action=add" class="button">Add new blacklist</a></div>
    </div>