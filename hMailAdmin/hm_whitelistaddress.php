<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Only server admins can change this.

$ID = hmailGetVar("ID",0);
$action = hmailGetVar("action","");

$obWhiteListAddresses	= $obBaseApp->Settings()->AntiSpam()->WhiteListAddresses;

if ($action == "edit") {
	$obAddress = $obWhiteListAddresses->ItemByDBID($ID);
	$LowerIPAddress = $obAddress->LowerIPAddress;
	$UpperIPAddress = $obAddress->UpperIPAddress;
	$EmailAddress = $obAddress->EmailAddress;
	$Description = $obAddress->Description;
} else {
	$LowerIPAddress = "";
	$UpperIPAddress = "";
	$EmailAddress = "";
	$Description = "";
}
?>
    <div class="box">
      <h2><?php EchoTranslation("White listing") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_whitelistaddress_save");
PrintHidden("action", "$action");
PrintHidden("ID", "$ID");

PrintPropertyEditRow("Description", "Description", $Description, 255, " ");
PrintPropertyEditRow("LowerIPAddress", "Lower IP", $LowerIPAddress, 20, " ");
PrintPropertyEditRow("UpperIPAddress", "Upper IP", $UpperIPAddress, 20, " ");
PrintPropertyEditRow("EmailAddress", "E-mail address", $EmailAddress);

PrintSaveButton(null, null, '?page=whitelistaddresses');
?>
      </form>
    </div>