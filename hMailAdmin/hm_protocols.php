<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$action = hmailGetVar("action", "");

if($action == "save") {
	$obSettings->ServiceSMTP= hmailGetVar("servicesmtp", 0);
	$obSettings->ServicePOP3= hmailGetVar("servicepop3", 0);
	$obSettings->ServiceIMAP= hmailGetVar("serviceimap", 0);
}

$servicesmtp = $obSettings->ServiceSMTP;
$servicepop3 = $obSettings->ServicePOP3;
$serviceimap = $obSettings->ServiceIMAP;
?>
    <div class="box">
      <h2><?php EchoTranslation("Protocols") ?></h2>
      <form action="index.php" method="post" class="form">
        <p class="warning bottom"><?php EchoTranslation("If you change the settings below you must restart the server before your changes take affect.") ?></p>
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "protocols");
PrintHidden("action", "save");

PrintCheckboxRow("servicesmtp", "SMTP", $servicesmtp);
PrintCheckboxRow("servicepop3", "POP3", $servicepop3);
PrintCheckboxRow("serviceimap", "IMAP", $serviceimap);

PrintSaveButton();
?>
      </form>
    </div>