<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$routeid = hmailGetVar("routeid",0);
$routeaddressid = hmailGetVar("routeaddressid",0);
$action = hmailGetVar("action","");

$routeaddress = "";

if ($action == "edit") {
	$obRoute = $obSettings->Routes->ItemByDBID($routeid);
	$obRouteAddresses = $obRoute->Addresses;
	$obRouteAddress = $obRouteAddresses->ItemByDBID($routeaddressid);
	$routeaddress = $obRouteAddress->Address;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("Address") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_route_address_save");
PrintHidden("action", $action);
PrintHidden("routeid", $routeid);
PrintHidden("routeaddressid", $routeaddressid);

PrintPropertyEditRow("routeaddress", "Address", $routeaddress, 35, "email");

PrintSaveButton();
?>
      </form>
    </div>