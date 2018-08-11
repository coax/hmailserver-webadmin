<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();

$action = hmailGetVar("action","");

if($action == "save") {
	$obSettings->MaxPOP3Connections= hmailGetVar("maxpop3connections",0);
	$obSettings->WelcomePOP3= hmailGetVar("welcomepop3",0);
}

$maxpop3connections = $obSettings->MaxPOP3Connections;
$welcomepop3 = $obSettings->WelcomePOP3;
?>
    <div class="box medium">
      <h2><?php EchoTranslation("POP3") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "pop3");
PrintHidden("action", "save");

PrintPropertyEditRow("maxpop3connections", "Maximum number of simultaneous connections (0 for unlimited)", $maxpop3connections, 11, "number", "small");
PrintPropertyEditRow("welcomepop3", "Welcome message", $welcomepop3);

PrintSaveButton();
?>
      </form>
    </div>