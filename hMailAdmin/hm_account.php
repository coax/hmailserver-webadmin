<?php
if (!defined('IN_WEBADMIN'))
	exit();

// Request variables
$DomainId = hmailGetVar("domainid", 0, true);
$AccountId = hmailGetVar("accountid", 0, true);
$Action = hmailGetVar("action", "");
$error_message = hmailGetVar("error_message", "");

// User can only edit his account
if (hmailGetAdminLevel() == 0 && ($AccountId != hmailGetAccountID() || $Action != "edit" || $DomainId != hmailGetDomainID()))
	hmailHackingAttemp();

// Domain admin but not for this domain
if (hmailGetAdminLevel() == 1 && $DomainId != hmailGetDomainID())
	hmailHackingAttemp();

$obDomain = $obBaseApp->Domains->ItemByDBID($DomainId);
$DomainName = $obDomain->Name;

$admin_rights = (hmailGetAdminLevel() === ADMIN_SERVER || hmailGetAdminLevel() === ADMIN_DOMAIN);

$AccountActive = 1;
$AccountMaxSize = 0;
$AccountSize = 0;
$AccountAddress = "";
$AccountLastLogonTime = "";
$AccountAdminLevel = 0;
$PersonFirstName = "";
$PersonLastName = "";
$VacationMessageOn = 0;
$VacationSubject = "";
$VacationMessage = "";
$VacationMessageExpires = 0;
$VacationMessageExpiresDate = "";
$ForwardEnabled = 0;
$ForwardAddress = "";
$ForwardKeepOriginal = 0;
$AdEnabled = 0;
$AdDomain = "";
$AdUsername = "";
$SignatureEnabled = 0;
$SignatureHTML = "";
$SignaturePlainText = "";

if ($Action == "edit") {
	$obAccount = $obDomain->Accounts->ItemByDBID($AccountId);
	$AccountMaxSize = $obAccount->MaxSize;
	$AccountAddress = $obAccount->Address;
	$AccountActive = $obAccount->Active;
	$AccountSize = $obAccount->Size();
	$AccountLastLogonTime = $obAccount->LastLogonTime();
	$AccountAdminLevel = $obAccount->AdminLevel();
	$AccountAddress = substr($AccountAddress, 0, strpos($AccountAddress, "@"));
	$PersonFirstName = $obAccount->PersonFirstName;
	$PersonLastName = $obAccount->PersonLastName;
	$VacationMessageOn = $obAccount->VacationMessageIsOn;
	$VacationSubject = $obAccount->VacationSubject;
	$VacationMessage = $obAccount->VacationMessage;
	$VacationMessageExpires = $obAccount->VacationMessageExpires;
	$VacationMessageExpiresDate = makeIsoDate($obAccount->VacationMessageExpiresDate);
	$VacationMessageExpiresDate = substr($VacationMessageExpiresDate, 0, 10);
	$ForwardEnabled = $obAccount->ForwardEnabled;
	$ForwardAddress = $obAccount->ForwardAddress;
	$ForwardKeepOriginal = $obAccount->ForwardKeepOriginal;
	$AdEnabled = $obAccount->IsAD;
	$AdDomain = $obAccount->ADDomain;
	$AdUsername = $obAccount->ADUsername;
	$SignatureEnabled = $obAccount->SignatureEnabled;
	$SignatureHTML = $obAccount->SignatureHTML;
	$SignaturePlainText = $obAccount->SignaturePlainText;
}

$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");
$str_yes = Translate("Yes");
$str_no = Translate("No");
?>
      <div class="box medium">
        <h2><?php EchoTranslation("Account") ?></h2>
        <form action="index.php" method="post" class="form">
<?php
if (strlen($error_message) > 0) {
	$error_message = PreprocessOutput(Translate($error_message));
	echo '          <div class="warning bottom">' . $error_message . '</div>';
}

PrintHiddenCsrfToken();
PrintHidden("page", "background_account_save");
PrintHidden("action", $Action);
PrintHidden("domainid", $obDomain->ID);
PrintHidden("accountid", $AccountId);
?>
          <p><?php EchoTranslation("Address") ?></p>
<?php
$AccountAddress = PreprocessOutput($AccountAddress);

if ($admin_rights)
	echo '          <input type="text" name="accountaddress" value="' . $AccountAddress . '" maxlength="255" class="req medium">';
else
	echo '          <b>' . $AccountAddress . '</b>' . PHP_EOL;
?> @<?php echo $DomainName . PHP_EOL; ?>
          <p><?php EchoTranslation("Password") ?></p>
          <input type="password" name="accountpassword" value="" autocomplete="off" placeholder="<< <?php EchoTranslation("Encrypted") ?> >>" class="medium">
          <p><?php EchoTranslation("Maximum size (MB)") ?></p>
<?php
$AccountMaxSize = PreprocessOutput($AccountMaxSize);

if ($admin_rights)
	echo '          <input type="text" name="accountmaxsize" value="' . $AccountMaxSize . '" class="req number small">' . PHP_EOL;
else
	echo '          <b>' . $AccountMaxSize . '</b>' . PHP_EOL;

if ($AccountId > 0) {
	PrintPropertyRow("Size (MB)", Round($AccountSize,3));

	// Quota bar
	if ($AccountMaxSize > 0) {
		$Color = "#b1d786";
		$Width = floor(($AccountSize / $AccountMaxSize) * 100);
		if ($Width >= 90) $Color = "#f77673";
		elseif ($Width >= 70) $Color = "#ffb565";
		elseif ($Width < 1) $Width = 1;

		echo '          <div style="margin:-14px 0 18px 0; width:100%; height:13px; background:#f2f2f2; border-radius:2px;"><div style="width:' . $Width . '%; height:100%; background:' . $Color . '; border-radius:2px;"></div></div>';
	}

	PrintPropertyRow("Last logon time", $AccountLastLogonTime);
}
?>
          <p><?php EchoTranslation("Administration level") ?></p>
          <select name="accountadminlevel" <?php if ($admin_rights == 0) echo " disabled "; ?> class="small">
<?php
if ($admin_rights >= 0) {
	echo '            <option value="0"';
	if ($AccountAdminLevel == 0) echo " selected ";
	echo '>' . Translate("User") . '</option>' . PHP_EOL;
}
if ($admin_rights == 1) {
	echo '            <option value="1"';
	if ($AccountAdminLevel == 1) echo " selected ";
	echo '>' . Translate("Domain") . '</option>' . PHP_EOL;
}
if (hmailGetAdminLevel() === ADMIN_SERVER) {
	echo '            <option value="2"';
	if ($AccountAdminLevel == 2) echo " selected ";
	echo '>' . Translate("Server") . '</option>' . PHP_EOL;
}
?>
          </select>
<?php
if ($admin_rights)
	PrintCheckboxRow("accountactive", "Enabled", $AccountActive);
else {
	if ($AccountActive == 1)
		echo '          <p>Enabled</p><b></b>';
	else
		echo '          <p>Disabled</p><b></b>';
}
?>
          <h3><a href="#"><?php EchoTranslation("Auto-reply") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("vacationmessageon", "Enabled", $VacationMessageOn);
PrintPropertyEditRow("vacationsubject", "Subject", $VacationSubject, 200);
PrintPropertyAreaRow("vacationmessage", "Text", $VacationMessage, 4, 55);
PrintCheckboxRow("vacationmessageexpires", "Automatically expire", $VacationMessageExpires);
?>
            <p><?php EchoTranslation("Expiration date") ?> (YYYY-MM-DD)</p>
            <input type="text" name="vacationmessageexpiresdate" value="<?php echo $VacationMessageExpiresDate ?>" maxlength="10" data-toggle="datepicker" class="medium">
          </div>
          <h3><a href="#"><?php EchoTranslation("Forwarding") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("forwardenabled", "Enabled", $ForwardEnabled);
PrintPropertyEditRow("forwardaddress", "Address", $ForwardAddress, 255);
PrintCheckboxRow("forwardkeeporiginal", "Keep original message", $ForwardKeepOriginal);
?>
          </div>
          <h3><a href="#"><?php EchoTranslation("Signature") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("SignatureEnabled", "Enabled", $SignatureEnabled);
PrintPropertyAreaRow("SignaturePlainText", "Plain text signature", $SignaturePlainText, 4, 55);
PrintPropertyAreaRow("SignatureHTML", "HTML signature", $SignatureHTML, 4, 55);
?>
          </div>
<?php
if ($AccountId > 0) {
?>
          <h3><a href="#"><?php EchoTranslation("External accounts") ?></a></h3>
          <div class="hidden">
            <table>
              <thead>
                <tr>
                  <th><?php EchoTranslation("Name") ?></th>
                  <th style="width:40%;"><?php EchoTranslation("Server address") ?></th>
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

		echo '                <tr>
                  <td><a href="?page=account_externalaccount&csrftoken=' . $csrftoken . '&action=edit&domainid=' . $DomainId . '&accountid=' . $AccountId . '&faid=' . $FAID . '">' . $Name . '</a></td>
                  <td><a href="?page=account_externalaccount&csrftoken=' . $csrftoken . '&action=edit&domainid=' . $DomainId . '&accountid=' . $AccountId . '&faid=' . $FAID . '">' . $ServerAddress . '</a></td>
                  <td><a href="?page=background_account_externalaccount_save&csrftoken=' . $csrftoken . '&action=downloadnow&domainid=' . $DomainId . '&accountid=' . $AccountId . '&faid=' . $FAID . '" class="download">' . $str_downloadnow . '</a></td>
                  <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $Name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_account_externalaccount_save&action=delete&domainid=' . $DomainId . '&accountid=' . $AccountId . '&faid=' . $FAID . '&csrftoken=' . $csrftoken . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
                </tr>' . PHP_EOL;
	}
?>
              </tbody>
            </table>
            <div class="buttons center bottom"><a href="?page=account_externalaccount&action=add&domainid=<?php echo $DomainId ?>&accountid=<?php echo $AccountId ?>" class="button"><?php EchoTranslation("Add new external account") ?></a></div>
          </div>
<?php
};

if (GetHasRuleAccess($DomainId, $AccountId)) {
?>
          <h3><a href="#"><?php EchoTranslation("Rules") ?></a></h3>
          <div class="hidden">
<?php
	if ($AccountId == 0) {
?>
            <p class="warning bottom"><?php EchoTranslation("You must save the account before you can edit rules.") ?></p>
<?php
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

		for ($i = 0; $i < $Count; $i++) {
			$rule = $rules->Item($i);
			$rulename = $rule->Name;
			$ruleid = $rule->ID;
			$enabled = $rule->Active ? $str_yes : $str_no;

			$rulename = PreprocessOutput($rulename);

			$move = '';
			if ($i > 0)
				$move = $move . '<a href="?page=background_rule_save&action=move&savetype=ruleup&domainid=' . $DomainId . '&accountid=' . $AccountId . '&ruleid=' . $ruleid . '&csrftoken=' . $csrftoken . '" class="arrow up">Up</a>';
			if ($i < $Count-1)
				$move = $move . '<a href="?page=background_rule_save&action=move&savetype=ruledown&domainid=' . $DomainId . '&accountid=' . $AccountId . '&ruleid=' . $ruleid . '&csrftoken=' . $csrftoken . '" class="arrow down">Down</a>';

			echo '                <tr>
                  <td><a href="?page=rule&action=edit&domainid=' . $DomainId . '&accountid=' . $AccountId . '&ruleid=' . $ruleid . '">' . $rulename . '</a></td>
                  <td>' . $enabled . '</td>
                  <td>' . $move . '</td>
                  <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $rulename . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_rule_save&action=delete&savetype=rule&domainid=' . $DomainId . '&accountid=' . $AccountId . '&ruleid=' . $ruleid . '&csrftoken=' . $csrftoken . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
                </tr>' . PHP_EOL;
		}
?>
              </tbody>
            </table>
            <div class="buttons center bottom"><a href="?page=rule&action=add&domainid=<?php echo $DomainId ?>&accountid=<?php echo $AccountId ?>" class="button"><?php EchoTranslation("Add new rule") ?></a></div>
<?php
	}
?>
          </div>
<?php
}

if (hmailGetAdminLevel() != ADMIN_USER) {
?>
          <h3><a href="#"><?php EchoTranslation("Active Directory") ?></a></h3>
          <div class="hidden">
            <p class="warning bottom"><?php EchoTranslation("By entering the fields below, you can connect this account to an Active Directory. When a user connects to the server, hMailServer will use the Active Directory to validate the user's password.") ?></p>
<?php
PrintCheckboxRow("adenabled", "Enabled", $AdEnabled);
PrintPropertyEditRow("addomain", "Domain", $AdDomain, 255);
PrintPropertyEditRow("adusername", "User name", $AdUsername, 255);
?>
          </div>
<?php
}
?>
          <h3><a href="#"><?php EchoTranslation("Advanced") ?></a></h3>
          <div class="hidden">
<?php
PrintPropertyEditRow("PersonFirstName", "First name", $PersonFirstName, 60);
PrintPropertyEditRow("PersonLastName", "Last name", $PersonLastName, 60);
?>
          </div>
<?php
if ($admin_rights) {
?>
          <h3><a href="#"><?php EchoTranslation("IMAP folders") ?></a></h3>
          <div class="hidden">
<?php
	if ($AccountId == 0) {
?>
            <p class="warning bottom"><?php EchoTranslation("You must save the account before you can edit IMAP folders.") ?></p>
<?php
	} else {
?>
            <table>
              <thead>
                <tr>
                  <th><?php EchoTranslation("Name") ?></th>
                  <th style="width:20%;"><?php EchoTranslation("Messages") ?></th>
                  <th style="width:32px;">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
<?php
		// Folders object
		$Folders = $obAccount->IMAPFolders();
		$TotalFolders = $Folders->Count();

		for ($i = 0; $i < $TotalFolders; $i++) {
			// Folder object
			$Folder = $Folders->Item($i);
			$FolderId = $Folder->ID;
			$FolderName = $Folder->Name;
			$FolderName = PreprocessOutput($FolderName);

			// Messages object
			$Messages = $Folder->Messages();
			$TotalMessages = $Messages->Count();

			echo '                <tr>
                  <td><a href="?page=account_imapfolder&action=edit&domainid=' . $DomainId . '&accountid=' . $AccountId . '&folderid=' . $FolderId . '">' . $FolderName . '</a></td>
                  <td>' . $TotalMessages . '</td>
                  <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $FolderName . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_account_imapfolder_save&action=delete&domainid=' . $DomainId . '&accountid=' . $AccountId . '&folderid=' . $FolderId . '&csrftoken=' . $csrftoken . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
                </tr>' . PHP_EOL;

			// Subfolders object - API only supports listing subfolders
			$SubFolders = $Folder->SubFolders;
			$TotalSubFolders = $SubFolders->Count();

			for ($j = 0; $j < $TotalSubFolders; $j++) {
				// Folder object
				$SubFolder = $SubFolders->Item($j);
				$SubFolderId = $SubFolder->ID;
				$SubFolderName = $SubFolder->Name;
				$SubFolderName = PreprocessOutput($SubFolderName);

				// Messages object
				$SubMessages = $SubFolder->Messages();
				$TotalMessages = $SubMessages->Count();

				echo '                <tr>
                  <td style="padding-left:20px;">' . $SubFolderName . '</td>
                  <td>' . $TotalMessages . '</td>
                  <td>&nbsp;</td>
                </tr>' . PHP_EOL;
			}
		}
?>
              </tbody>
            </table>
            <div class="buttons center bottom"><a href="?page=account_imapfolder&action=add&domainid=<?php echo $DomainId ?>&accountid=<?php echo $AccountId ?>" class="button"><?php EchoTranslation("Add new folder") ?></a></div>
<?php
	}
?>
          </div>
<?php
}

PrintSaveButton(null, null, "?page=accounts&domainid=$DomainId");
?>
        </form>
      </div>