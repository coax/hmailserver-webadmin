<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Domain admin but not for this domain.

$routeid = hmailGetVar("routeid",0);
$action = hmailGetVar("action","");

$obRoutes = $obSettings->Routes();

$routetargetsmtpport = 25;
$routenumberoftries = 4;
$routemminutesbetweentry = 60;

$routedomainname = "";
$routetargetsmtphost = "";
$TreatRecipientAsLocalDomain = 0;
$TreatSenderAsLocalDomain = 0;
$ConnectionSecurity = 0;
$routerequiresauth =0;
$routeauthusername ="";
$AllAddresses = true;

if ($action == "edit") {
	$obRoute = $obRoutes->ItemByDBID($routeid);

	$routedomainname = $obRoute->DomainName;
	$routetargetsmtphost = $obRoute->TargetSMTPHost;
	$routetargetsmtpport = $obRoute->TargetSMTPPort;
	$TreatRecipientAsLocalDomain = $obRoute->TreatRecipientAsLocalDomain;
	$TreatSenderAsLocalDomain = $obRoute->TreatSenderAsLocalDomain;

	$routenumberoftries = $obRoute->NumberOfTries;
	$routemminutesbetweentry = $obRoute->MinutesBetweenTry;
	$routerequiresauth = $obRoute->RelayerRequiresAuth;
	$routeauthusername = $obRoute->RelayerAuthUsername;
	$ConnectionSecurity = $obRoute->ConnectionSecurity;
	$AllAddresses = $obRoute->AllAddresses;
}
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Route") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_route_save");
PrintHidden("action", $action);
PrintHidden("routeid", $routeid);

PrintPropertyEditRow("routedomainname", "Domain", $routedomainname, 255);
PrintPropertyEditRow("routetargetsmtphost", "Target SMTP host", $routetargetsmtphost, 255);
PrintPropertyEditRow("routetargetsmtpport", "TCP/IP port", $routetargetsmtpport, 10, "number", "small");
?>
          <p><?php EchoTranslation("Connection security")?></p>
          <select name="ConnectionSecurity" class="medium">
            <option value="<?php echo CONNECTION_SECURITY_NONE?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_NONE) echo "selected";?> ><?php EchoTranslation("None")?></option>
            <option value="<?php echo CONNECTION_SECURITY_STARTTLSOPTIONAL?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_STARTTLSOPTIONAL) echo "selected";?> ><?php EchoTranslation("STARTTLS (Optional)")?></option>
            <option value="<?php echo CONNECTION_SECURITY_STARTTLSREQUIRED?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_STARTTLSREQUIRED) echo "selected";?> ><?php EchoTranslation("STARTTLS (Required)")?></option>
            <option value="<?php echo CONNECTION_SECURITY_TLS?>" <?php if ($ConnectionSecurity == CONNECTION_SECURITY_TLS) echo "selected";?> ><?php EchoTranslation("SSL/TLS")?></option>
          </select>
          <p><?php EchoTranslation("When sender matches route, treat sender domain as")?></p>
          <div style="position:relative; display:inline-block;"><input type="radio" name="TreatSenderAsLocalDomain" value="1" id="1" <?php if ($TreatSenderAsLocalDomain == 1) echo "checked"; ?>><label for="1"><?php EchoTranslation("A local domain")?></label></div>
          <div style="position:relative; display:inline-block;"><input type="radio" name="TreatSenderAsLocalDomain" value="0" id="2" <?php if ($TreatSenderAsLocalDomain == 0) echo "checked"; ?>><label for="2"><?php EchoTranslation("An external domain")?></label></div>
          <p><?php EchoTranslation("When recipient matches route, treat recipient domain as")?></p>
          <div style="position:relative; display:inline-block;"><input type="radio" name="TreatRecipientAsLocalDomain" value="1" id="3" <?php if ($TreatRecipientAsLocalDomain == 1) echo "checked"; ?>><label for="3"><?php EchoTranslation("A local domain")?></label></div>
          <div style="position:relative; display:inline-block;"><input type="radio" name="TreatRecipientAsLocalDomain" value="0" id="4" <?php if ($TreatRecipientAsLocalDomain == 0) echo "checked"; ?>><label for="4"><?php EchoTranslation("An external domain")?></label></div>
          <h3><a href="#"><?php EchoTranslation("Addresses") ?></a></h3>
          <div class="hidden">
<?php
PrintCheckboxRow("AllAddresses", "Deliver to all addresses", $AllAddresses);
?>
          </div>
          <h3><a href="#"><?php EchoTranslation("Delivery") ?></a></h3>
          <div class="hidden">
<?php
PrintPropertyEditRow("routenumberoftries", "Number of retries", $routenumberoftries, 4, "number", "small");
PrintPropertyEditRow("routemminutesbetweentry", "Minutes between every retry", $routemminutesbetweentry, 4, "number", "small");
PrintCheckboxRow("routerequiresauth", "Server requires authentication", $routerequiresauth);
PrintPropertyEditRow("routeauthusername", "User name", $routeauthusername, 255, null, "medium");
PrintPropertyEditRow("routeauthpassword", "Password", "", 255, null, "medium");
?>
          </div>
<?php
PrintSaveButton(null, null, '?page=routes');
?>
      </form>
    </div>