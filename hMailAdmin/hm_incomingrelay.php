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
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_incomingrelay_save");
PrintHidden("action", $action);
PrintHidden("relayid", $relayid);

PrintPropertyEditRow("relayname", "Name", $relayname, 100, "");
PrintPropertyEditRow("relaylowerip", "Lower IP", $relaylowerip, 20, "");
PrintPropertyEditRow("relayupperip", "Upper IP", $relayupperip, 20, "");

PrintSaveButton(null, null, '?page=incomingrelays');
?>
      </form>
    </div>