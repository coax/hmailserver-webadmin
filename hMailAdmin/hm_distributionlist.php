<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", null);
$distributionlistid = hmailGetVar("distributionlistid", 0);
$action = hmailGetVar("action", "");
$error_message = hmailGetVar("error_message", "");

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$listaddress = "";
$listactive = 0;
$Mode = 0;
$listrequiresmtpauth = 0;

if ($action == "edit") {
	$obList = $obDomain->DistributionLists->ItemByDBID($distributionlistid);
	$listaddress = $obList->Address;
	$listaddress = substr($listaddress, 0, strpos($listaddress, "@"));
	$listactive = $obList->Active;
	$Mode = $obList->Mode;
	$RequireSenderAddress = $obList->RequireSenderAddress;
	$listrequiresmtpauth = $obList->RequireSMTPAuth;
} else {
	$RequireSenderAddress = "";
}

$domainname = $obDomain->Name;
$listactivechecked = hmailCheckedIf1($listactive); //fixme, not working
$listrequiresmtpauthchecked = hmailCheckedIf1($listrequiresmtpauth); //fixme, not working
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
        <input type="text" name="listaddress" value="<?php echo PreprocessOutput($listaddress) ?>" size="255" checkallownull="false" checkmessage="<?php EchoTranslation("Address") ?>" class="req medium"> @<?php echo $domainname ?>
<?php
PrintCheckboxRow("listactive", "Enabled", $listactivechecked);
?>
        <h3><a href="#"><?php EchoTranslation("Security")?></a></h3>
        <div class="hidden">
          <p><?php EchoTranslation("Mode")?></p>
          <select name="Mode">
            <option value="0"<?php if ($Mode == 0) echo " selected";?>><?php EchoTranslation("Public - Anyone can send to the list") ?></option>
            <option value="1"<?php if ($Mode == 1) echo " selected";?>><?php EchoTranslation("Membership - Only members can send to the list") ?></option>
            <option value="2"<?php if ($Mode == 2) echo " selected";?>><?php EchoTranslation("Announcements - Only allow messages from the following address:") ?></option>
          </select>
<?php
PrintPropertyEditRow("RequireSenderAddress", "Address", $RequireSenderAddress, 255);
PrintCheckboxRow("listrequiresmtpauth", "Require SMTP authentication", $listrequiresmtpauthchecked);
?>
        </div>
<?php
if ($action=='edit') {
?>
        <h3><a href="#"><?php EchoTranslation("Members") ?></a></h3>
        <div class="hidden">
          <table>
            <thead>
              <tr>
                <th><?php EchoTranslation("Name") ?></th>
                <th style="width:32px;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
	$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
	$obList = $obDomain->DistributionLists->ItemByDBID($distributionlistid);
	$obRecipients = $obList->Recipients;
	$Count = $obRecipients->Count();
	$str_yes = $obLanguage->String("Yes");
	$str_no = $obLanguage->String("No");
	$str_delete = $obLanguage->String("Remove");
	$str_confirm = $obLanguage->String("Confirm delete");

	for ($i = 0; $i < $Count; $i++) {
		$obRecipient = $obRecipients->Item($i);
		$recipientaddress = $obRecipient->RecipientAddress;
		$recipientid = $obRecipient->ID;
		$recipientaddress = PreprocessOutput($recipientaddress);

		echo '              <tr>
                <td><a href="?page=distributionlist_recipient&action=edit&domainid=' . $domainid . '&distributionlistid=' . $distributionlistid . '&recipientid=' . $recipientid . '">' . $recipientaddress . '</a></td>
                <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $recipientaddress . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_distributionlist_recipient_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '&distributionlistid=' . $distributionlistid . '&recipientid=' . $recipientid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
              </tr>' . PHP_EOL;
	}
?>
            </tbody>
          </table>
          <div class="buttons center"><a href="?page=distributionlist_recipient&action=add&domainid=<?php echo $domainid?>&distributionlistid=<?php echo $distributionlistid?>" class="button"><?php EchoTranslation("Add") ?></a></div>
        </div>
<?php
} else {
?>
        <div class="warning"><?php EchoTranslation("You must save distribution list before you can edit members.") ?></div>
<?php
}
PrintSaveButton();
?>
      </form>
    </div>