<?php
if (!defined('IN_WEBADMIN'))
	exit();

$DomainId = hmailGetVar("domainid", null, true);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp(); // Users are not allowed to show this page.

if (hmailGetAdminLevel() == 1 && $DomainId != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($DomainId);
$obAccounts = $obDomain->Accounts();
$Count = $obAccounts->Count();
$currentaccountid = hmailGetAccountID();
?>
    <div class="box large">
      <h2><?php EchoTranslation("Accounts") ?> <span>(<?php echo $Count ?>)</span></h2>
      <table class="tablesort">
        <thead>
          <tr>
            <th data-sort="string"><?php EchoTranslation("Address") ?></th>
            <th style="width:12%;" data-sort="float"><?php EchoTranslation("Size (MB)") ?></th>
            <th style="width:12%;" data-sort="float"><?php EchoTranslation("Max. (MB)") ?></th>
            <th style="width:15%;" class="no-sort">&nbsp;</th>
            <th style="width:8%;" data-sort="string"><?php EchoTranslation("Enabled") ?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
$obAccounts = $obDomain->Accounts;

$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");
$str_unlimited = Translate("Unlimited");

if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$obAccount = $obAccounts->Item($i);
		$AccountAddress = $obAccount->Address;
		$AccountId = $obAccount->ID;
		$AccountMaxSize = $obAccount->MaxSize();
		$AccountAddress = PreprocessOutput($AccountAddress);
		$AccountAddress_Escaped = GetStringForJavaScript($AccountAddress);
		$AccountEnabled = $obAccount->Active ? $str_yes : $str_no;
		$AccountInactive = $obAccount->Active ? $AccountInactive = '' : $AccountInactive = ' class="red"';
		$AccountSize = $obAccount->Size();

		//Calculate account size
		$Color = '#b1d786';
		$Color2 = '#86a365';
		$Width = '1';
		if ($AccountMaxSize>0) {
			$Filled = Round((($AccountMaxSize - $AccountSize) / ($AccountMaxSize)) * 100);
			$Width = floor(($AccountSize / $AccountMaxSize) * 100);
			if (($Filled<=10) || ($Width>=90)) {
				$Color = $Color2 = '#f77673';
			} elseif (($Filled<=30) || ($Width>=70)) {
				$Color = '#ffb565';
				$Color2 = '#ff9c33';
			} elseif ($Width<1) $Width = 1;
		} else $AccountMaxSize = $str_unlimited;

		echo '          <tr>
            <td><a href="?page=account&action=edit&domainid=' . $DomainId . '&accountid=' . $AccountId . '"' . $AccountInactive . '>' . $AccountAddress . '</a></td>
            <td style="color:' . $Color2 . ';">' . number_format($AccountSize, 2, ".", "") . '</td>
            <td>' . $AccountMaxSize . '</td>
            <td><div class="bar"><div style="width:' . $Width . '%; background:' . $Color . ';"></div></div></td>
            <td>' . $AccountEnabled . '</td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $AccountAddress_Escaped . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_account_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $DomainId . '&accountid=' . $AccountId . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="6">' . Translate("You haven't added any accounts.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=account&action=add&domainid=<?php echo $DomainId?>" class="button"><?php EchoTranslation("Add new account") ?></a></div>
    </div>