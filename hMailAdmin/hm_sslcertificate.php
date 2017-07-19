<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // The user is not server administrator

$id = hmailGetVar("id",0);
$action = hmailGetVar("action","");
$Name = "";
$CertificateFile = "";
$PrivateKeyFile = "";

if ($action == "edit") {
	$sslCertificate = $obBaseApp->Settings->SSLCertificates->ItemByDBID($id);
	$Name = $sslCertificate->Name;
	$CertificateFile = $sslCertificate->CertificateFile;
	$PrivateKeyFile = $sslCertificate->PrivateKeyFile;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("SSL Certificate") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_sslcertificate_save");
PrintHidden("action", "$action");
PrintHidden("id", "$id");

PrintPropertyEditRow("Name", "Name", $Name);
PrintPropertyEditRow("CertificateFile", "Certificate file", $CertificateFile);
PrintPropertyEditRow("PrivateKeyFile", "Private key file", $PrivateKeyFile);

PrintSaveButton();
?>
      </form>
    </div>