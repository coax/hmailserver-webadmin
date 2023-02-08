<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();

$action = hmailGetVar("action","");

if($action == "save") {
	// General
	$obSettings->MaxSMTPConnections = hmailGetVar("maxsmtpconnections",0);
	$obSettings->WelcomeSMTP = hmailGetVar("welcomesmtp",0);

	// Delivery of email
	$obSettings->SMTPNoOfTries = hmailGetVar("smtpnooftries",0);
	$obSettings->SMTPMinutesBetweenTry = hmailGetVar("smtpminutesbetweentry",0);
	$obSettings->HostName = hmailGetVar("HostName", "");

	$obSettings->SMTPRelayer = hmailGetVar("smtprelayer",0);
	$obSettings->SMTPRelayerPort = hmailGetVar("smtprelayerport",0);
	$obSettings->SMTPRelayerRequiresAuthentication = hmailGetVar("SMTPRelayerRequiresAuthentication",0);
	$obSettings->SMTPRelayerUsername = hmailGetVar("SMTPRelayerUsername","");
	$obSettings->SMTPRelayerConnectionSecurity = hmailGetVar("SMTPRelayerConnectionSecurity","0");

	if (hmailGetVar("SMTPRelayerPassword","") != "")
		$obSettings->SetSMTPRelayerPassword(hmailGetVar("SMTPRelayerPassword",""));

	$obSettings->RuleLoopLimit = hmailGetVar("smtprulelooplimit",0);

	$obSettings->MaxMessageSize = hmailGetVar("maxmessagesize",0);

	$obSettings->SMTPDeliveryBindToIP = hmailGetVar("smtpdeliverybindtoip", "");
	$obSettings->MaxSMTPRecipientsInBatch = hmailGetVar("maxsmtprecipientsinbatch", "0");

	// RFC compliance
	$obSettings->AllowSMTPAuthPlain = hmailGetVar("AllowSMTPAuthPlain",0);
	$obSettings->DenyMailFromNull = hmailGetVar("AllowMailFromNull",0) == "0";
	$obSettings->AllowIncorrectLineEndings = hmailGetVar("AllowIncorrectLineEndings",0);
	$obSettings->DisconnectInvalidClients = hmailGetVar("DisconnectInvalidClients",0);
	$obSettings->MaxNumberOfInvalidCommands = hmailGetVar("MaxNumberOfInvalidCommands",0);
	$obSettings->AddDeliveredToHeader = hmailGetVar("AddDeliveredToHeader",0);
	$obSettings->MaxNumberOfMXHosts = hmailGetVar("MaxNumberOfMXHosts", 15);

	// Advanced
	$obSettings->SMTPConnectionSecurity = hmailGetVar("SMTPConnectionSecurity", 0) ? CONNECTION_SECURITY_STARTTLSOPTIONAL : CONNECTION_SECURITY_NONE;
}

// General
$maxsmtpconnections = $obSettings->MaxSMTPConnections;
$welcomesmtp = $obSettings->WelcomeSMTP;

// Delivery of email
$smtpnooftries = $obSettings->SMTPNoOfTries;
$smtpminutesbetweentry = $obSettings->SMTPMinutesBetweenTry;
$HostName = $obSettings->HostName;

$smtprelayer = $obSettings->SMTPRelayer;
$smtprelayerport = $obSettings->SMTPRelayerPort;
$SMTPRelayerRequiresAuthentication = $obSettings->SMTPRelayerRequiresAuthentication;
$SMTPRelayerConnectionSecurity = $obSettings->SMTPRelayerConnectionSecurity;
$SMTPRelayerUsername = $obSettings->SMTPRelayerUsername;

$smtprulelooplimit = $obSettings->RuleLoopLimit;

$maxmessagesize = $obSettings->MaxMessageSize;

$smtpdeliverybindtoip = $obSettings->SMTPDeliveryBindToIP;
$maxsmtprecipientsinbatch = $obSettings->MaxSMTPRecipientsInBatch;

$AllowSMTPAuthPlain = $obSettings->AllowSMTPAuthPlain;
$AllowMailFromNull = $obSettings->DenyMailFromNull == "0";
$AllowIncorrectLineEndings = $obSettings->AllowIncorrectLineEndings;
$DisconnectInvalidClients = $obSettings->DisconnectInvalidClients;
$MaxNumberOfInvalidCommands = $obSettings->MaxNumberOfInvalidCommands;
$AddDeliveredToHeader = $obSettings->AddDeliveredToHeader;

$AllowSMTPAuthPlainChecked = hmailCheckedIf1($AllowSMTPAuthPlain);
$AllowMailFromNullChecked = hmailCheckedIf1($AllowMailFromNull);
$AllowIncorrectLineEndingsChecked = hmailCheckedIf1($AllowIncorrectLineEndings);
$DisconnectInvalidClientsChecked = hmailCheckedIf1($DisconnectInvalidClients);

$MaxNumberOfMXHosts = $obSettings->MaxNumberOfMXHosts;

$SMTPConnectionSecurity = $obSettings->SMTPConnectionSecurity == CONNECTION_SECURITY_STARTTLSOPTIONAL;
?>
    <div class="box medium">
      <h2><?php EchoTranslation("SMTP") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "smtp");
PrintHidden("action", "save");

PrintPropertyEditRow("maxsmtpconnections", "Maximum number of simultaneous connections (0 for unlimited)", $maxsmtpconnections, 11, "number", "small");
PrintPropertyEditRow("welcomesmtp", "Welcome message", $welcomesmtp);
PrintPropertyEditRow("maxmessagesize", "Max message size (KB)", $maxmessagesize, 11, "number", "small");
?>
        <h3><a href="#"><?php EchoTranslation("Delivery of e-mail")?></a></h3>
        <div class="hidden">
          <h3><?php EchoTranslation("Delivery of e-mail")?></h3>
<?php
PrintPropertyEditRow("smtpnooftries", "Number of retries", $smtpnooftries, 4, "number", "small");
PrintPropertyEditRow("smtpminutesbetweentry", "Minutes between every retry", $smtpminutesbetweentry, 4, "number", "small");
PrintPropertyEditRow("HostName", "Local host name", $HostName);
 ?>
        <h3><?php EchoTranslation("SMTP relayer")?></a></h3>
<?php
PrintPropertyEditRow("smtprelayer", "Remote host name", $smtprelayer);
PrintPropertyEditRow("smtprelayerport", "Remote TCP/IP port", $smtprelayerport, 11, "number", "small");
PrintCheckboxRow("SMTPRelayerRequiresAuthentication", "Server requires authentication", $SMTPRelayerRequiresAuthentication);
PrintPropertyEditRow("SMTPRelayerUsername", "User name", $SMTPRelayerUsername, 255, null, "medium");
PrintPasswordEntry("SMTPRelayerPassword", "Password", 255, "medium");
?>
          <p><?php EchoTranslation("Connection security")?></p>
          <select name="SMTPRelayerConnectionSecurity" class="medium">
            <option value="<?php echo CONNECTION_SECURITY_NONE?>" <?php if ($SMTPRelayerConnectionSecurity == CONNECTION_SECURITY_NONE) echo "selected";?> ><?php EchoTranslation("None")?></a>
            <option value="<?php echo CONNECTION_SECURITY_STARTTLSOPTIONAL?>" <?php if ($SMTPRelayerConnectionSecurity == CONNECTION_SECURITY_STARTTLSOPTIONAL) echo "selected";?> ><?php EchoTranslation("STARTTLS (Optional)")?></a>
            <option value="<?php echo CONNECTION_SECURITY_STARTTLSREQUIRED?>" <?php if ($SMTPRelayerConnectionSecurity == CONNECTION_SECURITY_STARTTLSREQUIRED) echo "selected";?> ><?php EchoTranslation("STARTTLS (Required)")?></a>
            <option value="<?php echo CONNECTION_SECURITY_TLS?>" <?php if ($SMTPRelayerConnectionSecurity == CONNECTION_SECURITY_TLS) echo "selected";?> ><?php EchoTranslation("SSL/TLS")?></a>
          </select>
        </div>
        <h3><a href="#"><?php EchoTranslation("RFC compliance")?></a></h3>
        <div class="hidden">
<?php
PrintCheckboxRow("AllowSMTPAuthPlain", "Allow plain text authentication", $AllowSMTPAuthPlain);
PrintCheckboxRow("AllowMailFromNull", "Allow empty sender address", $AllowMailFromNull);
PrintCheckboxRow("AllowIncorrectLineEndings", "Allow incorrectly formatted line endings", $AllowIncorrectLineEndings);
PrintCheckboxRow("DisconnectInvalidClients", "Disconnect client after too many invalid commands", $DisconnectInvalidClients);
PrintPropertyEditRow("MaxNumberOfInvalidCommands", "Maximum number of invalid commands", $MaxNumberOfInvalidCommands, 4, "number", "small");
?>
        </div>
        <h3><a href="#"><?php EchoTranslation("Advanced")?></a></h3>
        <div class="hidden">
          <h3><?php EchoTranslation("Delivery of e-mail")?></h3>
<?php
PrintPropertyEditRow("smtpdeliverybindtoip", "Bind to local IP address", $smtpdeliverybindtoip, 20, null, "ip");
PrintPropertyEditRow("maxsmtprecipientsinbatch", "Maximum number of recipients in batch", $maxsmtprecipientsinbatch, 4, "number", "small");
PrintCheckboxRow("SMTPConnectionSecurity", "Use STARTTLS if available", $SMTPConnectionSecurity);
?>
          <h3><?php EchoTranslation("Other")?></h3>
<?php
PrintCheckboxRow("AddDeliveredToHeader", "Add Delivered-To header", $AddDeliveredToHeader);
PrintPropertyEditRow("smtprulelooplimit", "Rule Loop Limit", $smtprulelooplimit, 3, "number", "small");
PrintPropertyEditRow("MaxNumberOfMXHosts", "Maximum number of recipient hosts", $MaxNumberOfMXHosts, 11, "number", "small");
?>
        </div>
<?php
PrintSaveButton();
?>
      </form>
    </div>