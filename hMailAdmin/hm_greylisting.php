<?php
if (!defined('IN_WEBADMIN'))
   exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$action	   = hmailGetVar("action","");

$obSettings	= $obBaseApp->Settings();
$antiSpamSettings = $obSettings->AntiSpam;

if($action == "save")
{
	$antiSpamSettings->GreyListingEnabled = hmailGetVar("greylistingenabled", 0);
	$antiSpamSettings->GreyListingInitialDelay = hmailGetVar("greylistinginitialdelay", 0);
	$antiSpamSettings->GreyListingInitialDelete = hmailGetVar("greylistinginitialdelete", 0) * 24;
	$antiSpamSettings->GreyListingFinalDelete = hmailGetVar("greylistingfinaldelete", 0) * 24;

   $antiSpamSettings->BypassGreylistingOnSPFSuccess = hmailGetVar("BypassGreylistingOnSPFSuccess", 0);
   $antiSpamSettings->BypassGreylistingOnMailFromMX = hmailGetVar("BypassGreylistingOnMailFromMX", 0);

}

$greylistingenabled =   $antiSpamSettings->GreyListingEnabled;
$greylistinginitialdelay = $antiSpamSettings->GreyListingInitialDelay;
$greylistinginitialdelete = $antiSpamSettings->GreyListingInitialDelete / 24;
$greylistingfinaldelete = $antiSpamSettings->GreyListingFinalDelete / 24;
$greylistingenabledchecked = hmailCheckedIf1($greylistingenabled);
$BypassGreylistingOnSPFSuccess = $antiSpamSettings->BypassGreylistingOnSPFSuccess;
$BypassGreylistingOnMailFromMX = $antiSpamSettings->BypassGreylistingOnMailFromMX;

?>
    <div class="box medium">
      <h2><?php EchoTranslation("Greylisting") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "greylisting");
PrintHidden("action", "save");

PrintCheckboxRow("greylistingenabled", "Enabled", $greylistingenabled);
PrintPropertyEditRow("greylistinginitialdelay", "Minutes to defer delivery attempts", $greylistinginitialdelay, 11, "", "small");
PrintPropertyEditRow("greylistinginitialdelete", "Days before removing unused records", $greylistinginitialdelete, 11, "", "small");
PrintPropertyEditRow("greylistingfinaldelete", "Days before removing used records", $greylistingfinaldelete, 11, "", "small");

PrintCheckboxRow("BypassGreylistingOnSPFSuccess", "Bypass Greylisting on SPF Pass", $BypassGreylistingOnSPFSuccess);
PrintCheckboxRow("BypassGreylistingOnMailFromMX", "Bypass Greylisting when message arrives from A or MX record.", $BypassGreylistingOnMailFromMX);

PrintSaveButton();
?>
      </form>
    </div>