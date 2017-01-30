<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();
?>
    <div class="box large">
      <h2><?php EchoTranslation("Server messages") ?></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:100%;"><?php EchoTranslation("Message type")?></th>
            </tr>
          </thead>
          <tbody>
<?php
$obSettings = $obBaseApp->Settings();
$obServerMessages = $obSettings->ServerMessages();
$Count = $obServerMessages->Count();

for ($i = 0; $i < $Count; $i++) {
	$obServerMessage = $obServerMessages->Item($i);
	$messagename = $obServerMessage->Name;
	$messageid = $obServerMessage->ID;
	$messagename = PreprocessOutput($messagename);

	echo '            <tr>
              <td><a href="?page=servermessage&messageid=' . $messageid . '">' . $messagename . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
      </div>
    </div>