<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", 0, true);
$accountid = hmailGetVar("accountid", 0, true);
$action = hmailGetVar("action","");

if (hmailGetAdminLevel() == 0 && ($accountid != hmailGetAccountID() || $domainid != hmailGetDomainID()))
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obAccount = $obDomain->Accounts->ItemByDBID($accountid);
$obFetchAccounts = $obAccount->FetchAccounts();
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");

$action = hmailGetVar("action","");
?>
    <div class="box large">
      <h2><?php EchoTranslation("External accounts") ?></h2>
      <table>
        <thead>
          <tr>
            <th style="width:40%;"><?php EchoTranslation("Name")?></th>
            <th style="width:40%;"><?php EchoTranslation("Server address")?></th>
            <th style="width:10%;">&nbsp;</th>
            <th style="width:10%;">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
$Count = $obFetchAccounts->Count();

$str_delete = Translate("Remove");
$str_downloadnow = Translate("Download now");

if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$obFetchAccount = $obFetchAccounts->Item($i);

		$FAID = $obFetchAccount->ID;
		$Name = PreprocessOutput($obFetchAccount->Name);
		$ServerAddress = PreprocessOutput($obFetchAccount->ServerAddress);

		echo '          <tr>
            <td><a href="?page=account_externalaccount&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '&faid=' . $FAID . '">' . $Name . '</a></td>
            <td><a href="?page=account_externalaccount&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '&faid=' . $FAID . '">' . $ServerAddress . '</a></td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $Name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_account_externalaccount_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '&accountid=' . $accountid . '&faid=' . $FAID . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            <td><a href="?page=background_account_externalaccount_save&csrftoken=' . $csrftoken . 'action=downloadnow&domainid=' . $domainid . '&accountid=' . $accountid . '&faid=' . $FAID . '">' . $str_downloadnow . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="4">' . Translate("You haven't added any external accounts.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=account_externalaccount&action=add&domainid=<?php echo $domainid?>&accountid=<?php echo $accountid?>" class="button"><?php EchoTranslation("Add new external account") ?></a></div>
    </div>