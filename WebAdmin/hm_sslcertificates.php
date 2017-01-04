<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$SSLCertificates = $obSettings->SSLCertificates;
$Count = $SSLCertificates->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("SSL certificates") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th style="width:95%;"><?php EchoTranslation("Name")?></th>
              <th style="width:5%;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
for ($i = 0; $i < $Count; $i++) {
	$sslCertificate = $SSLCertificates->Item($i);
	$id = $sslCertificate->ID;
	$name = $sslCertificate->Name;
	$name = PreprocessOutput($name);

	echo '            <tr>
              <td><a href=\"?page=sslcertificate&action=edit&id=' . $id . '">' . $name . '</a></td>
             <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $name . '</b>:\',\'Yes\',\'?page=background_sslcertificate_save&action=delete&id=' . $id . '\');" class="delete">Delete</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=sslcertificate&action=add" class="button">Add SSL certificate</a></div>
      </div>
    </div>