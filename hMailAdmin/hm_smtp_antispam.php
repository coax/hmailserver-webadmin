<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obAntiSpam = $obSettings->AntiSpam;

$action = hmailGetVar("action","");

$antiSpamSettings = $obSettings->AntiSpam;

if($action == "save") {
	$antiSpamSettings->SpamMarkThreshold = hmailGetVar("SpamMarkThreshold",0);
	$antiSpamSettings->SpamDeleteThreshold = hmailGetVar("SpamDeleteThreshold",0);

	$antiSpamSettings->SpamAssassinEnabled = hmailGetVar("SpamAssassinEnabled", 0);
	$antiSpamSettings->SpamAssassinHost = hmailGetVar("SpamAssassinHost", 0);
	$antiSpamSettings->SpamAssassinPort = hmailGetVar("SpamAssassinPort", 0);
	$antiSpamSettings->SpamAssassinMergeScore = hmailGetVar("SpamAssassinMergeScore", 0);
	$antiSpamSettings->SpamAssassinScore = hmailGetVar("SpamAssassinScore", 0);

	$antiSpamSettings->UseSPF= hmailGetVar("usespf",0);
	$antiSpamSettings->UseSPFScore = hmailGetVar("usespfscore",0);
	$antiSpamSettings->UseMXChecks= hmailGetVar("usemxchecks",0);
	$antiSpamSettings->UseMXChecksScore = hmailGetVar("usemxchecksscore",0);
	$antiSpamSettings->CheckHostInHelo = hmailGetVar("checkhostinhelo", 0);
	$antiSpamSettings->CheckHostInHeloScore = hmailGetVar("checkhostinheloscore", 0);


	$antiSpamSettings->AddHeaderSpam = hmailGetVar("AddHeaderSpam", 0);
	$antiSpamSettings->AddHeaderReason = hmailGetVar("AddHeaderReason", 0);
	$antiSpamSettings->PrependSubject = hmailGetVar("PrependSubject", 0);
	$antiSpamSettings->PrependSubjectText = hmailGetVar("PrependSubjectText", "");
	$antiSpamSettings->MaximumMessageSize = hmailGetVar("MaximumMessageSize", 0);

	$antiSpamSettings->DKIMVerificationEnabled = hmailGetVar("DKIMVerificationEnabled", 0);
	$antiSpamSettings->DKIMVerificationFailureScore = hmailGetVar("DKIMVerificationFailureScore", 0);
}

$SpamMarkThreshold = $antiSpamSettings->SpamMarkThreshold;
$SpamDeleteThreshold = $antiSpamSettings->SpamDeleteThreshold;
$MaximumMessageSize = $antiSpamSettings->MaximumMessageSize;

$SpamAssassinEnabled = $antiSpamSettings->SpamAssassinEnabled;
$SpamAssassinHost = $antiSpamSettings->SpamAssassinHost;
$SpamAssassinPort = $antiSpamSettings->SpamAssassinPort;
$SpamAssassinMergeScore = $antiSpamSettings->SpamAssassinMergeScore;
$SpamAssassinScore = $antiSpamSettings->SpamAssassinScore;

$usespf = $antiSpamSettings->UseSPF;
$usespfscore = $antiSpamSettings->UseSPFScore;
$usemxchecks = $antiSpamSettings->UseMXChecks;
$usemxchecksscore = $antiSpamSettings->UseMXChecksScore;
$checkhostinhelo = $antiSpamSettings->CheckHostInHelo;
$checkhostinheloscore = $antiSpamSettings->CheckHostInHeloScore;

$DKIMVerificationEnabled = $antiSpamSettings->DKIMVerificationEnabled;
$DKIMVerificationFailureScore = $antiSpamSettings->DKIMVerificationFailureScore;

$AddHeaderSpam = $antiSpamSettings->AddHeaderSpam;
$AddHeaderReason = $antiSpamSettings->AddHeaderReason;
$PrependSubject = $antiSpamSettings->PrependSubject;
$PrependSubjectText = $antiSpamSettings->PrependSubjectText;

$AddHeaderSpamChecked = hmailCheckedIf1($AddHeaderSpam);
$AddHeaderReasonChecked = hmailCheckedIf1($AddHeaderReason);
$PrependSubjectChecked = hmailCheckedIf1($PrependSubject);
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Anti-spam") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "smtp_antispam");
PrintHidden("action", "save");

PrintPropertyEditRow("SpamMarkThreshold", "Spam mark threshold", $SpamMarkThreshold, 20, "number", "small");
PrintCheckboxRow("AddHeaderSpam", "Add X-hMailServer-Spam", $AddHeaderSpam);
PrintCheckboxRow("AddHeaderReason", "Add X-hMailServer-Reason", $AddHeaderReason);
PrintCheckboxRow("PrependSubject", "Add to message subject", $PrependSubject);
PrintPropertyEditRow("PrependSubjectText", "Add to message subject", $PrependSubjectText, 20);

PrintPropertyEditRow("SpamDeleteThreshold", "Spam delete threshold", $SpamDeleteThreshold, 6, "number", "small");
PrintPropertyEditRow("MaximumMessageSize", "Maximum message size to scan (KB)", $MaximumMessageSize, 6, "number", "small");
?>
        <h3><a href="#"><?php EchoTranslation("Spam tests")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("usespf", "Use SPF", $usespf);
PrintPropertyEditRow("usespfscore", "Score", $usespfscore, 4, "number", "small");
PrintCheckboxRow("checkhostinhelo", "Check host in the HELO command", $checkhostinhelo);
PrintPropertyEditRow("checkhostinheloscore", "Score", $checkhostinheloscore, 4, "number", "small");
PrintCheckboxRow("usemxchecks", "Check that sender has DNS-MX records", $usemxchecks);
PrintPropertyEditRow("usemxchecksscore", "Score", $usemxchecksscore, 4, "number", "small");
PrintCheckboxRow("DKIMVerificationEnabled", "Verify DKIM-Signature header", $DKIMVerificationEnabled);
PrintPropertyEditRow("DKIMVerificationFailureScore", "Score", $DKIMVerificationFailureScore, 4, "number", "small");
?>
        </div>
        <h3><a href="#">SpamAssassin</a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("SpamAssassinEnabled", "Use SpamAssassin", $SpamAssassinEnabled);
PrintPropertyEditRow("SpamAssassinHost", "Host name", $SpamAssassinHost);
PrintPropertyEditRow("SpamAssassinPort", "TCP/IP port", $SpamAssassinPort, 10, "number");
PrintCheckboxRow("SpamAssassinMergeScore", "Use score from SpamAssassin", $SpamAssassinMergeScore);
PrintPropertyEditRow("SpamAssassinScore", "Score", $SpamAssassinScore, 4, "number", "small");
?>
          <p>Test SpamAssassin connection</p>
          <a href="#" onclick="return TestScanner('SpamAssassin');" class="button">Test</a>
          <div id="SpamAssassinTestResult"></div>
        </div>
<?php
PrintSaveButton();
?>
      </form>
    </div>