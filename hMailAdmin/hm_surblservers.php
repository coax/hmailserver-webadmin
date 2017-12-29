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
              <th><?php EchoTranslation("Name") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Score") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Enabled") ?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");

for ($i = 0; $i < $Count; $i++) {
	$dnsBlackList = $dnsBlacklists->Item($i);
	$id = $dnsBlackList->ID;
	$name = PreprocessOutput($dnsBlackList->DNSHost);
	$enabled = $dnsBlackList->Active ? $str_yes : $str_no;
	$name = PreprocessOutput($name);
	$Score = $dnsBlackList->Score; //added
	if (strlen($name)==0) $name = "(unnamed)";

	echo '            <tr>
              <td><a href="?page=surblserver&action=edit&id=' . $id . '">' . $name . '</a></td>
              <td>' . $Score . '</td>
              <td>' . $enabled . '</td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_surblserver_save&csrftoken=' . $csrftoken . '&action=delete&id=' . $id . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
      </div>
      <div class="buttons center"><a href="?page=surblserver&action=add" class="button"><?php EchoTranslation("Add new SURBL") ?></a></div>
    </div>