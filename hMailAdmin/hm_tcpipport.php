<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Not server admin

$tcpipportid = hmailGetVar("tcpipportid",0);
$action = hmailGetVar("action","");
$obSettings = $obBaseApp->Settings();
$obTCPIPPOrts = $obSettings->TCPIPPorts;
$protocol = 1;
$portnumber = "";
$ConnectionSecurity = 0;
$SSLCertificateID = 0;
$Address = "";

if ($action == "edit") {
	$obTCPIPPort = $obTCPIPPOrts->ItemByDBID($tcpipportid);
	$portnumber = $obTCPIPPort->PortNumber;
	$protocol = $obTCPIPPort->Protocol;
	$ConnectionSecurity = $obTCPIPPort->ConnectionSecurity;
	$SSLCertificateID = $obTCPIPPort->SSLCertificateID;
	$Address = $obTCPIPPort->Address;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("TCP/IP port") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_tcpipport_save");
PrintHidden("action", "$action");
PrintHidden("tcpipportid", "$tcpipportid");
?>
        <p><?php EchoTranslation("Protocols")?></p>
        <select name="protocol" class="medium">
          <option value="1" <?php if ($protocol == "1") echo "selected";?> ><?php EchoTranslation("SMTP")?></option>
          <option value="3" <?php if ($protocol == "3") echo "selected";?> ><?php EchoTranslation("POP3")?></option>
          <option value="5" <?php if ($protocol == "5") echo "selected";?> ><?php EchoTranslation("IMAP")?></option>
        </select>
<?php
PrintPropertyEditRow("Address", "TCP/IP address", $Address, 15);
PrintPropertyEditRow("portnumber", "TCP/IP port", $portnumber, 10, "number", "small");
?>
        <p><?php EchoTranslation("Connection security")?></p>
        <select name="ConnectionSecurity" class="medium">
          <option value="<?php echo CONNECTION_SECURITY_NONE?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_NONE) echo "selected";?> ><?php EchoTranslation("None")?></option>
          <option value="<?php echo CONNECTION_SECURITY_STARTTLSOPTIONAL?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_STARTTLSOPTIONAL) echo "selected";?> ><?php EchoTranslation("STARTTLS (Optional)")?></option>
          <option value="<?php echo CONNECTION_SECURITY_STARTTLSREQUIRED?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_STARTTLSREQUIRED) echo "selected";?> ><?php EchoTranslation("STARTTLS (Required)")?></option>
          <option value="<?php echo CONNECTION_SECURITY_TLS?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_TLS) echo "selected";?> ><?php EchoTranslation("SSL/TLS")?></option>
        </select>
        <p><?php EchoTranslation("SSL Certificate")?></p>
        <select name="SSLCertificateID">
          <option value="0" <?php if ($SSLCertificateID == 0) echo "selected";?> ><?php EchoTranslation("None")?></a>
<?php
$SSLCertificates = $obSettings->SSLCertificates;

for ($i = 0; $i < $SSLCertificates->Count; $i++) {
	$SSLCertificate = $SSLCertificates[$i];
	$id = $SSLCertificate->ID;
	$name = PreprocessOutput($SSLCertificate->Name);
?>
          <option value="<?php echo $id?>" <?php if ($id == "$SSLCertificateID") echo "selected";?> ><?php echo $name?></option>
<?php
}
?>
        </select>
        <div class="warning">If you change these settings, hMailServer needs to be restarted for the changes to take effect.</div>
<?php
PrintSaveButton();
?>
      </form>
    </div>