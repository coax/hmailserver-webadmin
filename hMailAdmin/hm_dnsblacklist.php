<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // The user is not server administrator

$id = hmailGetVar("id",0);
$action = hmailGetVar("action","");

$Active = false;
$DNSHost = "";
$ExpectedResult = "";
$RejectMessage = "";
$Score = 5;

if ($action == "edit") {
	$dnsBlackList = $obBaseApp->Settings->AntiSpam->DNSBlackLists->ItemByDBID($id);

	$Active = $dnsBlackList->Active;
	$DNSHost = $dnsBlackList->DNSHost;
	$ExpectedResult = $dnsBlackList->ExpectedResult;
	$RejectMessage = $dnsBlackList->RejectMessage;
	$Score = $dnsBlackList->Score;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("DNS blacklist") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_dnsblacklist_save");
PrintHidden("action", $action);
PrintHidden("id", $id);

PrintCheckboxRow("Active", "Enabled", $Active);
PrintPropertyEditRow("DNSHost", "DNS Host", $DNSHost);
PrintPropertyEditRow("ExpectedResult", "Expected result", $ExpectedResult);
PrintPropertyEditRow("RejectMessage", "Rejection message", $RejectMessage);
PrintPropertyEditRow("Score", "Score", $Score, 5, "number", "small");

PrintSaveButton()
?>
      </form>
    </div>