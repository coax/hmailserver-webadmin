<?php
if (!defined('IN_WEBADMIN'))
	exit();

$action = hmailGetVar("action", "");
$domainid = hmailGetVar("domainid", 0);
$accountid = hmailGetVar("accountid", 0);
$ruleid = hmailGetVar("ruleid", 0);

// check permissions
if (!GetHasRuleAccess($domainid, $accountid))
	hmailHackingAttemp(); // The user has no rule editing permissions.

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
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
PrintHidden("page", "background_rule_save");
PrintHidden("savetype", "rule");
PrintHidden("action", $action);
PrintHidden("domainid", $domainid);
PrintHidden("accountid", $accountid);
PrintHidden("ruleid", $ruleid);

PrintPropertyEditRow("Name", "Name", $Name);
PrintCheckboxRow("Active", "Active", $Active);
PrintCheckboxRow("UseAND", "Use a AND", $UseAND);

if ($ruleid == 0) {
	echo '<div class="warning">You must save the rule before you can edit criteria and actions.</div>' . PHP_EOL;
} else {
?>
        <h3><a href="#"><?php EchoTranslation("Criteria") ?></a></h3>
        <div class="hidden">
          <table>
            <thead>
              <tr>
                <th style="width:30%;"><?php EchoTranslation("Field")?></th>
                <th style="width:30%;"><?php EchoTranslation("Comparison")?></th>
                <th style="width:30%;"><?php EchoTranslation("Value")?></th>
                <th style="width:10%;">&nbsp;</th>
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
?>
              <tr>
                <td><a href="?page=rule_criteria&action=edit&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>&criteriaid=<?php echo $criteriaid ?>"><?php echo $fieldName ?></a></td>
                <td><?php echo PreprocessOutput($matchType)?></td>
                <td><?php echo PreprocessOutput($matchValue)?></td>
                <td><a href="#" onclick="return Confirm('Confirm delete <b><?php echo $fieldName ?></b>:','Yes','?page=background_rule_save&savetype=criteria&action=delete&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>&criteriaid=<?php echo $criteriaid ?>');" class="delete">Delete</a></td>
              </tr>
<?php
	}
?>
            </tbody>
          </table>
          <div class="buttons center"><a href="?page=rule_criteria&action=add&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>" class="button">Add new criteria</a></div>
        </div>
        <h3><a href="#"><?php EchoTranslation("Actions")?></a></h3>
        <div class="hidden">
          <table>
            <thead>
              <tr>
                <th style="width:70%;"><?php EchoTranslation("Action")?></th>
		<th style="width:20%;">Move action</th>
                <th style="width:10%;">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
<?php
	$Actions = $rule->Actions;
	$count = $Actions->Count;

	for ($i = 0; $i < $count; $i++) {
		$action = $Actions->Item($i);

		$actionid = $action->ID;
		$actionName = GetRuleActionString($action->Type);
?>
              <tr>
                <td><?php echo "<a href=\"?page=rule_action&action=edit&domainid=$domainid&accountid=$accountid&ruleid=$ruleid&actionid=$actionid\">$actionName</a>";?></td>
		<td>
		<?php if($i>0)echo "<a href=\"?page=background_rule_save&action=move&savetype=actionup&domainid=$domainid&accountid=$accountid&ruleid=$ruleid&actionid=$actionid\">Up</a>";
		if($i < $count-1)echo "<a href=\"?page=background_rule_save&action=move&savetype=actiondown&domainid=$domainid&accountid=$accountid&ruleid=$ruleid&actionid=$actionid\">Down</a>";?>	
		</td>
                <td><a href="#" onclick="return Confirm('Confirm delete <b><?php echo $actionName ?></b>:','Yes','?page=background_rule_save&savetype=action&action=delete&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>&actionid=<?php echo $actionid ?>');" class="delete">Delete</a></td>
              </tr>
<?php
	}
?>
            </tbody>
          </table>
          <div class="buttons center"><a href="?page=rule_action&action=add&domainid=<?php echo $domainid ?>&accountid=<?php echo $accountid ?>&ruleid=<?php echo $ruleid ?>" class="button">Add new action</a></div>
        </div>
<?php
}

PrintSaveButton();
?>
      </form>
    </div>
