<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$Count = $obBaseApp->Domains->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("Domains") ?> <span>(<?php echo $Count ?>)</span></h2>
      <table class="tablesort">
        <thead>
          <tr>
            <th><?php EchoTranslation("Domain name") ?></th>
            <th style="width:12%;"><?php EchoTranslation("Size (MB)") ?></th>
            <th style="width:12%;"><?php EchoTranslation("Max. (MB)") ?></th>
            <th style="width:15%;" class="no-sort">&nbsp;</th>
            <th style="width:8%;"><?php EchoTranslation("Enabled") ?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");
$str_unlimited = Translate("Unlimited");

if ($Count>0) {
	for ($i=1; $i<=$Count; $i++) {
		$obDomain = $obBaseApp->Domains->Item($i-1);
		$DomainName = $obDomain->Name;
		$DomainId = $obDomain->ID;
		$DomainMaxSize = $obDomain->MaxSize;
		$DomainActive = $obDomain->Active ? $str_yes : $str_no;
		$DomainInactive = $obDomain->Active ? $DomainInactive = '' : $DomainInactive = ' class="red"';
		$DomainName = PreprocessOutput($DomainName);
		$DomainName_Escaped = GetStringForJavaScript($DomainName);

		//Calculate domain size
		$DomainSize = 0;
		$obAccounts = $obDomain->Accounts();
		$Accounts = $obAccounts->Count();
		for ($j=0; $j<$Accounts; $j++) {
			$obAccount = $obAccounts->Item($j);
			$DomainSize += $obAccount->Size();
		}
		$Color = '#b1d786';
		$Color2 = '#86a365';
		$Width = '1';
		if ($DomainMaxSize>0) {
			$Filled = Round((($DomainMaxSize - $DomainSize) / ($DomainMaxSize)) * 100);
			$Width = floor(($DomainSize / $DomainMaxSize) * 100);
			if (($Filled<=10) || ($Width>=90)) {
				$Color = $Color2 = '#f77673';
			} elseif (($Filled<=30) || ($Width>=70)) {
				$Color = '#ffb565';
				$Color2 = '#ff9c33';
			} elseif ($Width<1) $Width = 1;
		} else $DomainMaxSize = $str_unlimited;

		echo '          <tr>
            <td><a href="?page=domain&action=edit&domainid=' . $DomainId . '"' . $DomainInactive . '>' . $DomainName . '</a></td>
            <td style="color:' . $Color2 . ';">' . number_format($DomainSize, 2, ".", "") . '</td>
            <td>' . $DomainMaxSize . '</td>
            <td><div class="bar"><div style="width:' . $Width . '%; background:' . $Color . ';"></div></div></td>
            <td>' . $DomainActive . '</td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $DomainName_Escaped . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_domain_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $DomainId . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="6">' . Translate("You haven't added any domains.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=domain&action=add" class="button"><?php EchoTranslation("Add new domain") ?></a></div>
    </div>