<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obBackup = $obSettings->Backup();

$action = hmailGetVar("action","");

if($action == "save") {
	$obBackup->Destination = hmailGetVar("backupdestination",0);
	$obBackup->BackupSettings = hmailGetVar("backupsettings",0);
	$obBackup->BackupDomains = hmailGetVar("backupdomains",0);
	$obBackup->BackupMessages = hmailGetVar("backupmessages",0);
	$obBackup->CompressDestinationFiles = hmailGetVar("backupcompress",0);
} elseif ($action == "startbackup") {
	$obBaseApp->BackupManager->StartBackup();
}

$backupdestination = $obBackup->Destination;
$backupsettings = $obBackup->BackupSettings;
$backupdomains = $obBackup->BackupDomains;
$backupmessages = $obBackup->BackupMessages;
$backupcompress = $obBackup->CompressDestinationFiles;

$backupsettingschecked = hmailCheckedIf1($backupsettings);
$backupdomainschecked = hmailCheckedIf1($backupdomains);
$backupmessageschecked = hmailCheckedIf1($backupmessages);
$backupcompresschecked = hmailCheckedIf1($backupcompress);
?>
    <div class="box">
      <h2><?php EchoTranslation("Backup") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "backup");
PrintHidden("action", "save");

PrintPropertyEditRow("backupdestination", "Destination", $backupdestination, 255, "");
?>
        <p><?php EchoTranslation("Backup") ?></p>
<?php
PrintCheckboxRow("backupsettings", "Settings", $backupsettings);
PrintCheckboxRow("backupdomains", "Domains", $backupdomains);
PrintCheckboxRow("backupmessages", "Messages", $backupmessages);
PrintCheckboxRow("backupcompress", "Compress files", $backupcompress);

PrintSaveButton();
?>
      </form>
    </div>

    <div class="box">
      <h2><?php EchoTranslation("Actions") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "backup");
PrintHidden("action", "startbackup");
?>
        <p><input type="submit" value="<?php EchoTranslation("Start backup") ?>"></p>
      </form>
    </div>