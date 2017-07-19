<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", null, true);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp(); // Users are not allowed to show this page.

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obAccounts = $obDomain->Accounts();
$Count = $obAccounts->Count();
$currentaccountid = hmailGetAccountID();
?>
    <div class="box large">
      <h2><?php EchoTranslation("Accounts") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Address") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Size (MB)") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Max. size (MB)") ?></th>
              <th style="width:10%;"><?php EchoTranslation("Enabled") ?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
$obAccounts = $obDomain->Accounts;

$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");
$str_unlimited = $obLanguage->String("Unlimited");

for ($i = 0; $i < $Count; $i++) {
	$obAccount = $obAccounts->Item($i);
	$accountaddress = $obAccount->Address;
	$accountid = $obAccount->ID;
	$accountmaxsize = $obAccount->MaxSize();

	$accountaddress = PreprocessOutput($accountaddress);
	$accountaddress_escaped = GetStringForJavaScript($accountaddress);

	//added
	$AccountEnabled = $obAccount->Active ? $str_yes : $str_no;
	$AccountSize = $obAccount->Size();
	$Color = "green";
	if($accountmaxsize > 0){
		$Percentage = Round((($accountmaxsize - $AccountSize) / ($accountmaxsize)) * 100);
		if ($Percentage<=10) $Color = "red";
		elseif ($Percentage<=30) $Color = "yellow";
	}else $accountmaxsize = $str_unlimited;

	echo '            <tr>
              <td><a href="?page=account&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '">' . $accountaddress . '</a></td>
              <td class=' . $Color . '>' . number_format($AccountSize, 2, ".", "") . '</td>
              <td>' . $accountmaxsize . '</td>
              <td>' . $AccountEnabled . '</td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $accountaddress . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_account_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '&accountid=' . $accountid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=account&action=add&domainid=<?php echo $domainid?>" class="button"><?php EchoTranslation("Add new account") ?></a></div>
      </div>
    </div>