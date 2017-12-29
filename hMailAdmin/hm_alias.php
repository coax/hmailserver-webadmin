<?php

if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", 0, true);
$aliasid = hmailGetVar("aliasid", 0, true);
$action = hmailGetVar("action","");

$error_message = hmailGetVar("error_message","");

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);

$aliasname = "";
$aliasvalue = "";
$aliasactive = 0;

if ($action == "edit") {
	$obAlias = $obDomain->Aliases->ItemByDBID($aliasid);
	$aliasname = $obAlias->Name;
	$aliasvalue = $obAlias->Value;
	$aliasactive  = $obAlias->Active;
	$aliasname = substr($aliasname, 0, strpos($aliasname, "@"));
}

$domainname = $obDomain->Name;

$aliasactivechecked = hmailCheckedIf1($aliasactive);
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Alias") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
if (strlen($error_message) > 0) {
	$error_message = $obLanguage->String($error_message);
	echo '<div class="warning">' . $error_message . '</div>';
}

PrintHiddenCsrfToken();
PrintHidden("page", "background_alias_save");
PrintHidden("action", $action);
PrintHidden("domainid", $domainid);
PrintHidden("aliasid", $aliasid);
?>
        <p><?php EchoTranslation("Redirect from") ?></p>
        <input type="text" name="aliasname" value="<?php echo PreprocessOutput($aliasname)?>" maxlength="100" checkallownull="false" checkmessage="<?php EchoTranslation("Redirect from")?>" class="req medium">@<?php echo $domainname ?>
<?php
PrintPropertyEditRow("aliasvalue", "To", $aliasvalue, 100);
PrintCheckboxRow("aliasactive", "Enabled", $aliasactive);

PrintSaveButton();
?>
      </form>
    </div>