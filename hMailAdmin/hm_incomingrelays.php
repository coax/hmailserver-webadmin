<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obIncomingRelays = $obSettings->IncomingRelays();
$Count = $obIncomingRelays->Count();

$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");
?>
    <div class="box large">
      <h2><?php EchoTranslation("Incoming relays") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Name")?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$obIncomingRelay = $obIncomingRelays->Item($i);
	$relayname = $obIncomingRelay->Name;
	$relayid = $obIncomingRelay->ID;
	$relayname = PreprocessOutput($relayname);

   	echo '            <tr>
              <td><a href="?page=incomingrelay&action=edit&relayid=' . $relayid . '">' . $relayname . '</a></td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $relayname . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_incomingrelay_save&csrftoken=' . $csrftoken . '&action=delete&relayid=' . $relayid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=incomingrelay&action=add" class="button"><?php EchoTranslation("Add new relay") ?></a></div>
      </div>
    </div>
