<?php
if (!defined('IN_WEBADMIN'))
   exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings	= $obBaseApp->Settings();

$action	   = hmailGetVar("action","");

if($action == "save")
{
	$obSettings->WelcomeIMAP= hmailGetVar("welcomeimap",0);
	$obSettings->MaxIMAPConnections = hmailGetVar("MaxIMAPConnections",0);

	$obSettings->IMAPSortEnabled  = hmailGetVar("IMAPSortEnabled",0);
	$obSettings->IMAPQuotaEnabled = hmailGetVar("IMAPQuotaEnabled",0);
	$obSettings->IMAPIdleEnabled  = hmailGetVar("IMAPIdleEnabled",0);
	$obSettings->IMAPACLEnabled  = hmailGetVar("IMAPACLEnabled",0);

   $obSettings->IMAPHierarchyDelimiter = hmailGetVar("IMAPHierarchyDelimiter","");
}

$welcomeimap = $obSettings->WelcomeIMAP;
$MaxIMAPConnections = $obSettings->MaxIMAPConnections;

$IMAPSortEnabled  = $obSettings->IMAPSortEnabled;
$IMAPQuotaEnabled = $obSettings->IMAPQuotaEnabled;
$IMAPIdleEnabled  = $obSettings->IMAPIdleEnabled;
$IMAPACLEnabled  = $obSettings->IMAPACLEnabled;
$IMAPHierarchyDelimiter = $obSettings->IMAPHierarchyDelimiter;

?>
    <div class="box medium">
      <h2><?php EchoTranslation("IMAP") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "imap");
	PrintHidden("action", "save");

	PrintPropertyEditRow("MaxIMAPConnections", "Maximum number of simultaneous connections (0 for unlimited)", $MaxIMAPConnections, 50, "number", "small");
	PrintPropertyEditRow("welcomeimap", "Welcome message", $welcomeimap, 50);
?>
        <h3><a href="#"><?php EchoTranslation("Advanced")?></a></h3>
        <div class="hidden">
<?php
	PrintCheckboxRow("IMAPSortEnabled", "IMAP Sort", $IMAPSortEnabled);
	PrintCheckboxRow("IMAPQuotaEnabled", "IMAP Quota", $IMAPQuotaEnabled);
	PrintCheckboxRow("IMAPIdleEnabled", "IMAP Idle", $IMAPIdleEnabled);
	PrintCheckboxRow("IMAPACLEnabled", "IMAP ACL", $IMAPACLEnabled);
?>
          <p><?php EchoTranslation("Hierarchy delimiter")?></p>
          <select name="IMAPHierarchyDelimiter" class="small">
            <option value="." <?php if ($IMAPHierarchyDelimiter == ".") echo "selected";?> >.</option>
            <option value="\" <?php if ($IMAPHierarchyDelimiter == "\\") echo "selected";?> >\</option>
            <option value="/" <?php if ($IMAPHierarchyDelimiter == "/") echo "selected";?> >/</option>
          </select>
        </div>
<?php
	PrintSaveButton();
?>
      </form>
    </div>