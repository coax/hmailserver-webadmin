<?php
if (!defined('IN_WEBADMIN'))
	exit();

$domainid  = hmailGetVar("domainid",null);

if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp(); // Users are not allowed to show this page.

if (hmailGetAdminLevel() == 1 && $domainid != hmailGetDomainID())
	hmailHackingAttemp(); // Domain admin but not for this domain.
?>
    <div class="box large">
      <h2><?php EchoTranslation("Aliases") ?>></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:90%;"><?php EchoTranslation("Name")?></th>
            <th style="width:10%;">&nbsp;</th>
          </tr>
<?php
$obDomain = $obBaseApp->Domains->ItemByDBID($domainid);
$obAliases = $obDomain->Aliases();

$Count = $obAliases->Count();

$str_delete = $obLanguage->String("Remove");

$obAliases = $obDomain->Aliases;

for ($i = 0; $i < $Count; $i++) {
	$obAlias = $obAliases->Item($i);
	$aliasname = $obAlias->Name;
	$aliasid = $obAlias->ID;

	$aliasname = PreprocessOutput($aliasname);
	$aliasname_escaped = GetStringForJavaScript($aliasname);

	echo '          <tr>
            <td><a href="?page=alias&action=edit&domainid=' . $domainid . '&aliasid=' . $aliasid . '">' . $aliasname . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $aliasname . '</b>:\',\'Yes\',\'?page=background_alias_save&action=delete&domainid=' . $domainid . '&aliasid=' . $aliasid . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=alias&action=add&domainid=<?php echo $domainid?>" class="button">Add new alias</a></div>
      </div>
    </div>