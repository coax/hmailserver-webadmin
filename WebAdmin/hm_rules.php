<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$rules = $obBaseApp->Rules();
$Count = $rules->Count();
$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
?>
    <div class="box large">
      <h2><?php EchoTranslation("Rules") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:80%;"><?php EchoTranslation("Name") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Enabled") ?></th>
              <th style="width:5%;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$rule = $rules->Item($i);
	$rulename = $rule->Name;
	$ruleid = $rule->ID;
	$enabled = $rule->Active ? $str_yes : $str_no;
	$rulename = PreprocessOutput($rulename);

	echo '            <tr>
              <td><a href="?page=rule&action=edit&domainid=0&accountid=0&ruleid=' . $ruleid . '">' . $rulename . '</a></td>
              <td>' . $enabled . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $rulename . '</b>:\',\'Yes\',\'?page=background_rule_save&savetype=rule&action=delete&domainid=0&accountid=0&action=delete&ruleid=' . $ruleid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=rule&domainid=0&accountid=0&action=add" class="button">Add new rule</a></div>
      </div>
    </div>