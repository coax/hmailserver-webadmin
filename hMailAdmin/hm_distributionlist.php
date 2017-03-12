<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid",null);
$distributionlistid = hmailGetVar("distributionlistid",0);
$action = hmailGetVar("action","");

$error_message = hmailGetVar("error_message","");

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);

$listaddress="";
$listactive=0;
$listrequiresmtpauth=0;

$Mode = 0;

if ($action == "edit")
{
	$obList = $obDomain->DistributionLists->ItemByDBID($distributionlistid);

	$listaddress = $obList->Address;
	$listactive = $obList->Active;
	$listrequiresmtpauth = $obList->RequireSMTPAuth;
	$Mode = $obList->Mode;
	$RequireSenderAddress = $obList->RequireSenderAddress;

	$listaddress = substr($listaddress, 0, strpos($listaddress, "@"));
} else {
	$RequireSenderAddress = "";
}

$domainname = $obDomain->Name;

$listactivechecked = hmailCheckedIf1($listactive);
$listrequiresmtpauthchecked = hmailCheckedIf1($listrequiresmtpauth);
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Distribution list") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
if (strlen($error_message) > 0) {
	$error_message = $obLanguage->String($error_message);
	echo '<div class="warning">' . $error_message . '</div>';
}

PrintHiddenCsrfToken();
PrintHidden("page", "background_distributionlist_save");
PrintHidden("action", $action);
PrintHidden("distributionlistid", $distributionlistid);
PrintHidden("domainid", $domainid);
?>
        <p><?php EchoTranslation("Address") ?></p>
        <input type="text" name="listaddress" value="<?php echo PreprocessOutput($listaddress)?>" size="30" checkallownull="false" checkmessage="<?php EchoTranslation("Address")?>" class="req medium"> @<?php echo $domainname?>
<?php
PrintCheckboxRow("listactivechecked", "Enabled", $listactivechecked);
?>
        <h3><a href="#"><?php EchoTranslation("Security")?></a></h3>
        <div class="hidden">
          <p><?php EchoTranslation("Mode")?></p>
          <select name="Mode">
            <option value="0" <?php if ($Mode == 0) echo "selected";?> ><?php EchoTranslation("Public - Anyone can send to the list")?></option>
            <option value="1" <?php if ($Mode == 1) echo "selected";?> ><?php EchoTranslation("Membership - Only members can send to the list")?></option>
            <option value="2" <?php if ($Mode == 2) echo "selected";?> ><?php EchoTranslation("Announcements - Only allow messages from the following address:")?></option>
          </select>
<?php
PrintPropertyEditRow("RequireSenderAddress", "Address", $RequireSenderAddress, 30);
PrintCheckboxRow("listrequiresmtpauth", "Require SMTP Authentication", $listrequiresmtpauth);
?>
        </div>
<?php
PrintSaveButton();
?>
      </form>
    </div>