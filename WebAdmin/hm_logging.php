<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obLogging = $obSettings->Logging();
$action = hmailGetVar("action","");

if($action == "save") {
	$obLogging->Enabled = hmailGetVar("logenabled",0);
	$obLogging->LogApplication = hmailGetVar("logapplication",0);
	$obLogging->LogSMTP = hmailGetVar("logsmtp",0);
	$obLogging->LogPOP3 = hmailGetVar("logpop3",0);
	$obLogging->LogIMAP = hmailGetVar("logimap",0);
	$obLogging->LogTCPIP = hmailGetVar("logtcpip",0);
	$obLogging->LogDebug = hmailGetVar("logdebug",0);
	$obLogging->AwstatsEnabled = hmailGetVar("logawstats",0);
	$obLogging->KeepFilesOpen = hmailGetVar("KeepFilesOpen",0);
}

$logenabled = $obLogging->Enabled;
$logapplication = $obLogging->LogApplication;
$logsmtp = $obLogging->LogSMTP;
$logpop3 = $obLogging->LogPOP3;
$logimap = $obLogging->LogIMAP;
$logtcpip = $obLogging->LogTCPIP;
$logdebug = $obLogging->LogDebug;
$logawstats = $obLogging->AwstatsEnabled;
$KeepFilesOpen = $obLogging->KeepFilesOpen;
?>
    <div class="box">
      <h2><?php EchoTranslation("Logging") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "logging");
	PrintHidden("action", "save");

	PrintCheckboxRow("logenabled", "Enabled", $logenabled);
?>
        <h3>Log</h3>
<?php
	PrintCheckboxRow("logapplication", "Application", $logapplication);
	PrintCheckboxRow("logsmtp", "SMTP", $logsmtp);
	PrintCheckboxRow("logpop3", "POP3", $logpop3);
	PrintCheckboxRow("logimap", "IMAP", $logimap);
	PrintCheckboxRow("logdebug", "Debug", $logdebug);
	PrintCheckboxRow("logtcpip", "TCP/IP", $logtcpip);
	PrintCheckboxRow("logawstats", "AWStats", $logawstats);
?>
        <h3>Settings</h3>
<?php
	PrintCheckboxRow("KeepFilesOpen", "Keep files open", $KeepFilesOpen);

	PrintSaveButton();
?>
      </form>
    </div>