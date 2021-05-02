<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obAntivirus = $obSettings->AntiVirus();

$action = hmailGetVar("action","");

$str_delete = Translate("Remove");

if($action == "save") {
	$obAntivirus->Action = hmailGetVar("avaction",0);
	$obAntivirus->NotifySender = hmailGetVar("avnotifysender",0);
	$obAntivirus->NotifyReceiver = hmailGetVar("avnotifyreceiver",0);
	$obAntivirus->MaximumMessageSize = hmailGetVar("MaximumMessageSize",0);

	$obAntivirus->ClamWinEnabled = hmailGetVar("clamwinenabled",0);
	$obAntivirus->ClamWinExecutable = hmailGetVar("clamwinexecutable",0);
	$obAntivirus->ClamWinDBFolder = hmailGetVar("clamwindbfolder",0);

	$obAntivirus->ClamAVEnabled = hmailGetVar("ClamAVEnabled",0);
	$obAntivirus->ClamAVHost = hmailGetVar("ClamAVHost","");
	$obAntivirus->ClamAVPort = hmailGetVar("ClamAVPort","");

	$obAntivirus->CustomScannerEnabled = hmailGetVar("customscannerenabled",0);
	$obAntivirus->CustomScannerExecutable = hmailGetVar("customscannerexecutable",0);
	$obAntivirus->CustomScannerReturnValue = hmailGetVar("customscannerreturnvalue",0);

	$obAntivirus->EnableAttachmentBlocking = hmailGetVar("EnableAttachmentBlocking",0);
}

$avaction = $obAntivirus->Action;
$avnotifysender = $obAntivirus->NotifySender;
$avnotifyreceiver = $obAntivirus->NotifyReceiver;
$MaximumMessageSize = $obAntivirus->MaximumMessageSize;

$EnableAttachmentBlocking = $obAntivirus->EnableAttachmentBlocking;

$clamwinenabled = $obAntivirus->ClamWinEnabled;
$clamwinexecutable = $obAntivirus->ClamWinExecutable;
$clamwindbfolder = $obAntivirus->ClamWinDBFolder;

$ClamAVEnabled = $obAntivirus->ClamAVEnabled;
$ClamAVHost = $obAntivirus->ClamAVHost;
$ClamAVPort = $obAntivirus->ClamAVPort;

$customscannerenabled = $obAntivirus->CustomScannerEnabled;
$customscannerexecutable = $obAntivirus->CustomScannerExecutable;
$customscannerreturnvalue = $obAntivirus->CustomScannerReturnValue;

$avactiondeletemailchecked = hmailCheckedIf1($avaction == 0);
$avactiondeletattachmentschecked = hmailCheckedIf1($avaction == 1);
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Anti-virus") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "smtp_antivirus");
PrintHidden("action", "save");
?>
        <p><?php EchoTranslation("When a virus is found")?></p>
        <div style="position:relative; display:inline-block;"><input type="radio" name="avaction" id="1" value="0" <?php echo $avactiondeletemailchecked?>><label for="1"><?php EchoTranslation("Delete e-mail")?></label></div>
        <div style="position:relative; display:inline-block;"><input type="radio" name="avaction" id="2" value="1" <?php echo $avactiondeletattachmentschecked?>><label for="2"><?php EchoTranslation("Delete attachments")?></label></div>
<?php
PrintPropertyEditRow("MaximumMessageSize", "Maximum message size to virus scan (KB)", $MaximumMessageSize, 10, "number", "small");
?>
        <h3><a href="#">ClamWin</a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("clamwinenabled", "Enabled", $clamwinenabled);
PrintPropertyEditRow("clamwinexecutable", "ClamScan executable", $clamwinexecutable, 255);
PrintPropertyEditRow("clamwindbfolder", "Path to ClamScan database", $clamwindbfolder, 255);
?>
          <div class="buttons bottom"><input type="button" value="<?php EchoTranslation("Test")?>" onclick="return TestScanner('ClamWin');"></div>
          <div id="ClamWinTestResult" class="bottom"></div>
        </div>
        <h3><a href="#">ClamAV</a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("ClamAVEnabled", "Use ClamAV", $ClamAVEnabled);
PrintPropertyEditRow("ClamAVHost", "Host name", $ClamAVHost);
PrintPropertyEditRow("ClamAVPort", "TCP/IP port", $ClamAVPort, 5, "number");
?>
          <div class="buttons bottom"><input type="button" value="<?php EchoTranslation("Test")?>" onclick="return TestScanner('ClamAV');"></div>
          <div id="ClamAVTestResult" class="bottom"></div>
        </div>
        <h3><a href="#"><?php EchoTranslation("External virus scanner")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("customscannerenabled", "Enabled", $customscannerenabled);
PrintPropertyEditRow("customscannerexecutable", "Scanner executable", $customscannerexecutable, 255);
PrintPropertyEditRow("customscannerreturnvalue", "Return value", $customscannerreturnvalue, 5, "number");
?>
          <div class="buttons bottom"><input type="button" value="<?php EchoTranslation("Test")?>" onclick="return TestScanner('External');"></div>
          <div id="ExternalTestResult" class="bottom"></div>
        </div>
        <h3><a href="#"><?php EchoTranslation("Block attachments")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("EnableAttachmentBlocking", "Block attachments with the following extensions:", $EnableAttachmentBlocking);
?>
        <table>
          <thead>
            <tr>
              <th><?php EchoTranslation("Name")?></th>
              <th style="width:60%;"><?php EchoTranslation("Description")?></th>
              <th style="width:32px;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
$blockedAttachments = $obAntivirus->BlockedAttachments;

$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");

for ($i = 0; $i < $blockedAttachments->Count; $i++) {
	$blockedAttachment = $blockedAttachments->Item($i);
	$id = $blockedAttachment->ID;
	$wildcard = $blockedAttachment->Wildcard;
	$description= $blockedAttachment->Description;

	echo '            <tr>
              <td><a href="?page=blocked_attachment&action=edit&id=' . $id . '">' . PreprocessOutput($wildcard) . '</a></td>
              <td>' . PreprocessOutput($description) . '</td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . PreprocessOutput($wildcard) . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_blocked_attachment_save&csrftoken=' . $csrftoken . '&action=delete&id=' . $id . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center bottom"><a href="?page=blocked_attachment&action=add" class="button"><?php EchoTranslation("Add new extension") ?></a></div>
        </div>
<?php
PrintSaveButton();
?>
      </form>
    </div>