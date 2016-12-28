<?php
if (!defined('IN_WEBADMIN'))
	exit();

$distributionlistid = hmailGetVar("distributionlistid",0);
$recipientid = hmailGetVar("recipientid",0);
$domainid = hmailGetVar("domainid",0);
$action = hmailGetVar("action","");

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.

$recipientaddress = "";

if ($action == "edit") {
   $obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
   $obList = $obDomain->DistributionLists->ItemByDBID($distributionlistid);
   $obRecipient = $obList->Recipients->ItemByDBID($recipientid);

   $recipientaddress = $obRecipient->RecipientAddress;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("Address") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "background_distributionlist_recipient_save");
	PrintHidden("action", $action);
	PrintHidden("distributionlistid", $distributionlistid);
	PrintHidden("domainid", $domainid);
	PrintHidden("recipientid", $recipientid);

	PrintPropertyEditRow("recipientaddress", "Address", $recipientaddress, 30, "email");

	PrintSaveButton();
?>
      </form>
    </div>