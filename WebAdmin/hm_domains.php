<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$DomainCount = $obBaseApp->Domains->Count();
$str_delete = $obLanguage->String("Remove");
$str_name = $obLanguage->String("Domain name");
$str_maxsizemb = $obLanguage->String("Maximum size (MB)");
?>

    <div class="box large">
      <h2><?php EchoTranslation("Domains") ?> <span>(<?php echo $DomainCount ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:75%;">Name</th>
              <th style="width:10%;">Max size (MB)</th>
              <th style="width:10%;">Enabled</th>
              <th style="width:5%;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 1; $i <= $DomainCount; $i++) {
	$obDomain = $obBaseApp->Domains->Item($i-1);
	$domainname = $obDomain->Name;
	$domainid = $obDomain->ID;
	$domainmaxsize = $obDomain->MaxSize;
	if ($domainmaxsize == 0) $domainmaxsize = "Unlimited"; //added
	$domainactive = $obDomain->Active ? $obLanguage->String("Yes") : $obLanguage->String("No"); //modified
	$domainname = PreprocessOutput($domainname);
	$domainname_escaped = GetStringForJavaScript($domainname);

	echo '            <tr>
              <td><a href="?page=domain&action=edit&domainid=' . $domainid . '">' . $domainname . '</a></td>
              <td>' . $domainmaxsize . '</td>
              <td>' . $domainactive . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $domainname . '</b>:\',\'Yes\',\'?page=background_domain_save&action=delete&domainid=' . $domainid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=domain&action=add" class="button">Add new domain</a></div>
      </div>
    </div>