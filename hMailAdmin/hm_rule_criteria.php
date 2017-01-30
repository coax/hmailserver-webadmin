<?php
if (!defined('IN_WEBADMIN'))
	exit();

$action = hmailGetVar("action", "");
$domainid = hmailGetVar("domainid", 0, true);
$accountid = hmailGetVar("accountid", 0, true);
$ruleid = hmailGetVar("ruleid", 0);
$criteriaid = hmailGetVar("criteriaid", 0);

if (!GetHasRuleAccess($domainid, $accountid))
	hmailHackingAttemp();

include "include/rule_strings.php";

if ($domainid == 0)
	$rule = $obBaseApp->Rules->ItemByDBID($ruleid);
else
	$rule = $obBaseApp->Domains->ItemByDBID($domainid)->Accounts->ItemByDBID($accountid)->Rules->ItemByDBID($ruleid);

if ($action == "edit") {
	$ruleCriteria = $rule->Criterias->ItemByDBID($criteriaid);

	$UsePredefined = $ruleCriteria->UsePredefined;
	$PredefinedField = $ruleCriteria->PredefinedField;
	$MatchType = $ruleCriteria->MatchType;
	$MatchValue = $ruleCriteria->MatchValue;
	$HeaderField = $ruleCriteria->HeaderField;
} else {
	$UsePredefined = 1;
	$PredefinedField = 0;
	$MatchType = 0;
	$MatchValue = "";
	$HeaderField = "";
}
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Criteria") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
        <table>
          <tbody>
            <tr>
              <td style="width:55%; padding-right:10%;">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_rule_save");
PrintHidden("savetype", "criteria");
PrintHidden("action", $action);
PrintHidden("domainid", $domainid);
PrintHidden("accountid", $accountid);
PrintHidden("ruleid", $ruleid);
PrintHidden("criteriaid", $criteriaid);
?>
                <p><input type="radio" name="UsePredefined" value="1" id="1" <?php if ($UsePredefined == 1) echo "checked"?>><label for="1"><?php EchoTranslation("Predefined field")?></label></p>
                <select name="PredefinedField">
<?php
$eFTFromSelected = $PredefinedField == eFTFrom ? "selected" : "";
$eFTToSelected = $PredefinedField == eFTTo ? "selected" : "";
$eFTCCSelected = $PredefinedField == eFTCC ? "selected" : "";
$eFTSubjectSelected = $PredefinedField == eFTSubject ? "selected" : "";
$eFTBodySelected = $PredefinedField == eFTBody ? "selected" : "";
$eFTMessageSizeSelected = $PredefinedField == eFTMessageSize ? "selected" : "";
$eFTRecipientList = $PredefinedField == eFTRecipientList ? "selected" : "";
$eFTDeliveryAttempts = $PredefinedField == eFTDeliveryAttempts ? "selected" : "";

echo '                  <option value=' . eFTFrom . ' $eFTFromSelected>' . GetPredefinedFieldString(eFTFrom) . '</option>
                  <option value= ' . eFTTo . ' $eFTToSelected>' . GetPredefinedFieldString(eFTTo) . '</option>
                  <option value= ' . eFTCC . ' $eFTCCSelected>' . GetPredefinedFieldString(eFTCC) . '</option>
                  <option value= ' . eFTSubject . ' $eFTSubjectSelected>' . GetPredefinedFieldString(eFTSubject) . '</option>
                  <option value= ' . eFTBody . ' $eFTBodySelected>' . GetPredefinedFieldString(eFTBody) . '</option>
                  <option value= ' . eFTMessageSize . ' $eFTMessageSizeSelected>' . GetPredefinedFieldString(eFTMessageSize) . '</option>
                  <option value= ' . eFTRecipientList . ' $eFTRecipientList>' . GetPredefinedFieldString(eFTRecipientList) . '</option>
                  <option value= ' . eFTDeliveryAttempts . ' $eFTDeliveryAttempts>' . GetPredefinedFieldString(eFTDeliveryAttempts) . '</option>' . PHP_EOL;
?>
                </select>
              </td>
              <td>
                <p><input type="radio" name="UsePredefined" value="0" id="2" <?php if ($UsePredefined == 0) echo "checked"?>><label for="2"><?php EchoTranslation("Custom header field")?></label></p>
                <input type="text" name="HeaderField" value="<?php echo PreprocessOutput($HeaderField);?>">
              </td>
            </tr>
          </tbody>
        </table>
        <table>
          <tbody>
            <tr>
              <td style="width:55%; padding-right:10%;">
                <p><?php EchoTranslation("Search type")?></p>
                <select name="MatchType">
<?php
$eMTEqualsSelected = $MatchType == eMTEquals ? "selected" : "";
$eMTContainsSelected = $MatchType == eMTContains ? "selected" : "";
$eMTLessThanSelected = $MatchType == eMTLessThan ? "selected" : "";
$eMTGreaterThanSelected = $MatchType == eMTGreaterThan ? "selected" : "";
$eMTRegExMatchSelected = $MatchType == eMTRegExMatch ? "selected" : "";
$eMTNotContainsSelected = $MatchType == eMTNotContains ? "selected" : "";
$eMTNotEqualsSelected = $MatchType == eMTNotEquals ? "selected" : "";
$eMTWildcardSelected = $MatchType == eMTWildcard ? "selected" : "";

echo '                  <option value= ' . eMTEquals . ' $eMTEqualsSelected>' . GetMatchTypeString(eMTEquals) . '</option>
                  <option value= ' . eMTContains . ' $eMTContainsSelected>' . GetMatchTypeString(eMTContains) . '</option>
                  <option value= ' . eMTLessThan . ' $eMTLessThanSelected>' . GetMatchTypeString(eMTLessThan) . '</option>
                  <option value= ' . eMTGreaterThan . ' $eMTGreaterThanSelected>' . GetMatchTypeString(eMTGreaterThan) . '</option>
                  <option value= ' . eMTRegExMatch . ' $eMTRegExMatchSelected>' . GetMatchTypeString(eMTRegExMatch) . '</option>
                  <option value= ' . eMTNotContains . ' $eMTNotContainsSelected>' . GetMatchTypeString(eMTNotContains) . '</option>
                  <option value= ' . eMTNotEquals . ' $eMTNotEqualsSelected>' . GetMatchTypeString(eMTNotEquals) . '</option>
                  <option value= ' . eMTWildcard . ' $eMTWildcardSelected>' . GetMatchTypeString(eMTWildcard) . '</option>' . PHP_EOL;
?>
                </select>
              </td>
              <td>
                <p><?php EchoTranslation("Value")?></p>
                <input type="text" name="MatchValue" value="<?php echo PreprocessOutput($MatchValue);?>">
              </td>
            </tr>
          </tbody>
        </table>
<?php
PrintSaveButton();
?>
      </form>
    </div>