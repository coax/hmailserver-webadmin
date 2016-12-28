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
	$obScripting->Language = hmailGetVar("scriptinglanguage",0);
} elseif ($action == "checksyntax"){
	$syntax_result = $obScripting->CheckSyntax();
} elseif ($action == "reloadscripts") {
	$obScripting->Reload();
}

$scriptingenabled = $obScripting->Enabled;
$scriptinglanguage = $obScripting->Language;
$scriptingenabledchecked = hmailCheckedIf1($scriptingenabled);
?>
    <div class="box">
      <h2><?php EchoTranslation("Scripts") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
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
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "scripts");
	PrintHidden("action", "checksyntax");

if ($action == "checksyntax")
	echo $syntax_result;
	echo "<br/>";
?>
        <p><input type="submit" value="<?php EchoTranslation("Check syntax")?>"></p>
      </form>
      <form action="index.php" method="post" onsubmit="return formCheck(this);" class="cd-form">
<?php
	PrintHidden("page", "scripts");
	PrintHidden("action", "reloadscripts");
?>
        <p><input type="submit" value="<?php EchoTranslation("Reload scripts")?>"></p>
      </form>
    </div>