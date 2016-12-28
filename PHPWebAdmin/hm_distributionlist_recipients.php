<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid",null);
$distributionlistid = hmailGetVar("distributionlistid",0);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Members") ?>></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:95%;">Name</th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obList = $obDomain->DistributionLists->ItemByDBID($distributionlistid);
$obRecipients = $obList->Recipients;

$Count = $obRecipients->Count();

$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++) {
	$obRecipient = $obRecipients->Item($i);

	$recipientaddress = $obRecipient->RecipientAddress;
	$recipientid = $obRecipient->ID;

	$recipientaddress = PreprocessOutput($recipientaddress);

	echo '          <tr>
            <td><a href="?page=distributionlist_recipient&action=edit&domainid=' . $domainid . '&distributionlistid=' . $distributionlistid . '&recipientid=' . $recipientid . '">' . $recipientaddress . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $recipientaddress . '</b>:\',\'Yes\',\'?page=background_distributionlist_recipient_save&action=delete&domainid=' . $domainid . '&distributionlistid=' . $distributionlistid . '&recipientid=' . $recipientid . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=securityrange&action=add" class="button">Add new recipient</a></div>
      </div>
    </div>