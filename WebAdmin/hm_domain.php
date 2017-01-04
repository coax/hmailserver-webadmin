<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid",0);
$action = hmailGetVar("action","");

if (hmailGetAdminLevel() == 1 && ($domainid != hmailGetDomainID() || $action != "edit"))
	hmailHackingAttemp();

$admin_rights = (hmailGetAdminLevel()  === ADMIN_SERVER);

$domainname = "";
$domainactive = 1;
$domainpostmaster = "";
$domainmaxsize = 0;
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
$MaxNumberOfAliases  = 0;
$MaxNumberOfDistributionLists = 0;
$MaxAccountSize      = 0;

$MaxNumberOfAccountsEnabled = 0;
$MaxNumberOfAliasesEnabled  = 0;
$MaxNumberOfDistributionListsEnabled = 0;

$DKIMSignEnabled = 0;
$DKIMPrivateKeyFile = "";
$DKIMSelector = "";

$DKIMHeaderCanonicalizationMethod = 2;
$DKIMBodyCanonicalizationMethod = 2;
$DKIMSigningAlgorithm = 2;

$DomainID = 0;

if ($action == "edit") {
	$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);

	$domainname = $obDomain->Name;
	$domainactive = $obDomain->Active;
	$domainpostmaster = $obDomain->Postmaster;
	$domainmaxsize = $obDomain->MaxSize;
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

	$DomainID = $obDomain->ID;
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
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "background_domain_save");
	PrintHidden("action", $action);
	PrintHidden("domainid", $DomainID);


	$str_name = $obLanguage->String("Name");
	$domainname = PreprocessOutput($domainname);
	if ($admin_rights)
		PrintPropertyEditRow("domainname", "Domain name", $domainname);
	else
		echo $domainname;

	if ($admin_rights)
		PrintCheckboxRow("domainactive", "Enabled", $domainactive);
	else {
		if ($domainactive == 1)
			echo $obLanguage->String("Yes");
		else
			echo $obLanguage->String("No");
		}

		if (isset($obDomain) && hmailGetAdminLevel() == ADMIN_SERVER) {
			$str_delete = $obLanguage->String("Remove");
?>
          <h3><a href="#"><?php EchoTranslation("Names") ?></a></h3>
          <div class="hidden">
            <table>
              <thead>
                <tr>
                  <th style="width:95%;"><?php EchoTranslation("Name") ?></th>
                  <th style="width:5%;">&nbsp;</th>
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
                  <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . PreprocessOutput($name) . '</b>:\',\'Yes\',\'?page=background_domain_name_save&action=delete&domainid=' . $domainid . '&aliasid=' . $aliasid . '\');" class="delete">Delete</a></td>
                </tr>' . PHP_EOL;
		}
?>
              </tbody>
            </table>
            <div class="buttons center"><a href="?page=domain_aliasname&action=add&domainid=<?php echo $DomainID ?>" class="button"><?php EchoTranslation("Add") ?></a></div>
          </div>
<?php
	}
?>
          <h3><a href="#"><?php EchoTranslation("Signature") ?></a></h3>
          <div class="hidden">
<?php
	PrintCheckboxRow("SignatureEnabled", "Enabled", $SignatureEnabled);
?>

            <p>
              <select name="SignatureMethod">
                <option value="1" <?php if ($SignatureMethod == "1") echo "selected";?> ><?php EchoTranslation("Use signature if none has been specified in sender's account") ?></option>
                <option value="2" <?php if ($SignatureMethod == "2") echo "selected";?> ><?php EchoTranslation("Overwrite account signature") ?></option>
                <option value="3" <?php if ($SignatureMethod == "3") echo "selected";?> ><?php EchoTranslation("Append signature to account signature") ?></option>
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
            <p><?php EchoTranslation("Maximum size (MB)") ?></p>
<?php
	$str_maxsizemb = $obLanguage->String("Maximum size (MB)");
	$domainmaxsize = PreprocessOutput($domainmaxsize);

	if ($admin_rights)
		echo "<input type=\"text\" name=\"domainmaxsize\" value=\"$domainmaxsize\" size=\"8\" checkallownull=\"false\" checkmessage=\"$str_maxsizemb\" class=\"req small number\">";
	else
		echo $domainmaxsize;

	echo $obLanguage->String("<p>Allocated size (MB)</p>");
	echo "<input type=\"text\" name=\"dummy\" value=\"$AllocatedSize\" size=\"8\" readonly disabled class=\"small\">";
?>
            <p><?php EchoTranslation("Max message size (KB)")?></p>
<?php
	$str_warning = $obLanguage->String("Max message size (KB)");
	$domainmaxmessagesize = PreprocessOutput($domainmaxmessagesize);

	if ($admin_rights)
		echo "<input type=\"text\" name=\"domainmaxmessagesize\" value=\"$domainmaxmessagesize\" size=\"8\" checkallownull=\"false\" checkmessage=\"$str_warning\" class=\"req small number\">";
	else
		echo $domainmaxsize;
?>
            <p><?php EchoTranslation("Max size of accounts (MB)")?></p>
<?php
	$str_warning = $obLanguage->String("Max size of accounts (MB)");
	$MaxAccountSize = PreprocessOutput($MaxAccountSize);

	if ($admin_rights)
		echo "<input type=\"text\" name=\"MaxAccountSize\" value=\"$MaxAccountSize\" size=\"8\" checkallownull=\"false\" checkmessage=\"$str_warning\" class=\"req small number\">";
	else
		echo $MaxAccountSize;
?>
            <p><?php EchoTranslation("Max number of accounts")?></p>
<?php
	if ($admin_rights)
		echo "<input type=\"checkbox\" name=\"MaxNumberOfAccountsEnabled\" value=\"1\" $MaxNumberOfAccountsEnabledChecked>";
	else
		echo "<input type=\"checkbox\" name=\"MaxNumberOfAccountsEnabled\" value=\"1\" readonly disabled $MaxNumberOfAccountsEnabledChecked>";

	$str_warning = $obLanguage->String("Max number of accounts");
	$MaxNumberOfAccounts = PreprocessOutput($MaxNumberOfAccounts);

	if ($admin_rights)
		echo "<input type=\"text\" name=\"MaxNumberOfAccounts\" value=\"$MaxNumberOfAccounts\" size=\"8\" checkallownull=\"false\" checkmessage=\"$str_warning\" class=\"req small number\">";
	else
		echo $MaxNumberOfAccounts;
?>
            <p><?php EchoTranslation("Max number of aliases")?></p>
<?php
	if ($admin_rights)
		echo "<input type=\"checkbox\" name=\"MaxNumberOfAliasesEnabled\" value=\"1\" $MaxNumberOfAliasesEnabledChecked>";
	else
		echo "<input type=\"checkbox\" name=\"MaxNumberOfAliasesEnabled\" value=\"1\" readonly disabled $MaxNumberOfAliasesEnabledChecked>";

	$str_warning = $obLanguage->String("Max number of aliases");
	$MaxNumberOfAliases = PreprocessOutput($MaxNumberOfAliases);

	if ($admin_rights)
		echo "<input type=\"text\" name=\"MaxNumberOfAliases\" value=\"$MaxNumberOfAliases\" size=\"8\" checkallownull=\"false\" checkmessage=\"$str_warning\" class=\"req small number\">";
	else
		echo $MaxNumberOfAliases;
?>
            <p><?php EchoTranslation("Max number of distribution lists")?></p>
<?php
	if ($admin_rights)
		echo "<input type=\"checkbox\" name=\"MaxNumberOfDistributionListsEnabled\" value=\"1\" $MaxNumberOfDistributionListsEnabledChecked>";
	else
		echo "<input type=\"checkbox\" name=\"MaxNumberOfDistributionListsEnabled\" value=\"1\" readonly disabled $MaxNumberOfDistributionListsEnabledChecked>";

	$str_warning = $obLanguage->String("Max number of distribution lists");
	$MaxNumberOfDistributionLists = PreprocessOutput($MaxNumberOfDistributionLists);

	if ($admin_rights)
		echo "<input type=\"text\" name=\"MaxNumberOfDistributionLists\" value=\"$MaxNumberOfDistributionLists\" size=\"8\" checkallownull=\"false\" checkmessage=\"$str_warning\" class=\"req small number\">";
	else
		echo $MaxNumberOfDistributionLists;
?>
          </div>
          <h3><a href="#"><?php EchoTranslation("DKIM Signing") ?></a></h3>
          <div class="hidden">
<?php
	PrintCheckboxRow("DKIMSignEnabled", $obLanguage->String("Enabled"), $DKIMSignEnabled);
	PrintPropertyEditRow("DKIMPrivateKeyFile", $obLanguage->String("Private key file"), $DKIMPrivateKeyFile, 50);
	PrintPropertyEditRow("DKIMSelector", $obLanguage->String("Selector"), $DKIMSelector, 20);
?>
            <p>Header method</p>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMHeaderCanonicalizationMethod" value="1" id="1" <?php if ($DKIMHeaderCanonicalizationMethod == 1) echo "checked"?>><label for="1">Simple</label></div>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMHeaderCanonicalizationMethod" value="2" id="2" <?php if ($DKIMHeaderCanonicalizationMethod == 2) echo "checked"?>><label for="2">Relaxed</label></div>
            <p>Body method</p>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMBodyCanonicalizationMethod" value="1" id="3" <?php if ($DKIMBodyCanonicalizationMethod == 1) echo "checked"?>><label for="3">Simple</label></div>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMBodyCanonicalizationMethod" value="2" id="4" <?php if ($DKIMBodyCanonicalizationMethod == 2) echo "checked"?>><label for="4">Relaxed</label></div>
            <p>Signing algorithm</p>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMSigningAlgorithm" value="1" id="5" <?php if ($DKIMSigningAlgorithm == 1) echo "checked"?>><label for="5">SHA1</label></div>
            <div style="position:relative; display:inline-block;"><input type="radio" name="DKIMSigningAlgorithm" value="2" id="6" <?php if ($DKIMSigningAlgorithm == 2) echo "checked"?>><label for="6">SHA256</label></div>
          </div>
          <h3><a href="#"><?php EchoTranslation("Advanced") ?></a></h3>
          <div class="hidden">
<?php PrintPropertyEditRow("domainpostmaster", $obLanguage->String("Catch-all address"), $domainpostmaster, 40); ?>
          </div>
          <h3><a href="#"><?php EchoTranslation("Plus addressing") ?></a></h3>
          <div class="hidden">
<?php
	PrintCheckboxRow("domainplusaddressingenabled", $obLanguage->String("Enabled"), $domainplusaddressingenabled);
?>
            <p><?php EchoTranslation("Character")?></p>
            <select name="domainplusaddressingcharacter" class="small">
              <option value="+" <?php if ($domainplusaddressingcharacter == "+") echo "selected";?> >+</option>
              <option value="-" <?php if ($domainplusaddressingcharacter == "-") echo "selected";?> >-</option>
              <option value="_" <?php if ($domainplusaddressingcharacter == "_") echo "selected";?> >_</option>
              <option value="%" <?php if ($domainplusaddressingcharacter == "%") echo "selected";?> >%</option>
            </select>
          </div>
          <h3><a href="#"><?php EchoTranslation("Greylisting")?></a></h3>
          <div class="hidden">
<?php
	PrintCheckboxRow("domainantispamenablegreylisting", $obLanguage->String("Enabled"), $domainantispamenablegreylistingchecked);
?>
          </div>
<?php
	PrintSaveButton();
?>
      </form>
    </div>