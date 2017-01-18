<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Users are not allowed to show this page.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Addresses") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <thead>
            <tr>
              <th><?php EchoTranslation("Name") ?></th>
              <th style="width:32px;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
$routeid = hmailGetVar("routeid",0);

$obRoutes = $obSettings->Routes();
$obRoute = $obRoutes->ItemByDBID($routeid);
$obAddresses = $obRoute->Addresses();

$Count = $obAddresses->Count();

$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++)
	{
	$obAddress = $obAddresses->Item($i);
	$routeaddress = $obAddress->Address;
	$routeaddressid = $obAddress->ID;

   	echo '            <tr>
              <td><a href="?page=route_address&action=edit&routeid=' . $routeid . '&routeaddressid=' . $routeaddressid . '">' . $routeaddress . '</a></td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $routeaddress . '</b>:\',\'Yes\',\'?page=background_route_address_save&action=delete&routeid=' . $routeid . '&routeaddressid=' . $routeaddressid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=route_address&action=add&routeid=<?php echo $routeid?>" class="button"><?php EchoTranslation("Add new address") ?></a></div>
      </div>
    </div>