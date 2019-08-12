<?php

if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", 0, true);
$accountid = hmailGetVar("accountid", 0, true);
$faid = hmailGetVar("faid", 0, true);
$action = hmailGetVar("action","");

if (hmailGetAdminLevel() == 0 && ($accountid != hmailGetAccountID() || $domainid != hmailGetDomainID()))
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp();

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obAccount = $obDomain->Accounts->ItemByDBID($accountid);

if ($action == "edit") {
	$obFetchAccount = $obAccount->FetchAccounts->ItemByDBID($faid);
	$Enabled = $obFetchAccount->Enabled;
	$Name = $obFetchAccount->Name;
	$DaysToKeepMessages = $obFetchAccount->DaysToKeepMessages;
	$MinutesBetweenFetch = $obFetchAccount->MinutesBetweenFetch;
	$Port = $obFetchAccount->Port;
	$ProcessMIMERecipients = $obFetchAccount->ProcessMIMERecipients;
	$ProcessMIMEDate = $obFetchAccount->ProcessMIMEDate;
	$ServerAddress = $obFetchAccount->ServerAddress;
	$ServerType = $obFetchAccount->ServerType;
	$Username = $obFetchAccount->Username;
	$UseAntiSpam = $obFetchAccount->UseAntiSpam;
	$UseAntiVirus = $obFetchAccount->UseAntiVirus;
	$EnableRouteRecipients = $obFetchAccount->EnableRouteRecipients;
	$ConnectionSecurity = $obFetchAccount->ConnectionSecurity;
} else {
	$Enabled = 0;
	$Name = "";
	$DaysToKeepMessages = 0;
	$MinutesBetweenFetch = 30;
	$Port = 110;
	$ProcessMIMERecipients = 0;
	$ProcessMIMEDate = 0;
	$ServerAddress = "";
	$ServerType = 0;
	$Username = "";
	$UseAntiSpam = 0;
	$UseAntiVirus = 0;
	$EnableRouteRecipients = 0;
	$ConnectionSecurity = 0;
}

$EnabledChecked = hmailCheckedIf1($Enabled);
$ProcessMIMERecipientsChecked = hmailCheckedIf1($ProcessMIMERecipients);
$ProcessMIMEDateChecked = hmailCheckedIf1($ProcessMIMEDate);

$DaysToKeepMessagesValue = 7;
if ($DaysToKeepMessages > 0)
	$DaysToKeepMessagesValue = $DaysToKeepMessages;
?>
    <div class="box medium">
      <h2><?php EchoTranslation("External account") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_account_externalaccount_save");
PrintHidden("action", $action);
PrintHidden("faid", $faid);
PrintHidden("domainid", $domainid);
PrintHidden("accountid", $accountid);

PrintPropertyEditRow("Name", "Name", $Name, 255, "medium");
PrintCheckboxRow("Enabled", "Enabled", $Enabled);
?>
        <h3><a href="#"><?php EchoTranslation("Server information")?></a></h3>
        <div class="hidden">
          <p><?php EchoTranslation("Type")?></p>
          <select name="Type" class="medium">
            <option value="0" selected>POP3</option>
          </select>
<?php
PrintPropertyEditRow("ServerAddress", "Server address", $ServerAddress, 255, "medium");
PrintPropertyEditRow("Port", "TCP/IP port", $Port, 10, "medium number");
?>
          <p><?php EchoTranslation("Connection security")?></p>
          <select name="ConnectionSecurity" class="medium">
            <option value="<?php echo CONNECTION_SECURITY_NONE?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_NONE) echo "selected";?> ><?php EchoTranslation("None")?></a>
            <option value="<?php echo CONNECTION_SECURITY_STARTTLSREQUIRED?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_STARTTLSREQUIRED) echo "selected";?> ><?php EchoTranslation("STARTTLS (Required)")?></a>
            <option value="<?php echo CONNECTION_SECURITY_TLS?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_TLS) echo "selected";?> ><?php EchoTranslation("SSL/TLS")?></a>
          </select>
<?php
PrintPropertyEditRow("Username", "User name", $Username, 255, "medium");
PrintPasswordEntry("Password", "Password", 255, "medium");
?>
        </div>
        <h3><a href="#"><?php EchoTranslation("Settings")?></a></h3>
        <div class="hidden">
<?php
PrintPropertyEditRow("MinutesBetweenFetch", "Minutes between download", $MinutesBetweenFetch, 10, "number", "small");
PrintCheckboxRow("ProcessMIMERecipients", "Deliver to recipients in MIME headers", $ProcessMIMERecipients);
echo '          <div style="padding-left:18px;">';
PrintCheckboxRow("EnableRouteRecipients", "Allow route recipients", $EnableRouteRecipients);
echo '          </div>';
PrintCheckboxRow("ProcessMIMEDate", "Retrieve date from Received header", $ProcessMIMEDate);
PrintCheckboxRow("UseAntiSpam", "Anti-spam", $UseAntiSpam);
PrintCheckboxRow("UseAntiVirus", "Anti-virus", $UseAntiVirus);
?>
          <div style="position:relative;"><input type="radio" name="DaysToKeepMessages" value="-1" id="1" <?php if ($DaysToKeepMessages == -1) echo "checked";?>><label for="1"><?php EchoTranslation("Delete messages immediately")?></label></div>
          <div style="position:relative; display:inline-block;"><input type="radio" name="DaysToKeepMessages" value="" id="3" <?php if ($DaysToKeepMessages > 0) echo "checked";?>><label for="3"><?php EchoTranslation("Delete messages after")?></label></div>
          <input type="text" name="DaysToKeepMessagesValue" value="<?php echo PreprocessOutput($DaysToKeepMessagesValue)?>" class="num small"> <?php EchoTranslation("days")?>
          <div style="position:relative;"><input type="radio" name="DaysToKeepMessages" value="0" id="2" <?php if ($DaysToKeepMessages == 0) echo "checked";?>><label for="2"><?php EchoTranslation("Do not delete messages")?></label></div>
        </div>
<?php
PrintSaveButton();
?>
      </form>
    </div>