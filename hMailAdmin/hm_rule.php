<?php
if (!defined('IN_WEBADMIN'))
	exit();

$action = hmailGetVar("action", "");
$domainid = hmailGetVar("domainid", 0, true);
$accountid = hmailGetVar("accountid", 0, true);
$ruleid = hmailGetVar("ruleid", 0, true);

if (!GetHasRuleAccess($domainid, $accountid))
	hmailHackingAttemp();

include "include/rule_strings.php";

if ($ruleid != 0) {
	if ($domainid != 0) {
		$domain = $obBaseApp->Domains->ItemByDBID($domainid);
		$account = $domain->Accounts->ItemByDBID($accountid);
		$rule = $account->Rules->ItemByDBID($ruleid);
	} else {
		$rule = $obBaseApp->Rules->ItemByDBID($ruleid);
	}

	$Name = $rule->Name;
	$Active = $rule->Active;
	$UseAND = $rule->UseAND;
} else {
	$Name = "";
	$Active = 0;
	$UseAND = 1;
}

$str_delete = $obLanguage->String("Remove");
$str_add = $obLanguage->String("Add");
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Rule") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_rule_save");
PrintHidden("savetype", "rule");
PrintHidden("action", $action);
PrintHidden("domainid", $domainid);
PrintHidden("accountid", $accountid);
PrintHidden("ruleid", $ruleid);

PrintPropertyEditRow("Name", "Name", $Name, 100);
PrintCheckboxRow("Active", "Active", $Active);
?>
        <div style="position:relative; display:inline-block;"><p><input type="radio" name="UseAND" value="1" id="1" <?php if ($UseAND == 1) echo "checked"?>><label for="1"><?php EchoTranslation("Use AND") ?></label></p></div>
        <div style="position:relative; display:inline-block;"><p><input type="radio" name="UseAND" value="0" id="2" <?php if ($UseAND == 0) echo "checked"?>><label for="2"><?php EchoTranslation("Use OR") ?></label></p></div>
<?php
if ($ruleid == 0) {
	echo '<div class="warning">' . $obLanguage->String("You must save the rule before you can edit criteria and actions.") . '</div>' . PHP_EOL;
} else {
	$str_yes = $obLanguage->String("Yes");
	$str_no = $obLanguage->String("No");
	$str_delete = $obLanguage->String("Remove");
	$str_confirm = $obLanguage->String("Confirm delete");
?>
        <h3><a href="#"><?php EchoTranslation("Criteria") ?></a></h3>
        <div class="hidden">
          <table>
            <thead>
              <tr>
                <th><?php EchoTranslation("Field") ?></th>
                <th style="width:30%;"><?php EchoTranslation("Comparison") ?></th>
                <th style="width:30%;"><?php EchoTranslation("Value") ?></th>
                <th style="width:32px;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
	$Criterias = $rule->Criterias;
	$count = $Criterias->Count;
	for ($i = 0; $i < $count; $i++) {
		$criteria = $Criterias->Item($i);
		$criteriaid = $criteria->ID;

		if ($criteria->UsePredefined)
			$fieldName = GetPredefinedFieldString($criteria->PredefinedField);
		else
			$fieldName = $criteria->HeaderField;

		$matchType = GetMatchTypeString($criteria->MatchType);
		$matchValue = $criteria->MatchValue;

		echo '              <tr>
                <td><a href="?page=rule_criteria&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '&criteriaid=' . $criteriaid . '">' . $fieldName . '</a></td>
                <td>' . PreprocessOutput($matchType) . '</td>
                <td>' . PreprocessOutput($matchValue) . '</td>
                <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $fieldName . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_rule_save&csrftoken=' . $csrftoken . '&savetype=criteria&action=delete&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '&criteriaid=' . $criteriaid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
              </tr>' . PHP_EOL;
	}
?>
            </tbody>
          </table>
          <div class="buttons center"><a href="?page=rule_criteria&action=add&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>" class="button"><?php EchoTranslation("Add new criteria") ?></a></div>
        </div>
        <h3><a href="#"><?php EchoTranslation("Actions") ?></a></h3>
        <div class="hidden">
          <table>
            <thead>
              <tr>
                <th><?php EchoTranslation("Action") ?></th>
                <th style="width:10%;"><?php EchoTranslation("Move") ?></th>
                <th style="width:32px;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
	$Actions = $rule->Actions;
	$count = $Actions->Count;

	$str_move_up= $obLanguage->String("Move up");
	$str_move_down = $obLanguage->String("Move down");

	for ($i = 0; $i < $count; $i++) {
		$action = $Actions->Item($i);

		$actionid = $action->ID;
		$actionName = GetRuleActionString($action->Type);

		$move = '';
		if ($i > 0)
			$move = $move . '<a href="?page=background_rule_save&csrftoken=' . $csrftoken . '&action=move&savetype=actionup&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '&actionid=' . $actionid . '" class="arrow up" title="' . $str_move_up . '">' . $str_move_up . '</a>';
		if ($i < $count-1)
			$move = $move . '<a href="?page=background_rule_save&csrftoken=' . $csrftoken . '&action=move&savetype=actiondown&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '&actionid=' . $actionid . '" class="arrow down" title="' . $str_move_down . '">' . $str_move_down . '</a>';

		echo '              <tr>
                <td><a href="?page=rule_action&action=edit&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '&actionid=' . $actionid . '">' . $actionName . '</a></td>
                <td>' . $move . '</td>
                <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $actionName . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_rule_save&csrftoken=' . $csrftoken . '&savetype=action&action=delete&domainid=' . $domainid . '&accountid=' . $accountid . '&ruleid=' . $ruleid . '&actionid=' . $actionid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
              </tr>' . PHP_EOL;
	}
?>
            </tbody>
          </table>
          <div class="buttons center"><a href="?page=rule_action&action=add&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>" class="button"><?php EchoTranslation("Add new action") ?></a></div>
        </div>
<?php
}

PrintSaveButton();
?>
      </form>
    </div>