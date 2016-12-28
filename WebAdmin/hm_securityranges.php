<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // Users are not allowed to show this page.

$obSettings = $obBaseApp->Settings();
$obSecurityRanges = $obSettings->SecurityRanges();
$Count = $obSecurityRanges->Count();
$str_delete = $obLanguage->String("Remove");
?>
    <div class="box large">
      <h2><?php EchoTranslation("IP Ranges") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:65%;">Name</th>
              <th style="width:10%;">Priority</th>
              <th style="width:20%;">Expires (min)</th>
              <th style="width:5%;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$obSecurityRange = $obSecurityRanges->Item($i);
	$securityrangename = $obSecurityRange->Name;
	$securityrangeid = $obSecurityRange->ID;

	$securityrangename = PreprocessOutput($securityrangename);
	$securityrangepriority = $obSecurityRange->Priority; //added
	$ExpiresTime = $obSecurityRange->ExpiresTime; //added

	echo '            <tr>
              <td><a href="?page=securityrange&action=edit&securityrangeid=' . $securityrangeid . '">' . $securityrangename . '</a></td>
              <td>' . $securityrangepriority . '</td>
              <td>' . $ExpiresTime . '</td>
              <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $securityrangename . '</b>:\',\'Yes\',\'?page=background_securityrange_save&action=delete&securityrangeid=' . $securityrangeid . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=securityrange&action=add" class="button">Add new range</a></div>
      </div>
    </div>