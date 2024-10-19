<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obScripting = $obSettings->Scripting();
$action = hmailGetVar("action","");

if($action == "save") {
	$obScripting->Enabled = hmailGetVar("scriptingenabled",0);

	$language = hmailGetVar("scriptinglanguage",0);
	if ($language != "VBScript" && $language != "JScript") {
		echo 'Unsupported language';
		die;
	}

	$obScripting->Language = hmailGetVar("scriptinglanguage",0);
} elseif ($action == "checksyntax"){
	$syntax_result = $obScripting->CheckSyntax();
	$message = $syntax_result;

} elseif ($action == "reloadscripts") {
	$obScripting->Reload();
	$message = Translate("Scripts reloaded");
}

$scriptingenabled = $obScripting->Enabled;
$scriptinglanguage = $obScripting->Language;
$scriptingenabledchecked = hmailCheckedIf1($scriptingenabled);
?>
    <div class="box">
      <h2><?php EchoTranslation("Scripts") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "scripts");
PrintHidden("action", "save");

PrintCheckboxRow("scriptingenabled", "Enabled", $scriptingenabled);
?>
        <select name="scriptinglanguage" class="medium">
          <option value="VBScript" <?php if ($scriptinglanguage == "VBScript") echo "selected"; ?>>VBScript</option>
          <option value="JScript" <?php if ($scriptinglanguage == "JScript") echo "selected"; ?>>JScript</option>
        </select>
<?php
PrintSaveButton();
?>
      </form>
    </div>
    <div class="box">
      <h2><?php EchoTranslation("Actions") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "scripts");
PrintHidden("action", "checksyntax");
?>
        <p><button><?php EchoTranslation("Check syntax")?></button></p>
      </form>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "scripts");
PrintHidden("action", "reloadscripts");
?>
        <p><button><?php EchoTranslation("Reload scripts")?></button></p>
      </form>
<?php
if (isset($message))
	if ($message)
		echo '      <p class="warning" style="margin-top:18px;">' . $message . '</p>';
?>
    </div>