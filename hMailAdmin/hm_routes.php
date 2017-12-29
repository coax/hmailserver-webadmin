<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Users are not allowed to show this page.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Routes") ?></h2>
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
$obRoutes = $obSettings->Routes();

$Count = $obRoutes->Count();

$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");

for ($i = 0; $i < $Count; $i++) {
	$obRoute = $obRoutes->Item($i);
	$routename = $obRoute->DomainName;
	$routeid = $obRoute->ID;

	$routename = PreprocessOutput($routename);

   	echo '            <tr>
              <td><a href="?page=route&action=edit&routeid=' . $routeid . '">' . $routename . '</a></td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $routename . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_route_save&csrftoken=' . $csrftoken . '&action=delete&routeid=' . $routeid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=route&action=add" class="button"><?php EchoTranslation("Add new route") ?></a></div>
      </div>
    </div>