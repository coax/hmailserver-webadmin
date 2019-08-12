<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Users are not allowed to show this page.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Routes") ?></h2>
      <table>
        <thead>
          <tr>
            <th><?php EchoTranslation("Name") ?></th>
            <th style="width:32px;">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
$obRoutes = $obSettings->Routes();

$Count = $obRoutes->Count();

$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");

if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$obRoute = $obRoutes->Item($i);
		$routename = $obRoute->DomainName;
		$routeid = $obRoute->ID;

		$routename = PreprocessOutput($routename);

	   	echo '          <tr>
            <td><a href="?page=route&action=edit&routeid=' . $routeid . '">' . $routename . '</a></td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $routename . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_route_save&csrftoken=' . $csrftoken . '&action=delete&routeid=' . $routeid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="2">' . Translate("You haven't added any routes.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=route&action=add" class="button"><?php EchoTranslation("Add new route") ?></a></div>
    </div>