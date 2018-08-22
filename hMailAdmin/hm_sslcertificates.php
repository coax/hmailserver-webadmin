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
      <table class="tablesort">
        <thead>
          <tr>
            <th data-sort="string"><?php EchoTranslation("Name")?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");

if ($Count>0) {
	for ($i=0; $i<$Count; $i++) {
		$sslCertificate = $SSLCertificates->Item($i);
		$id = $sslCertificate->ID;
		$name = $sslCertificate->Name;
		$name = PreprocessOutput($name);

		echo '          <tr>
            <td><a href="?page=sslcertificate&action=edit&id=' . $id . '">' . $name . '</a></td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $name . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_sslcertificate_save&csrftoken=' . $csrftoken . '&action=delete&id=' . $id . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="2">' . Translate("You haven't added any SSL certificates.") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=sslcertificate&action=add" class="button"><?php EchoTranslation("Add new SSL certificate") ?></a></div>
    </div>