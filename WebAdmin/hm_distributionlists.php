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
?>
    <div class="box large">
      <h2><?php EchoTranslation("Distribution lists") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:80%;"><?php EchoTranslation("Name")?></th>
              <th style="width:15%;"><?php EchoTranslation("Enabled")?></th>
              <th style="width:5%;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$obList = $obDistributionLists->Item($i);
	$listaddress = $obList->Address;
	$listid = $obList->ID;

	$listaddress = PreprocessOutput($listaddress);
	$listaddress_escaped = GetStringForJavaScript($listaddress);

	$listactive = $obList->Active ? $obLanguage->String("Yes") : $obLanguage->String("No"); //added

	echo '            <tr>
              <td><a href="?page=distributionlist&action=edit&domainid=' . $domainid . '&distributionlistid=' . $listid . '">' . $listaddress . '</a></td>
              <td>' . $listactive . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $listaddress . '</b>:\',\'Yes\',\'?page=background_distributionlist_save&action=delete&domainid=' . $domainid . '&distributionlistid=' . $listid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=distributionlist&action=add&domainid=<?php echo $domainid?>" class="button">Add new list</a></div>
      </div>
    </div>