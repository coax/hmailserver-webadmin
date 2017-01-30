<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$action = hmailGetVar("action","");

if($action == "save") {
	$obSettings->AutoBanOnLogonFailure= hmailGetVar("AutoBanOnLogonFailure",0);
	$obSettings->MaxInvalidLogonAttempts= hmailGetVar("MaxInvalidLogonAttempts",0);
	$obSettings->MaxInvalidLogonAttemptsWithin= hmailGetVar("MaxInvalidLogonAttemptsWithin",0);
	$obSettings->AutoBanMinutes= hmailGetVar("AutoBanMinutes",0);
}

$AutoBanOnLogonFailure = $obSettings->AutoBanOnLogonFailure;
$MaxInvalidLogonAttempts = $obSettings->MaxInvalidLogonAttempts;
$MaxInvalidLogonAttemptsWithin = $obSettings->MaxInvalidLogonAttemptsWithin;
$AutoBanMinutes = $obSettings->AutoBanMinutes;
?>
    <div class="box">
      <h2><?php EchoTranslation("Auto-ban") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "autoban");
PrintHidden("action", "save");

PrintCheckboxRow("AutoBanOnLogonFailure", "Enabled", $AutoBanOnLogonFailure);
PrintPropertyEditRow("MaxInvalidLogonAttempts", "Max invalid logon attempts", $MaxInvalidLogonAttempts, 4, "number", "medium");
PrintPropertyEditRow("MaxInvalidLogonAttemptsWithin", "Minutes before reset", $MaxInvalidLogonAttemptsWithin, 4, "number", "medium");
PrintPropertyEditRow("AutoBanMinutes", "Minutes to auto-ban", $AutoBanMinutes, 5, "number", "medium");

PrintSaveButton();
?>
      </form>
    </div>