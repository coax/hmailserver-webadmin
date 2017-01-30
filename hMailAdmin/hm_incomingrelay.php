<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // The user is not server administrator

$relayid = hmailGetVar("relayid",0);
$action = hmailGetVar("action","");
$relayname ="";
$relaylowerip = "0.0.0.0";
$relayupperip = "255.255.255.255";

if ($action == "edit") {
	$obIncomingRelay = $obBaseApp->Settings->IncomingRelays->ItemByDBID($relayid);
	$relayname = $obIncomingRelay->Name;
	$relaylowerip = $obIncomingRelay->LowerIP;
	$relayupperip = $obIncomingRelay->UpperIP;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("Incoming relay") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_incomingrelay_save");
PrintHidden("action", $action);
PrintHidden("relayid", $relayid);

PrintPropertyEditRow("relayname", "Name", $relayname, 30);
PrintPropertyEditRow("relaylowerip", "Lower IP", $relaylowerip, 25);
PrintPropertyEditRow("relayupperip", "Upper IP", $relayupperip, 25);

PrintSaveButton();
?>
      </form>
    </div>