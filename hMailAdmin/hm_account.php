<?php
if (!defined('IN_WEBADMIN'))
	exit();

$DomainId = hmailGetVar("domainid", 0, true);
$accountid = hmailGetVar("accountid", 0, true);
$action = hmailGetVar("action","");

$error_message = hmailGetVar("error_message","");

if (hmailGetAdminLevel() == 0 && ($accountid != hmailGetAccountID() || $action != "edit" || $DomainId != hmailGetDomainID()))
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $DomainId != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($DomainId);

$admin_rights = (hmailGetAdminLevel() === ADMIN_SERVER || hmailGetAdminLevel() === ADMIN_DOMAIN);

$accountactive = 1;
$AccountMaxSize = 0;
$AccountSize = 0;
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
	$AccountMaxSize = $obAccount->MaxSize;
	$accountaddress = $obAccount->Address;
	$accountactive = $obAccount->Active;
	$AccountSize = $obAccount->Size();
	$accountlastlogontime = $obAccount->LastLogonTime();
	$accountadminlevel = $obAccount->AdminLevel();
	$accountaddress = substr($accountaddress, 0, strpos($accountaddress, "@"));
	$PersonFirstName = $obAccount->PersonFirstName;
	$PersonLastName = $obAccount->PersonLastName;
	$vacationmessageon = $obAccount->VacationMessageIsOn;
	$vacationsubject = $obAccount->VacationSubject;
	$vacationmessage = $obAccount->VacationMessage;
	$vacationmessageexpires = $obAccount->VacationMessageExpires;
	$vacationmessageexpiresdate = makeIsoDate($obAccount->VacationMessageExpiresDate);
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

$str_user = Translate("User");
$str_domain = Translate("Domain");
$str_server = Translate("Server");
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Account") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
if (strlen($error_message) > 0) {
	$error_message = PreprocessOutput(Translate($error_message));
	echo '        <div class="warning">' . $error_message . '</div>';
}

PrintHiddenCsrfToken();
PrintHidden("page", "background_account_save");
PrintHidden("action", $action);
PrintHidden("domainid", $obDomain->ID);
PrintHidden("accountid", $accountid);
?>
        <p><?php EchoTranslation("Address")?></p>
<?php
$accountaddress = PreprocessOutput($accountaddress);

if ($admin_rights)

	echo '        <input type="text" name="accountaddress" value="' . $accountaddress . '" class="req medium">';
else
	echo $accountaddress;
?> @<?php echo $domainname . PHP_EOL; ?>
        <p><?php EchoTranslation("Password")?></p>
        <input type="password" name="accountpassword" value="" class="medium" autocomplete="off">
        <p><?php EchoTranslation("Maximum size (MB)") ?></p>
<?php
$AccountMaxSize = PreprocessOutput($AccountMaxSize);

if ($admin_rights)
	echo '        <input type="text" name="accountmaxsize" value="' . $AccountMaxSize . '" class="req number small">' . PHP_EOL;
else
	echo '        <b>' . $AccountMaxSize . '</b>' . PHP_EOL;

if ($accountid>0) {
	PrintPropertyRow("Size (MB)", Round($AccountSize,3));

	//Bar
	if ($AccountMaxSize>0) {
		$Color = "#b1d786";
		$Width = floor(($AccountSize / $AccountMaxSize) * 100);
		if ($Width>=90) $Color = "#f77673";
		elseif ($Width>=70) $Color = "#ffb565";
		elseif ($Width<1) $Width = 1;

		echo '        <div style="margin:-14px 0 18px 0; width:100%; height:13px; background:#f2f2f2; border-radius:2px;"><div style="width:' . $Width . '%; height:100%; background:' . $Color . '; border-radius:2px;"></div></div>';
	}

	PrintPropertyRow("Last logon time", $accountlastlogontime);
}
?>
        <p><?php EchoTranslation("Administration level")?></p>
        <select name="accountadminlevel" <?php if ($admin_rights == 0) echo " disabled ";?> class="medium">
<?php
if ($admin_rights >= 0) {
	echo '        <option value="0"';
	if ($accountadminlevel == 0) echo " selected ";
	echo '>'.$str_user.'</option>';
}
if ($admin_rights == 1) {
	echo '        <option value="1"';
	if ($accountadminlevel == 1) echo " selected ";
	echo '>'.$str_domain.'</option>';
}
if (hmailGetAdminLevel() === ADMIN_SERVER) {
	echo '        <option value="2"';
	if ($accountadminlevel == 2) echo " selected ";
	echo '>'.$str_server.'</option>';
}
?>
        </select>
<?php
if ($admin_rights)
	PrintCheckboxRow("accountactive", "Enabled", $accountactive);
else {
	if ($accountactive == 1)
		echo '        <p>Enabled</p>';
	else
		echo '        <p>Disabled</p>';
}
?>
        <h3><a href="#"><?php EchoTranslation("Auto-reply")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("vacationmessageon", "Enabled", $vacationmessageon);
PrintPropertyEditRow("vacationsubject", "Subject", $vacationsubject, 200);
PrintPropertyAreaRow("vacationmessage", "Text", $vacationmessage, 4, 55);
PrintCheckboxRow("vacationmessageexpires", "Automatically expire", $vacationmessageexpires);
?>
          <p><?php EchoTranslation("Expiration date")?> (YYYY-MM-DD)</p>
          <input type="text" name="vacationmessageexpiresdate" value="<?php echo $vacationmessageexpiresdate ?>" maxlength="10" data-toggle="datepicker" class="medium">
        </div>
        <h3><a href="#"><?php EchoTranslation("Forwarding")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("forwardenabled", "Enabled", $forwardenabled);
PrintPropertyEditRow("forwardaddress", "Address", $forwardaddress, 255);
PrintCheckboxRow("forwardkeeporiginal", "Keep original message", $forwardkeeporiginal);
?>
        </div>
        <h3><a href="#"><?php EchoTranslation("Signature")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("SignatureEnabled", "Enabled", $SignatureEnabled);
PrintPropertyAreaRow("SignaturePlainText", "Plain text signature", $SignaturePlainText, 4, 55);
PrintPropertyAreaRow("SignatureHTML", "HTML signature", $SignatureHTML, 4, 55);
?>
        </div>
<?php
if (hmailGetAdminLevel() != ADMIN_USER) {
?>
        <h3><a href="#"><?php EchoTranslation("Active Directory")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("adenabled", "Enabled", $adenabled);
PrintPropertyEditRow("addomain", "Domain", $addomain, 255);
PrintPropertyEditRow("adusername", "User name", $adusername, 255);
?>
        </div>
<?php
}

if (GetHasRuleAccess($DomainId, $accountid)) {
?>
        <h3><a href="#"><?php EchoTranslation("Rules")?></a></h3>
        <div class="hidden">
<?php
if ($accountid == 0) {
	echo '<p class="warning bottom">' . Translate("You must save the account before you can edit rules.") . '</p>';
} else {
?>
          <table>
            <thead>
              <tr>
                <th><?php EchoTranslation("Name") ?></th>
                <th style="width:20%;"><?php EchoTranslation("Enabled") ?></th>
                <th style="width:10%;"><?php EchoTranslation("Move") ?></th>
                <th style="width:32px;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
	$rules = $obAccount->Rules();
	$Count = $rules->Count();

	$str_delete = Translate("Remove");
	$str_confirm = Translate("Confirm delete");
	$str_yes = Translate("Yes");
	$str_no = Translate("No");

	for ($i = 0; $i < $Count; $i++) {
		$rule = $rules->Item($i);
		$rulename = $rule->Name;
		$ruleid = $rule->ID;
		$enabled = $rule->Active ? $str_yes : $str_no;

		$rulename = PreprocessOutput($rulename);

		$move = '';
		if ($i > 0)
			$move = $move . '<a href="?page=background_rule_save&csrftoken=' . $csrftoken . '&action=move&savetype=ruleup&domainid=' . $DomainId . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '" class="arrow up">Up</a>';
		if ($i < $Count-1)
			$move = $move . '<a href="?page=background_rule_save&csrftoken=' . $csrftoken . '&action=move&savetype=ruledown&domainid=' . $DomainId . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '" class="arrow down">Down</a>';

		echo '              <tr>
                <td><a href="?page=rule&action=edit&domainid=' . $DomainId . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '">' . $rulename . '</a></td>
                <td>' . $enabled . '</td>
                <td>' . $move . '</td>
                <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $rulename . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_rule_save&csrftoken=' . $csrftoken . '&savetype=rule&action=delete&domainid=' . $DomainId . '&accountid=' . $accountid . '&action=delete&ruleid=' . $ruleid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
              </tr>' . PHP_EOL;

	}
?>
            </tbody>
          </table>
          <div class="buttons center bottom"><a href="?page=rule&domainid=<?php echo $DomainId ?>&accountid=<?php echo $accountid ?>&action=add" class="button"><?php EchoTranslation("Add new rule") ?></a></div>
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
PrintPropertyEditRow("PersonFirstName", "First name", $PersonFirstName, 60);
PrintPropertyEditRow("PersonLastName", "Last name", $PersonLastName, 60);
?>
        </div>
<?php if ($accountid > 0) { ?>
        <h3><a href="#"><?php EchoTranslation("External accounts") ?></a></h3>
        <div class="hidden">
          <table>
            <thead>
              <tr>
                <th><?php EchoTranslation("Name")?></th>
                <th style="width:40%;"><?php EchoTranslation("Server address")?></th>
                <th style="width:32px;">&nbsp;</th>
                <th style="width:32px;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
$obFetchAccounts = $obAccount->FetchAccounts();
$Count = $obFetchAccounts->Count();

$str_downloadnow = Translate("Download now");

for ($i = 0; $i < $Count; $i++) {
	$obFetchAccount = $obFetchAccounts->Item($i);

	$FAID = $obFetchAccount->ID;
	$Name = $obFetchAccount->Name;
	$ServerAddress = $obFetchAccount->ServerAddress;

	echo '              <tr>
                <td><a href="?page=account_externalaccount&csrftoken=' . $csrftoken . '&action=edit&domainid=' . $DomainId . '&accountid=' . $accountid . '&faid=' . $FAID . '">' . $Name . '</a></td>
                <td><a href="?page=account_externalaccount&csrftoken=' . $csrftoken . '&action=edit&domainid=' . $DomainId . '&accountid=' . $accountid . '&faid=' . $FAID . '">' . $ServerAddress . '</a></td>
                <td><a href="?page=background_account_externalaccount_save&csrftoken=' . $csrftoken . '&action=downloadnow&domainid=' . $DomainId . '&accountid=' . $accountid . '&faid=' . $FAID . '" class="download">' . $str_downloadnow . '</a></td>
                <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $Name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_account_externalaccount_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $DomainId . '&accountid=' . $accountid . '&faid=' . $FAID . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
              </tr>' . PHP_EOL;
}
?>
            </tbody>
          </table>
          <div class="buttons center bottom"><a href="?page=account_externalaccount&action=add&domainid=<?php echo $DomainId ?>&accountid=<?php echo $accountid ?>" class="button"><?php EchoTranslation("Add new external account") ?></a></div>
        </div>
<?php
};
PrintSaveButton(null, null, '?page=accounts&domainid=' . $DomainId);
?>
      </form>
    </div>
