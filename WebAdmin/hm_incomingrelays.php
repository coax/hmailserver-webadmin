<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // Users are not allowed to show this page.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Incoming relays") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:95%;">Name</th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$obSettings = $obBaseApp->Settings();
$obIncomingRelays = $obSettings->IncomingRelays();
$Count = $obIncomingRelays->Count();
$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++) {
	$obIncomingRelay = $obIncomingRelays->Item($i);
	$relayname = $obIncomingRelay->Name;
	$relayid = $obIncomingRelay->ID;
	$relayname = PreprocessOutput($relayname);

   	echo '          <tr>
            <td><a href=\"?page=incomingrelay&action=edit&relayid=' . $relayid . '">' . $relayname . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $relayname . '</b>:\',\'Yes\',\'?page=background_incomingrelay_save&action=delete&relayid=' . $relayid . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=incomingrelay&action=add" class="button">New relay</a></div>
      </div>
    </div>