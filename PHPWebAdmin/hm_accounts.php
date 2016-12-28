<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid",null);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp(); // Users are not allowed to show this page.

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Accounts") ?>></h2>
      <div style="margin:0 18px 18px;">
<?php
$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obAccounts = $obDomain->Accounts();

$Count = $obAccounts->Count();

$str_delete = $obLanguage->String("Remove");

$currentaccountid = hmailGetAccountID();

$str_accountaddress = $obLanguage->String("Address");
$str_maxsizemb = $obLanguage->String("Maximum size (MB)");
?>
        <table>
          <tr>
            <th style="width:80%;"><?php $str_accountaddress ?></th>
            <th style="width:10%;"><?php $str_maxsizemb ?></th>
            <th style="width:10%;">&nbsp;</th>
          </tr>
<?php
$obAccounts = $obDomain->Accounts;

for ($i = 0; $i < $Count; $i++) {
	$obAccount = $obAccounts->Item($i);
	$accountaddress = $obAccount->Address;
	$accountid = $obAccount->ID;
	$accountmaxsize = $obAccount->MaxSize();

	$accountaddress = PreprocessOutput($accountaddress);
	$accountaddress_escaped = GetStringForJavaScript($accountaddress);

	echo '          <tr>
            <td><a href="?page=account&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '">' . $accountaddress . '</a></td>
            <td>' . $accountmaxsize . '</td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $accountaddress . '</b>:\',\'Yes\',\'?page=background_account_save&action=delete&domainid=' . $domainid . '&accountid=' . $accountid . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=account&action=add&domainid=<?php echo $domainid?>" class="button">Add new account</a></div>
      </div>
    </div>