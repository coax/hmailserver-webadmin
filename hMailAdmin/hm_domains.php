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
$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");
$str_unlimited = $obLanguage->String("Unlimited");

for ($i = 1; $i <= $DomainCount; $i++) {
	$obDomain = $obBaseApp->Domains->Item($i-1);
	$domainname = $obDomain->Name;
	$domainid = $obDomain->ID;
	$domainmaxsize = $obDomain->MaxSize;
	$domainactive = $obDomain->Active ? $str_yes : $str_no; //modified
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
	}else $domainmaxsize = $str_unlimited;

	echo '            <tr>
              <td><a href="?page=domain&action=edit&domainid=' . $domainid . '">' . $domainname . '</a></td>
              <td class=' . $Color . '>' . number_format($DomainSize, 2, ".", "") . '</td>
              <td>' . $domainmaxsize . '</td>
              <td>' . $domainactive . '</td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $domainname . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_domain_save&csrftoken=' . $csrftoken . '&action=delete&domainid=' . $domainid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=domain&action=add" class="button"><?php EchoTranslation("Add new domain") ?></a></div>
      </div>
    </div>