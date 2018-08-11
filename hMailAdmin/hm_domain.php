<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", 0, true);
$action = hmailGetVar("action","");

if (hmailGetAdminLevel() == 1 && ($domainid != hmailGetDomainId() || $action != "edit"))
	hmailHackingAttemp();

$admin_rights = (hmailGetAdminLevel() === ADMIN_SERVER);

$domainname = "";
$domainactive = 1;
$domainpostmaster = "";
$DomainMaxSize = 0;
$domainplusaddressingenabled = 0;
$domainplusaddressingcharacter = "+";
$domainantispamenablegreylisting = 1;
$domainmaxmessagesize = 0;
$AllocatedSize = 0;
$SignatureEnabled = 0;
$SignatureHTML = "";
$SignaturePlainText = "";
$SignatureMethod = 1;
$AddSignaturesToLocalMail = 1;
$AddSignaturesToReplies = 0;
$MaxNumberOfAccounts = 0;
$MaxNumberOfAliases = 0;
$MaxNumberOfDistributionLists = 0;
$MaxAccountSize = 0;
$MaxNumberOfAccountsEnabled = 0;
$MaxNumberOfAliasesEnabled = 0;
$MaxNumberOfDistributionListsEnabled = 0;
$DKIMSignEnabled = 0;
$DKIMPrivateKeyFile = "";
$DKIMSelector = "";
$DKIMHeaderCanonicalizationMethod = 2;
$DKIMBodyCanonicalizationMethod = 2;
$DKIMSigningAlgorithm = 2;
$DomainId = 0;

if ($action == "edit") {
	$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
	$domainname = $obDomain->Name;
	$domainactive = $obDomain->Active;
	$domainpostmaster = $obDomain->Postmaster;
	$DomainMaxSize = $obDomain->MaxSize;
	$AllocatedSize = $obDomain->AllocatedSize;
	$domainmaxmessagesize = $obDomain->MaxMessageSize;
	$domainplusaddressingenabled = $obDomain->PlusAddressingEnabled;
	$domainplusaddressingcharacter = $obDomain->PlusAddressingCharacter;
	$domainantispamenablegreylisting = $obDomain->AntiSpamEnableGreylisting;
	$SignatureEnabled = $obDomain->SignatureEnabled;
	$SignatureHTML = $obDomain->SignatureHTML;
	$SignaturePlainText = $obDomain->SignaturePlainText;
	$SignatureMethod = $obDomain->SignatureMethod;
	$AddSignaturesToLocalMail = $obDomain->AddSignaturesToLocalMail;
	$AddSignaturesToReplies = $obDomain->AddSignaturesToReplies;
	$MaxAccountSize = $obDomain->MaxAccountSize;
	$MaxNumberOfAccounts = $obDomain->MaxNumberOfAccounts;
	$MaxNumberOfAliases = $obDomain->MaxNumberOfAliases;
	$MaxNumberOfDistributionLists = $obDomain->MaxNumberOfDistributionLists;
	$MaxNumberOfAccountsEnabled = $obDomain->MaxNumberOfAccountsEnabled;
	$MaxNumberOfAliasesEnabled = $obDomain->MaxNumberOfAliasesEnabled;
	$MaxNumberOfDistributionListsEnabled = $obDomain->MaxNumberOfDistributionListsEnabled;
	$DKIMSignEnabled = $obDomain->DKIMSignEnabled;
	$DKIMPrivateKeyFile = $obDomain->DKIMPrivateKeyFile;
	$DKIMSelector = $obDomain->DKIMSelector;
	$DKIMHeaderCanonicalizationMethod = $obDomain->DKIMHeaderCanonicalizationMethod;
	$DKIMBodyCanonicalizationMethod = $obDomain->DKIMBodyCanonicalizationMethod;
	$DKIMSigningAlgorithm = $obDomain->DKIMSigningAlgorithm;
	$DomainId = $obDomain->ID;
}

$domainactivechecked = hmailCheckedIf1($domainactive);
$domainplusaddressingenabledchecked = hmailCheckedIf1($domainplusaddressingenabled);
$domainantispamenablegreylistingchecked = hmailCheckedIf1($domainantispamenablegreylisting);
$SignatureEnabledChecked = hmailCheckedIf1($SignatureEnabled);
$AddSignaturesToLocalMailChecked = hmailCheckedIf1($AddSignaturesToLocalMail);
$AddSignaturesToRepliesChecked = hmailCheckedIf1($AddSignaturesToReplies);
$MaxNumberOfAccountsEnabledChecked = hmailCheckedIf1($MaxNumberOfAccountsEnabled);
$MaxNumberOfAliasesEnabledChecked = hmailCheckedIf1($MaxNumberOfAliasesEnabled);
$MaxNumberOfDistributionListsEnabledChecked = hmailCheckedIf1($MaxNumberOfDistributionListsEnabled);
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Domain") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_domain_save");
PrintHidden("action", $action);
PrintHidden("domainid", $DomainId);

$str_name = Translate("Name");
$str_yes = Translate("Yes");
$str_no = Translate("No");
$domainname = PreprocessOutput($domainname);
if ($admin_rights)
	PrintPropertyEditRow("domainname", "Domain name", $domainname, 80, " ");
else
	echo '<b>' . $domainname . '</b>';

if ($admin_rights) {
	PrintCheckboxRow("domainactive", "Enabled", $domainactive);

	if ($DomainId<>'') echo '        <div class="buttons bottom"><a href="?page=accounts&domainid=' . $DomainId . '" class="button">' . Translate("Show accounts") . '</a></div>';
} else {
	echo '<p>' . Translate("Active") . ':</p>';
	if ($domainactive == 1)
		echo '<b>' . $str_yes . '</b>';
	else
		echo '<b>' . $str_no . '</b>';
	}

	if (isset($obDomain) && $admin_rights) {
?>
          <h3><a href="#"><?php EchoTranslation("Names") ?></a></h3>
          <div class="hidden">
<?php
		if ($DomainId==0)
			echo '<p class="warning bottom">' . Translate("You must save this domain before you can edit names.") . '</p>' . PHP_EOL;
		else {
			$str_delete = Translate("Remove");
			$str_confirm = Translate("Confirm delete");
?>
            <table>
              <thead>
                <tr>
                  <th><?php EchoTranslation("Name") ?></th>
                  <th style="width:32px;">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
<?php
		$obDomainAliases = $obDomain->DomainAliases;

		for ($i = 0; $i < $obDomainAliases->Count; $i++) {
			$obDomainAlias = $obDomainAliases->Item($i);
			$aliasid = $obDomainAlias->ID;
			$name = $obDomainAlias->AliasName;

			echo '                <tr>
                  <td><a href="#">' . PreprocessOutput($name) . '</a></td>
                  <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . PreprocessOutput($name) . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_domain_name_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '&aliasid=' . $aliasid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
                </tr>' . PHP_EOL;
		}
?>
              </tbody>
            </table>
            <div class="buttons center bottom"><a href="?page=domain_aliasname&action=add&domainid=<?php echo $DomainId ?>" class="button"><?php EchoTranslation("Add") ?></a></div>
<?php
	}
?>
          </div>
<?php
}
?>
          <h3><a href="#"><?php EchoTranslation("Signature") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("SignatureEnabled", "Enabled", $SignatureEnabled);
?>
            <p style="margin-left:18px;">
              <select name="SignatureMethod">
                <option value="1" <?php if ($SignatureMethod == "1") echo "selected";?> ><?php EchoTranslation("Use signature if none has been specified in sender's account") ?></option>
                <option value="2" <?php if ($SignatureMethod == "2") echo "selected";?> ><?php EchoTranslation("Overwrite account signature") ?></option>
                <option value="3" <?php if ($SignatureMethod == "3") echo "selected";?> ><?php EchoTranslation("Append to account signature") ?></option>
              </select>
            </p>
<?php
PrintCheckboxRow("AddSignaturesToReplies", "Add signatures to replies", $AddSignaturesToReplies);
PrintCheckboxRow("AddSignaturesToLocalMail", "Add signatures to local email", $AddSignaturesToLocalMail);
PrintPropertyAreaRow("SignaturePlainText", "Plain text signature", $SignaturePlainText, 4, 50);
PrintPropertyAreaRow("SignatureHTML", "HTML signature", $SignatureHTML, 4, 50);
?>
          </div>
          <h3><a href="#"><?php EchoTranslation("Limits") ?></a></h3>
          <div class="hidden">
            <p><?php EchoTranslation("Allocated size (MB)") ?></p>
            <b><?php echo $AllocatedSize ?></b>
<?php
$DomainMaxSize = PreprocessOutput($DomainMaxSize);
$domainmaxmessagesize = PreprocessOutput($domainmaxmessagesize);
$MaxAccountSize = PreprocessOutput($MaxAccountSize);
$MaxNumberOfAccounts = PreprocessOutput($MaxNumberOfAccounts);
$MaxNumberOfAliases = PreprocessOutput($MaxNumberOfAliases);
$MaxNumberOfDistributionLists = PreprocessOutput($MaxNumberOfDistributionLists);

if ($admin_rights) {
	PrintPropertyEditRow("domainmaxsize", "Maximum size (MB)", $DomainMaxSize, 11, "number", "small");
	PrintPropertyEditRow("domainmaxmessagesize", "Max message size (KB)", $domainmaxmessagesize, 11, "number", "small");
	PrintPropertyEditRow("MaxAccountSize", "Max. size of accounts (MB)", $MaxAccountSize, 11, "number", "small");
	PrintPropertyEditRow("MaxNumberOfAccounts", "Max. number of accounts", $MaxNumberOfAccounts, 11, "number", "small");
	echo '            <p style="display:inline-block; vertical-align:-50%;"><input type="checkbox" name="MaxNumberOfAccountsEnabled" id="MaxNumberOfAccountsEnabled" value="1" ' . $MaxNumberOfAccountsEnabled . '><label for="MaxNumberOfAccountsEnabled"></label></p>' . PHP_EOL;
	PrintPropertyEditRow("MaxNumberOfAliases", "Max. number of aliases", $MaxNumberOfAliases, 11, "number", "small");
	echo '            <p style="display:inline-block; vertical-align:-50%;"><input type="checkbox" name="MaxNumberOfAliasesEnabled" id="MaxNumberOfAliasesEnabled" value="1" ' . $MaxNumberOfAliasesEnabledChecked . '><label for="MaxNumberOfAliasesEnabled"></label></p>' . PHP_EOL;
	PrintPropertyEditRow("MaxNumberOfDistributionLists", "Max. number of distribution lists", $MaxNumberOfDistributionLists, 11, "number", "small");
	echo '            <p style="display:inline-block; vertical-align:-50%;"><input type="checkbox" name="MaxNumberOfDistributionListsEnabled" id="MaxNumberOfDistributionListsEnabled" value="1" ' . $MaxNumberOfDistributionListsEnabledChecked . '><label for="MaxNumberOfDistributionListsEnabled"></label></p>' . PHP_EOL;
} else {
	PrintPropertyRow("Maximum size (MB)", Round($DomainMaxSize,3));
	PrintPropertyRow("Max. message size (KB)", Round($domainmaxmessagesize,3));
	PrintPropertyRow("Max. size of accounts (MB)", Round($MaxAccountSize,3));
	PrintPropertyRow("Max. number of accounts", Round($MaxNumberOfAccounts,3));
	PrintPropertyRow("Max. number of aliases", Round($MaxNumberOfAliases,3));
	PrintPropertyRow("Max. number of distribution lists", Round($MaxNumberOfDistributionLists,3));
}
?>
          </div>
          <h3><a href="#"><?php EchoTranslation("DKIM Signing") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("DKIMSignEnabled", Translate("Enabled"), $DKIMSignEnabled);
PrintPropertyEditRow("DKIMPrivateKeyFile", Translate("Private key file"), $DKIMPrivateKeyFile, 255);
PrintPropertyEditRow("DKIMSelector", Translate("Selector"), $DKIMSelector, 255);
?>
            <p><?php EchoTranslation("Header method") ?></p>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMHeaderCanonicalizationMethod" value="1" id="1" <?php if ($DKIMHeaderCanonicalizationMethod == 1) echo "checked"?>><label for="1"><?php EchoTranslation("Simple") ?></label></div>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMHeaderCanonicalizationMethod" value="2" id="2" <?php if ($DKIMHeaderCanonicalizationMethod == 2) echo "checked"?>><label for="2"><?php EchoTranslation("Relaxed") ?></label></div>
            <p><?php EchoTranslation("Body method") ?></p>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMBodyCanonicalizationMethod" value="1" id="3" <?php if ($DKIMBodyCanonicalizationMethod == 1) echo "checked"?>><label for="3"><?php EchoTranslation("Simple") ?></label></div>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMBodyCanonicalizationMethod" value="2" id="4" <?php if ($DKIMBodyCanonicalizationMethod == 2) echo "checked"?>><label for="4"><?php EchoTranslation("Relaxed") ?></label></div>
            <p><?php EchoTranslation("Signing algorithm") ?></p>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMSigningAlgorithm" value="1" id="5" <?php if ($DKIMSigningAlgorithm == 1) echo "checked"?>><label for="5">SHA1</label></div>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMSigningAlgorithm" value="2" id="6" <?php if ($DKIMSigningAlgorithm == 2) echo "checked"?>><label for="6">SHA256</label></div>
          </div>
          <h3><a href="#"><?php EchoTranslation("Advanced") ?></a></h3>
          <div class="hidden">
<?php
PrintPropertyEditRow("domainpostmaster", Translate("Catch-all address"), $domainpostmaster, 80);
?>
          </div>
          <h3><a href="#"><?php EchoTranslation("Plus addressing") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("domainplusaddressingenabled", Translate("Enabled"), $domainplusaddressingenabled);
?>
            <p><?php EchoTranslation("Character")?></p>
            <select name="domainplusaddressingcharacter" class="small">
              <option value="+" <?php if ($domainplusaddressingcharacter == "+") echo "selected";?>>+</option>
              <option value="-" <?php if ($domainplusaddressingcharacter == "-") echo "selected";?>>-</option>
              <option value="_" <?php if ($domainplusaddressingcharacter == "_") echo "selected";?>>_</option>
              <option value="%" <?php if ($domainplusaddressingcharacter == "%") echo "selected";?>>%</option>
            </select>
          </div>
          <h3><a href="#"><?php EchoTranslation("Greylisting")?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("domainantispamenablegreylisting", Translate("Enabled"), $domainantispamenablegreylisting);
?>
          </div>
<?php
PrintSaveButton(null, null, '?page=domains');
?>
      </form>
    </div>