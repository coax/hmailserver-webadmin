<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$action = hmailGetVar("action","");

if($action == "save") {
	$obSettings->VerifyRemoteSslCertificate= hmailGetVar("VerifyRemoteSslCertificate",0);
	$obSettings->SslCipherList = hmailGetVar("SslCipherList", "");
	$obSettings->TlsVersion10Enabled = hmailGetVar("TlsVersion10Enabled", 0);
	$obSettings->TlsVersion11Enabled = hmailGetVar("TlsVersion11Enabled", 0);
	$obSettings->TlsVersion12Enabled = hmailGetVar("TlsVersion12Enabled", 0);
	$obSettings->TlsVersion13Enabled = hmailGetVar("TlsVersion13Enabled", 0);
}

$VerifyRemoteSslCertificate = $obSettings->VerifyRemoteSslCertificate;
$SslCipherList = $obSettings->SslCipherList;
$TlsVersion10Enabled = $obSettings->TlsVersion10Enabled;
$TlsVersion11Enabled = $obSettings->TlsVersion11Enabled;
$TlsVersion12Enabled = $obSettings->TlsVersion12Enabled;
$TlsVersion13Enabled = $obSettings->TlsVersion13Enabled;
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Security") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "ssltls");
PrintHidden("action", "save");

PrintPropertyAreaRow("SslCipherList", "SSL/TLS ciphers", $SslCipherList, 12, 80);

PrintCheckboxRow("VerifyRemoteSslCertificate", "Verify remote server SSL/TLS certificates", $VerifyRemoteSslCertificate);
?>
        <h3><?php EchoTranslation("Versions") ?></h3>
<?php
PrintCheckboxRow("TlsVersion10Enabled", "TLS v1.0", $TlsVersion10Enabled);
PrintCheckboxRow("TlsVersion11Enabled", "TLS v1.1", $TlsVersion11Enabled);
PrintCheckboxRow("TlsVersion12Enabled", "TLS v1.2", $TlsVersion12Enabled);
PrintCheckboxRow("TlsVersion13Enabled", "TLS v1.3", $TlsVersion13Enabled);

PrintSaveButton();
?>
      </form>
    </div>