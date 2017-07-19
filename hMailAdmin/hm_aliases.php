<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid = hmailGetVar("domainid", null, true);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp();

$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obAliases = $obDomain->Aliases();
$Count = $obAliases->Count();
$obAliases = $obDomain->Aliases;
?>
    <div class="box large">
      <h2><?php EchoTranslation("Aliases") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Name")?></th>
              <th style="width:15%;"><?php EchoTranslation("Enabled")?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");

for ($i = 0; $i < $Count; $i++) {
	$obAlias = $obAliases->Item($i);
	$aliasname = $obAlias->Name;
	$aliasid = $obAlias->ID;

	$aliasname = PreprocessOutput($aliasname);
	$aliasname_escaped = GetStringForJavaScript($aliasname);

	$aliasactive = $obAlias->Active ? $str_yes : $str_no; //added

	echo '            <tr>
              <td><a href="?page=alias&action=edit&domainid=' . $domainid . '&aliasid=' . $aliasid . '">' . $aliasname . '</a></td>
              <td>' . $aliasactive . '</td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $aliasname . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_alias_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '&aliasid=' . $aliasid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=alias&action=add&domainid=<?php echo $domainid?>" class="button"><?php EchoTranslation("Add new alias") ?></a></div>
      </div>
    </div>