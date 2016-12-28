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
      <h2><?php EchoTranslation("Distribution lists") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:95%;">Name</th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obLists = $obDomain->DistributionLists();

$Count = $obLists->Count();

$str_delete = $obLanguage->String("Remove");

$obDistributionLists = $obDomain->DistributionLists;

for ($i = 0; $i < $Count; $i++) {
	$obList = $obDistributionLists->Item($i);
	$listaddress = $obList->Address;
	$listid = $obList->ID;

	$listaddress = PreprocessOutput($listaddress);
	$listaddress_escaped = GetStringForJavaScript($listaddress);

	echo '          <tr>
            <td><a href="?page=distributionlist&action=edit&domainid=' . $domainid . '&distributionlistid=' . $listid . '">' . $listaddress . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $listaddress . '</b>:\',\'Yes\',\'?page=background_distributionlist_save&action=delete&domainid=' . $domainid . '&distributionlistid=' . $listid . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=distributionlist&action=add&domainid=<?php echo $domainid?>" class="button">Add new list</a></div>
      </div>
    </div>