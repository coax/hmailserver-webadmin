<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Only server admins can change this.

$ID = hmailGetVar("ID",0);
$action = hmailGetVar("action","");

$obGreyListingWhiteAddresses	= $obBaseApp->Settings()->AntiSpam()->GreyListingWhiteAddresses;

if ($action == "edit") {
	$obAddress = $obGreyListingWhiteAddresses->ItemByDBID($ID);
	$IPAddress = $obAddress->IPAddress;
	$Description = $obAddress->Description;
} else {
	$IPAddress = "";
	$Description = "";
}
?>
    <div class="box">
      <h2><?php EchoTranslation("Greylisting White listing") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_greylistingwhiteaddress_save");
PrintHidden("action", "$action");
PrintHidden("ID", "$ID");

PrintPropertyEditRow("Description", "Description", $Description, 255, " ");
PrintPropertyEditRow("IPAddress", "IP address", $IPAddress, 20, " ");

PrintSaveButton(null, null, '?page=greylistingwhiteaddresses');
?>
      </form>
    </div>