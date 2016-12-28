<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // Users are not allowed to show this page.
?>
    <div class="box large">
      <h2><?php EchoTranslation("SSL certificates") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:25%;"><?php EchoTranslation("Name")?></th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$obSettings = $obBaseApp->Settings();
$SSLCertificates = $obSettings->SSLCertificates;
$Count = $SSLCertificates->Count();
$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++) {
	$sslCertificate = $SSLCertificates->Item($i);
	$id = $sslCertificate->ID;
	$name = $sslCertificate->Name;
	$name = PreprocessOutput($name);

	echo '          <tr>
            <td><a href=\"?page=sslcertificate&action=edit&id=' . $id . '">' . $name . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $name . '</b>:\',\'Yes\',\'?page=background_sslcertificate_save&action=delete&id=' . $id . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=sslcertificate&action=add" class="button">Add SSL certificate</a></div>
      </div>
    </div>