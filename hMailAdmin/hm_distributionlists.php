<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid",null);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp();

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obLists = $obDomain->DistributionLists();
$Count = $obLists->Count();
$obDistributionLists = $obDomain->DistributionLists;
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");
?>
    <div class="box large">
      <h2><?php EchoTranslation("Distribution lists") ?> <span>(<?php echo $Count ?>)</span></h2>
      <table class="tablesort">
        <thead>
          <tr>
            <th><?php EchoTranslation("Name")?></th>
            <th style="width:15%;"><?php EchoTranslation("Enabled")?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$obList = $obDistributionLists->Item($i);
		$listaddress = $obList->Address;
		$listid = $obList->ID;

		$listaddress = PreprocessOutput($listaddress);
		$listaddress_escaped = GetStringForJavaScript($listaddress);

		$listactive = $obList->Active ? Translate("Yes") : Translate("No"); //added

		echo '          <tr>
            <td><a href="?page=distributionlist&action=edit&domainid=' . $domainid . '&distributionlistid=' . $listid . '">' . $listaddress . '</a></td>
            <td>' . $listactive . '</td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $listaddress . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_distributionlist_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '&distributionlistid=' . $listid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="3">' . Translate("You haven't added any distribution lists.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=distributionlist&action=add&domainid=<?php echo $domainid?>" class="button"><?php EchoTranslation("Add new distribution list") ?></a></div>
    </div>