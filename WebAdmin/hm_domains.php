<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$DomainCount = $obBaseApp->Domains->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("Domains") ?> <span>(<?php echo $DomainCount ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Domain name") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Size (MB)") ?></th>
              <th style="width:15%;"><?php EchoTranslation("Max. size (MB)") ?></th>
              <th style="width:10%;"><?php EchoTranslation("Enabled") ?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 1; $i <= $DomainCount; $i++) {
	$obDomain = $obBaseApp->Domains->Item($i-1);
	$domainname = $obDomain->Name;
	$domainid = $obDomain->ID;
	$domainmaxsize = $obDomain->MaxSize;
	$domainactive = $obDomain->Active ? $obLanguage->String("Yes") : $obLanguage->String("No"); //modified
	$domainname = PreprocessOutput($domainname);
	$domainname_escaped = GetStringForJavaScript($domainname);

	//calculate domain size
	$DomainSize = 0;
	$obAccounts = $obDomain->Accounts();
	$Count = $obAccounts->Count();
	for ($j = 0; $j < $Count; $j++) {
		$obAccount = $obAccounts->Item($j);
		$DomainSize += $obAccount->Size();
	}
	$Color = "green";
	if($domainmaxsize != 0){
		$Percentage = Round((($domainmaxsize - $DomainSize) / ($domainmaxsize)) * 100);
		if ($Percentage<=10) $Color = "red";
		elseif ($Percentage<=30) $Color = "yellow";
	}else $domainmaxsize = "Unlimited";

	echo '            <tr>
              <td><a href="?page=domain&action=edit&domainid=' . $domainid . '">' . $domainname . '</a></td>
              <td class=' . $Color . '>' . number_format($DomainSize, 2, ".", "") . '</td>
              <td>' . $domainmaxsize . '</td>
              <td>' . $domainactive . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $domainname . '</b>:\',\'Yes\',\'?page=background_domain_save&action=delete&domainid=' . $domainid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=domain&action=add" class="button"><?php EchoTranslation("Add new domain") ?></a></div>
      </div>
    </div>