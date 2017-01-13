<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid",0);
$accountid = hmailGetVar("accountid",0);
$action = hmailGetVar("action","");

$error_message = hmailGetVar("error_message","");

if (hmailGetAdminLevel() == 0 && ($accountid != hmailGetAccountID() || $action != "edit" || $domainid != hmailGetDomainID()))
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);

$admin_rights = (hmailGetAdminLevel()  === ADMIN_SERVER || hmailGetAdminLevel()  === ADMIN_DOMAIN);

$accountactive = 1;
$accountmaxsize = 0;
$accountsize = 0;
$accountaddress = "";
$accountlastlogontime = "";
$accountadminlevel = 0;
$PersonFirstName = "";
$PersonLastName = "";

$vacationmessageon = 0;
$vacationsubject = "";
$vacationmessage = "";

$vacationmessageexpires = 0;
$vacationmessageexpiresdate = "";

$forwardenabled = 0;
$forwardaddress = "";
$forwardkeeporiginal = 0;

$adenabled = 0;
$addomain = "";
$adusername = "";

$SignatureEnabled = 0;
$SignatureHTML = "";
$SignaturePlainText = "";

if ($action == "edit") {
	$obAccount = $obDomain->Accounts->ItemByDBID($accountid);

	$accountmaxsize = $obAccount->MaxSize;
	$accountaddress = $obAccount->Address;
	$accountactive = $obAccount->Active;
	$accountsize = $obAccount->Size();
	$accountlastlogontime = $obAccount->LastLogonTime();
	$accountadminlevel = $obAccount->AdminLevel();

	$accountaddress = substr($accountaddress, 0, strpos($accountaddress, "@"));

	$PersonFirstName = $obAccount->PersonFirstName;
	$PersonLastName = $obAccount->PersonLastName;

	$vacationmessageon = $obAccount->VacationMessageIsOn;
	$vacationsubject = $obAccount->VacationSubject;
	$vacationmessage = $obAccount->VacationMessage;

	$vacationmessageexpires = $obAccount->VacationMessageExpires;
	$vacationmessageexpiresdate = $obAccount->VacationMessageExpiresDate;
	$vacationmessageexpiresdate = substr($vacationmessageexpiresdate, 0, 10);



	$forwardenabled = $obAccount->ForwardEnabled;
	$forwardaddress = $obAccount->ForwardAddress;
	$forwardkeeporiginal = $obAccount->ForwardKeepOriginal;

	$adenabled = $obAccount->IsAD;
	$addomain = $obAccount->ADDomain;
	$adusername = $obAccount->ADUsername;

	$SignatureEnabled = $obAccount->SignatureEnabled;
	$SignatureHTML = $obAccount->SignatureHTML;
	$SignaturePlainText = $obAccount->SignaturePlainText;
}

$SignatureEnabledChecked = hmailCheckedIf1($SignatureEnabled);
$vacationmessageexpireschecked = hmailCheckedIf1($vacationmessageexpires);
$accountactivechecked = hmailCheckedIf1($accountactive);

$domainname = $obDomain->Name;

$str_user = $obLanguage->String("User");
$str_domain = $obLanguage->String("Domain");
$str_server = $obLanguage->String("Server");
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Account") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
if (strlen($error_message) > 0) {
	$error_message = PreprocessOutput($obLanguage->String($error_message));
	echo '<div class="warning">' . $error_message . '</div>';
}

	PrintHidden("page", "background_account_save");
	PrintHidden("action", $action);
	PrintHidden("domainid", $obDomain->ID);
	PrintHidden("accountid", $accountid);
?>
        <p><?php EchoTranslation("Address")?></p>
<?php
	$str_address = $obLanguage->String("Address");

	$accountaddress = PreprocessOutput($accountaddress);

	if ($admin_rights)
		echo '<input type="text" name="accountaddress" value="' . $accountaddress . '" checkallownull="false" checkmessage="' . $str_address . '" class="req medium">';
	else
		echo $accountaddress;
?>@<?php echo $domainname?>
        <p><?php EchoTranslation("Password")?></p>
        <input type="password" name="accountpassword" value="">
        <p><?php EchoTranslation("Maximum size (MB)")?></p>
<?php
	$str_mailboxsize = $obLanguage->String("Maximum size (MB)");

	$accountmaxsize = PreprocessOutput($accountmaxsize);

	if ($admin_rights)
		echo '<input type="text" name="accountmaxsize" value="' . $accountmaxsize . '" checktype="number" size="5" checkallownull="false" checkmessage="' . $str_mailboxsize . '" class="req number small">';
	else
		echo $accountmaxsize;

               PrintPropertyRow("Size (MB)", Round($accountsize,3));
               PrintPropertyRow("Last logon time", $accountlastlogontime);
?>
        <p><?php EchoTranslation("Administration level")?></p>
        <select name="accountadminlevel" <?php if ($admin_rights == 0) echo " disabled ";?> class="medium">
          <option value="0" <?php if ($accountadminlevel == 0) echo " selected "; ?>><?php echo $str_user; ?></option>
          <option value="1" <?php if ($accountadminlevel == 1) echo " selected "; ?>><?php echo $str_domain; ?></option>
          <option value="2" <?php if ($accountadminlevel == 2) echo " selected "; ?>><?php echo $str_server; ?></option>
        </select>
<?php
	if ($admin_rights)
		PrintCheckboxRow("accountactive", "Enabled", $accountactive);
	else {
		if ($accountactive == 1)
			echo 'Enabled';
		else
			echo 'Disabled';
	}
?>
        <h3><a href="#"><?php EchoTranslation("Auto-reply")?></a></h3>
        <div class="hidden">
<?php
	PrintCheckboxRow("vacationmessageon", "Enabled", $vacationmessageon);
	PrintPropertyEditRow("vacationsubject", "Subject", $vacationsubject);

	PrintPropertyAreaRow("vacationmessage", "Text", $vacationmessage, 6, 55);

	PrintCheckboxRow("vacationmessageexpires", "Automatically expires", $vacationmessageexpires);
	PrintPropertyEditRow("vacationmessageexpiresdate", "(YYYY-MM-DD)", $vacationmessageexpiresdate);
?>
        </div>
        <h3><a href="#"><?php EchoTranslation("Forwarding")?></a></h3>
        <div class="hidden">
<?php
	PrintCheckboxRow("forwardenabled", "Enabled", $forwardenabled);
	PrintPropertyEditRow("forwardaddress", "Address", $forwardaddress);
	PrintCheckboxRow("forwardkeeporiginal", "Keep original message", $forwardkeeporiginal);
?>
        </div>
        <h3><a href="#"><?php EchoTranslation("Signature")?></a></h3>
        <div class="hidden">
<?php
	PrintCheckboxRow("SignatureEnabled", "Enabled", $SignatureEnabled);
	PrintPropertyAreaRow("SignaturePlainText", "Plain text signature", $SignaturePlainText, 6, 55);
	PrintPropertyAreaRow("SignatureHTML", "HTML signature", $SignatureHTML, 6, 55);
?>
        </div>
<?php
if (hmailGetAdminLevel() != ADMIN_USER) {
?>
        <h3><a href="#"><?php EchoTranslation("Active Directory")?></a></h3>
        <div class="hidden">
<?php
	PrintCheckboxRow("adenabled", "Enabled", $adenabled);
	PrintPropertyEditRow("addomain", "Domain", $addomain);
	PrintPropertyEditRow("adusername", "User name", $adusername);
}
?>
        </div>
<?php
if (GetHasRuleAccess($domainid, $accountid)) {
?>
        <h3><a href="#"><?php EchoTranslation("Rules")?></a></h3>
        <div class="hidden">
<?php
if ($accountid == 0) {
	echo '<div class="warning">' . $obLanguage->String("You must save the account before you can edit rules.") . '</div>';
} else {
?>
          <table>
            <thead>
              <tr>
                <th style="width:55%;"><?php EchoTranslation("Name");?></th>
                <th style="width:20%;"><?php EchoTranslation("Enabled");?></th>
		<th style="width:15%;">Move rule</th>
                <th style="width:10%;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
	$rules = $obAccount->Rules();
	$Count = $rules->Count();

	$str_delete = $obLanguage->String("Remove");
	$str_yes = $obLanguage->String("Yes");
	$str_no = $obLanguage->String("No");

	for ($i = 0; $i < $Count; $i++) {
		$rule = $rules->Item($i);
		$rulename = $rule->Name;
		$ruleid = $rule->ID;
		$enabled = $rule->Active ? $str_yes : $str_no;

		$rulename = PreprocessOutput($rulename);

		echo '              <tr>
                <td><a href="?page=rule&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '">' . $rulename . '</a></td>
                <td><a href="#">' . $enabled . '</a></td>
		<td>';
		if($i>0)echo '<a href="?page=background_rule_save&action=move&savetype=ruleup&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '">Up</a>';
		if($i<$Count-1)echo '<a href="?page=background_rule_save&action=move&savetype=ruledown&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '">Down</a>';
                echo '</td>
		<td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $aliasname . '</b>:\',\'Yes\',\'?page=background_rule_save&savetype=rule&action=delete&domainid=' . $domainid . '&accountid=' . $accountid . '&action=delete&ruleid=' . $ruleid . '\');" class="delete">Delete</a></td>
              </tr>' . PHP_EOL;

	}
?>
            </tbody>
          </table>
          <div class="buttons center"><a href="?page=rule&domainid=$domainid&accountid=$accountid&action=add" class="button">Add new rule</a></div>
<?php
}
?>
        </div>
<?php
}
?>
        <h3><a href="#"><?php EchoTranslation("Advanced")?></a></h3>
        <div class="hidden">
<?php
	PrintPropertyEditRow("PersonFirstName", "First name", $PersonFirstName);
	PrintPropertyEditRow("PersonLastName", "Last name", $PersonLastName);
?>
        </div>
<?php
	PrintSaveButton();
?>
      </form>
    </div>
