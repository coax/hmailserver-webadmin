<?php
if (!defined('IN_WEBADMIN'))
	exit();
?>
        <li class="label"><?php EchoTranslation("Main") ?></li>
<?php
// User only
if (hmailGetAdminLevel() == 0) {
	$domainname = hmailGetUserDomainName($username);

	$obDomain = $obBaseApp->Domains->ItemByName($domainname);
	$obAccounts = $obDomain->Accounts;
	$obAccount = $obAccounts->ItemByAddress($username);
	$accountaddress = $obAccount->Address;
	$accountaddress = str_replace("'", "\'", $accountaddress);
	$accountaddress = PreprocessOutput($accountaddress);

	$url = htmlentities("?page=account&action=edit&accountid=" . $obAccount->ID . "&domainid=" . $obDomain->ID);

	// Webmail parser
	if(isset($hmail_config['webmail'])) $Webmail = str_replace("[domain]", $domainname, $hmail_config['webmail']);
?>
        <li class="has-children<?php if (strpos('hm_account,hm_account_externalaccounts', $page) !== false) echo ' active' ?>">
          <a href="<?php echo $url ?>"><i data-feather="user"></i><?php echo $accountaddress ?><i data-feather="chevron-down" class="arrow"></i></a>
          <ul>
            <li><a href="?page=account_externalaccounts&accountid=<?php echo $obAccount->ID ?>&domainid=<?php echo $obDomain->ID ?>"><?php EchoTranslation("External accounts") ?></a></li>
          </ul>
        </li>
        <?php if(isset($Webmail)) { ?><li><a href="<?php echo $Webmail ?>" target="_blank"><i data-feather="mail"></i><?php EchoTranslation("Webmail") ?></a></li><?php } ?>
<?php
}

// Domain admin
if (hmailGetAdminLevel() == 1) {
	$domainname = hmailGetUserDomainName($username);
	$obDomain = $obBaseApp->Domains->ItemByName($domainname);

	GetStringForDomain($obDomain);

	$obAccounts = $obDomain->Accounts;
	$obAccount = $obAccounts->ItemByAddress($username);
	$accountaddress = $obAccount->Address;
	$accountaddress = str_replace("'", "\'", $accountaddress);
	$accountaddress = PreprocessOutput($accountaddress);

	$url = htmlentities("?page=account&action=edit&accountid=" . $obAccount->ID . "&domainid=" . $obDomain->ID);

	// Webmail parser
	if(isset($hmail_config['webmail'])) $Webmail = str_replace("[domain]", $domainname, $hmail_config['webmail']);
?>
        <li class="has-children<?php if (strpos('hm_account,hm_account_externalaccounts', $page) !== false) echo ' active' ?>">
          <a href="<?php echo $url ?>"><i data-feather="user"></i><?php echo $accountaddress ?><i data-feather="chevron-down" class="arrow"></i></a>
          <ul>
            <li><a href="<?php echo $url ?>"><?php EchoTranslation("Account settings") ?></a></li>
            <li><a href="?page=account_externalaccounts&accountid=<?php echo $obAccount->ID ?>&domainid=<?php echo $obDomain->ID ?>"><?php EchoTranslation("External accounts") ?></a></li>
          </ul>
        </li>
        <?php if(isset($Webmail)) { ?><li><a href="<?php echo $Webmail ?>" target="_blank"><i data-feather="mail"></i><?php EchoTranslation("Webmail") ?></a></li><?php } ?>
<?php
}

// Admin
if (hmailGetAdminLevel() == 2) {
	$obSettings = $obBaseApp->Settings();

	// Counters
	$Domains = $obBaseApp->Domains();
	$TotalDomains = $Domains->Count();

	$Rules = $obBaseApp->Rules();
	$TotalRules = $Rules->Count();

	$Blacklists = $obSettings->AntiSpam->DNSBlackLists();
	$TotalBlacklists = $Blacklists->Count();

	$IpRanges = $obSettings->SecurityRanges();
	$TotalIpRanges = $IpRanges->Count();

	$Routes = $obSettings->Routes();
	$TotalRoutes = $Routes->Count();

	$Relays = $obSettings->IncomingRelays();
	$TotalRelays = $Relays->Count();

	$Ports = $obSettings->TCPIPPorts();
	$TotalPorts = $Ports->Count();

	$TotalSURBLServers = $obSettings->AntiSpam->SURBLServers->Count();
	$TotalWhiteListAddresses = $obSettings->AntiSpam->WhiteListAddresses->Count();
	$TotalGreyListWhiteListAddresses = $obSettings->AntiSpam->GreyListingWhiteAddresses->Count();
	$TotalSSLCertificates = $obSettings->SSLCertificates->Count();

?>
        <li class="<?php if (($page=='hm_status') || ($page=='hm_frontpage')) echo 'active' ?>"><a href="?page=status"><i data-feather="home"></i><?php EchoTranslation("Dashboard") ?></a></li>
        <li class="has-children<?php if (strpos('hm_domains,hm_domain,hm_accounts,hm_account,hm_aliases,hm_aliase,hm_distributionlists,hm_distributionlist,hm_domain_aliasname', $page) !== false) echo ' active' ?>">
          <a href="?page=domains"><i data-feather="globe"></i><?php EchoTranslation("Domains") ?><i data-feather="chevron-down" class="arrow"></i><span class="count"><?php echo $TotalDomains ?></span></a>
          <ul>
<?php
for ($i = 1; $i <= $TotalDomains; $i++) {
	$obDomain = $obBaseApp->Domains[$i-1];

	GetStringForDomain($obDomain);
}

// Show all domains (useful for mobile)
if ((hmailGetAdminLevel() == ADMIN_SERVER) && ($page != 'hm_domains')) {
?>
            <li><a href="?page=domains"><?php EchoTranslation("Show all domains") ?></a></li><?php
}
?>
          </ul>
        </li>
        <li class="<?php if (strpos('hm_rules,hm_rule', $page) !== false) echo 'active' ?>">
          <a href="?page=rules"><i data-feather="sliders"></i><?php EchoTranslation("Rules") ?><span class="count"><?php echo $TotalRules ?></span></a>
        </li>
        <li class="label"><?php EchoTranslation("Configuration") ?></li>
        <li class="has-children">
          <a href="#"><i data-feather="settings"></i><?php EchoTranslation("Settings") ?><i data-feather="chevron-down" class="arrow"></i></a>
          <ul>
            <li class="has-children">
              <a href="?page=protocols" class="more"><?php EchoTranslation("Protocols") ?><i data-feather="chevron-down" class="arrow"></i></a>
              <ul>
                <li class="has-children">
                  <a href="?page=smtp" class="more"><?php EchoTranslation("SMTP") ?><i data-feather="chevron-down" class="arrow"></i></a>
                  <ul>
                    <li><a href="?page=routes"><?php EchoTranslation("Routes") ?><span class="count"><?php echo $TotalRoutes ?></span></a></li>
                  </ul>
                </li>
                <li><a href="?page=pop3"><?php EchoTranslation("POP3") ?></a></li>
                <li><a href="?page=imap"><?php EchoTranslation("IMAP") ?></a></li>
              </ul>
            </li>
            <li class="has-children">
              <a href="?page=smtp_antispam" class="more"><?php EchoTranslation("Anti-spam") ?><i data-feather="chevron-down" class="arrow"></i></a>
              <ul>
                <li><a href="?page=dnsblacklists"><?php EchoTranslation("DNS blacklists") ?><span class="count"><?php echo $TotalBlacklists ?></span></a></li>
                <li><a href="?page=surblservers"><?php EchoTranslation("SURBL servers") ?><span class="count"><?php echo $TotalSURBLServers ?></span></a></li>
                <li class="has-children">
                  <a href="?page=greylisting" class="more"><?php EchoTranslation("Greylisting") ?><i data-feather="chevron-down" class="arrow"></i></a>
                  <ul>
                    <li><a href="?page=greylistingwhiteaddresses"><?php EchoTranslation("White listing") ?><span class="count"><?php echo $TotalGreyListWhiteListAddresses ?></span></a></li>
                  </ul>
                </li>
                <li><a href="?page=whitelistaddresses"><?php EchoTranslation("White listing") ?><span class="count"><?php echo $TotalWhiteListAddresses ?></span></a></li>
              </ul>
            </li>
            <li><a href="?page=smtp_antivirus"><?php EchoTranslation("Anti-virus") ?></a></li>
            <li><a href="?page=logging"><?php EchoTranslation("Logging") ?></a></li>
            <li class="has-children">
              <a href="#" class="more"><?php EchoTranslation("Advanced") ?><i data-feather="chevron-down" class="arrow"></i></a>
              <ul>
                <li><a href="?page=sslcertificates"><?php EchoTranslation("SSL certificates") ?><span class="count"><?php echo $TotalSSLCertificates ?></span></a></li>
                <li><a href="?page=autoban"><?php EchoTranslation("Auto ban") ?></a></li>
                <li><a href="?page=securityranges"><?php EchoTranslation("IP Ranges") ?><span class="count"><?php echo $TotalIpRanges ?></span></a></li>
                <li><a href="?page=incomingrelays"><?php EchoTranslation("Incoming relays") ?><span class="count"><?php echo $TotalRelays ?></span></a></li>
                <li><a href="?page=mirror"><?php EchoTranslation("Mirror") ?></a></li>
                <li><a href="?page=performance"><?php EchoTranslation("Performance") ?></a></li>
                <li><a href="?page=servermessages"><?php EchoTranslation("Server messages") ?></a></li>
                <li><a href="?page=ssltls"><?php EchoTranslation("SSL/TLS") ?></a></li>
                <li><a href="?page=scripts"><?php EchoTranslation("Scripts") ?></a></li>
                <li><a href="?page=tcpipports"><?php EchoTranslation("TCP/IP ports") ?><span class="count"><?php echo $TotalPorts ?></span></a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="has-children">
          <a href="#"><i data-feather="tool"></i><?php EchoTranslation("Utilities") ?><i data-feather="chevron-down" class="arrow"></i></a>
          <ul>
            <li><a href="?page=blacklistcheck"><?php EchoTranslation("Blacklist check") ?></a></li>
            <li><a href="?page=backup"><?php EchoTranslation("Backup") ?></a></li>
            <li><a href="?page=diagnostics"><?php EchoTranslation("Diagnostics") ?></a></li>
          </ul>
        </li>
        <li>
          <a href="<?php echo $DocumentationLink ?>" target="_blank"><i data-feather="help-circle"></i><?php EchoTranslation("Documentation") ?></a>
        </li>
        <li class="label"><?php EchoTranslation("Quick links") ?></li>
        <li class="dns-blacklists <?php if ($page=='hm_dnsblacklists') echo 'active' ?>">
          <a href="?page=dnsblacklists"><i data-feather="shield"></i><?php EchoTranslation("DNS blacklists") ?><span class="count"><?php echo $TotalBlacklists ?></span></a>
        </li>
        <li class="<?php if ($page=='hm_securityranges') echo 'active' ?>">
          <a href="?page=securityranges"><i data-feather="flag"></i><?php EchoTranslation("IP Ranges") ?><span class="count orange"><?php echo $TotalIpRanges ?></span></a>
        </li>
        <li class="<?php if ($page=='hm_logviewer') echo 'active' ?>">
          <a href="?page=logviewer"><i data-feather="activity"></i><?php EchoTranslation("Log parser") ?></a>
        </li>
<?php
if (!empty($hmail_config['dmarc_enable'])) {
?>
        <li class="<?php if ($page=='hm_dmarcreports') echo 'active' ?>">
          <a href="?page=dmarcreports"><i data-feather="inbox"></i><?php EchoTranslation("DMARC reports") ?></a>
        </li>
<?php
}

if (!empty($hmail_config['tlsreport_enable'])) { ?>
        <li class="<?php if ($page=='hm_tlsreports') echo 'active' ?>">
          <a href="?page=tlsreports"><i data-feather="lock"></i>TLS reports</a>
        </li>
<?php
}
?>
        <li class="label"><?php EchoTranslation("Action") ?></li>
<?php
$Action = hmailGetVar("action","");

if ($Action == "control") {
	$controlaction = hmailGetVar("controlaction","");
	if ($controlaction == "1")
		$obBaseApp->Start();
	else if ($controlaction == "0")
		$obBaseApp->Stop();
}

$ServerState = $obBaseApp->ServerState();

switch($ServerState) {
	case 1:
		$state = Translate("Stopped");
		break;
	case 2:
		$state = Translate("Starting");
		break;
	case 3:
		$state = Translate("Running");
		break;
	case 4:
		$state = Translate("Stopping");
		break;
	default:
		$state = Translate("Unknown");
		break;
}

switch($ServerState) {
	case 1:
	case 4:
		$controlaction = 1;
		$controlbutton = Translate("Resume");
		break;
	case 2:
	case 3:
		$controlaction = 0;
		$controlbutton = Translate("Pause");
		break;
	default:
		$controlaction = 0;
		$controlbutton = Translate("Unknown");
		break;
}
?>
        <li class="action-btn center">
          <form action="index.php" method="post">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "status");
PrintHidden("action", "control");
PrintHidden("controlaction", $controlaction);
?>
            <input type="submit" value="<?php echo $controlbutton ?> server">
          </form>
        </li>
<?php
}

function GetStringForDomain($Domain) {
	$DomainId = $Domain->ID;

	$current_domainid = hmailGetVar("domainid", 0);
	//$current_accountid = hmailGetVar("accountid", 0);

	$DomainName = $Domain->Name;
	$DomainName = PreprocessOutput($DomainName);
	$DomainName = str_replace("'", "\'", $DomainName);

	if (hmailGetVar("domainid")==$DomainId) $DomainName = '<span class="active">' . $DomainName . '</span>';

	if ($current_domainid != $DomainId && hmailGetAdminLevel() == ADMIN_SERVER) {
		// If the user is logged on as a system administrator, only show accounts for the currently selected domain.
		echo '            <li><a href="?page=domain&action=edit&domainid=' . $DomainId . '">' . $DomainName . '</a></li>' . PHP_EOL;
		return;
	} else
		$Accounts = $Domain->Accounts();
		$TotalAccounts = $Accounts->Count();

		$Aliases = $Domain->Aliases();
		$TotalAliases = $Aliases->Count();

		$DistributionLists = $Domain->DistributionLists();
		$TotalDistributionLists = $DistributionLists->Count();

		echo '            <li class="has-children">
              <a href="?page=domain&action=edit&domainid=' . $DomainId . '">';
		if (hmailGetAdminLevel() != ADMIN_SERVER)
			echo '<i data-feather="globe"></i>';
		echo '<i data-feather="chevron-down" class="arrow"></i>' . $DomainName . '</a>
              <ul>' . PHP_EOL;
		if (hmailGetAdminLevel() != ADMIN_SERVER)
			echo '                <li><a href="?page=domain&action=edit&domainid=' . $DomainId . '">' . Translate("Domain settings") . '</a></li>' . PHP_EOL;
		echo '                <li class="has-children"><a href="?page=accounts&domainid=' . $DomainId . '">' . GetStringForJavaScript("Accounts") . '<span class="count">' . $TotalAccounts . '</a>' . PHP_EOL;
		if ($TotalAccounts>0) {
			echo '                  <ul>' . PHP_EOL;
			for ($j = 0; $j < $TotalAccounts; $j++) {
				$Account = $Accounts->Item($j);
				$AccountId = $Account->ID;
				$EmailAddress = $Account->Address;
				$EmailAddress = PreprocessOutput($EmailAddress);
				$EmailAddress = str_replace("'", "\'", $EmailAddress);
				echo '                    <li><a href="?page=account&action=edit&accountid=' . $AccountId . '&domainid=' . $DomainId . '">' . $EmailAddress . '</a></li>' . PHP_EOL;
			}
			echo '                  </ul>' . PHP_EOL;
		}
		echo '                </li>
                <li class="has-children"><a href="?page=aliases&domainid=' . $DomainId . '">' . GetStringForJavaScript("Aliases") . '<span class="count">' . $TotalAliases . '</a>' . PHP_EOL;
		if ($TotalAliases>0) {
			echo '                  <ul>' . PHP_EOL;
			for ($j = 0; $j < $TotalAliases; $j++) {
				$Alias = $Aliases->Item($j);
				$AliasId = $Alias->ID;
				$AliasName = $Alias->Name;
				$AliasName = PreprocessOutput($AliasName);
				$AliasName = str_replace("'", "\'", $AliasName);
				echo '                    <li><a href="?page=alias&action=edit&aliasid=' . $AliasId . '&domainid=' . $DomainId . '">' . $AliasName . '</a></li>' . PHP_EOL;
			}
			echo '                  </ul>' . PHP_EOL;
		}
		echo '                </li>
                <li class="has-children"><a href="?page=distributionlists&domainid=' . $DomainId . '">' . GetStringForJavaScript("Distribution lists") . '<span class="count">' . $TotalDistributionLists . '</a>' . PHP_EOL;
		if ($TotalDistributionLists>0) {
			echo '                  <ul>' . PHP_EOL;
			for ($j = 0; $j < $TotalDistributionLists; $j++) {
				$DistributionList = $DistributionLists->Item($j);
				$DistributionListId = $DistributionList->ID;
				$Address = $DistributionList->Address;
				$Address = PreprocessOutput($Address);
				$Address = str_replace("'", "\'", $Address);
				echo '                    <li><a href="?page=distributionlist&action=edit&distributionlistid=' . $DistributionListId . '&domainid=' . $DomainId . '">' . $Address . '</a></li>' . PHP_EOL;
			}
			echo '                  </ul>' . PHP_EOL;
		}
		echo '                </li>
              </ul>
            </li>' . PHP_EOL;
}

unset($Domain);
unset($Accounts);
?>