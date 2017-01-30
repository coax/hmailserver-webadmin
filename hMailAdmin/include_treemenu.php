<?php
if (!defined('IN_WEBADMIN'))
	exit();
?>
        <li class="cd-label"><?php EchoTranslation("Main") ?></li>
<?php
//user
if (hmailGetAdminLevel() == 0) {
	$domainname = hmailGetUserDomainName($username);

	$obDomain = $obBaseApp->Domains->ItemByName($domainname);
	$obAccounts = $obDomain->Accounts;

	$obAccount = $obAccounts->ItemByAddress($username);

	$accountaddress = $obAccount->Address;
	$accountaddress = str_replace("'", "\'", $accountaddress);
	$accountaddress = PreprocessOutput($accountaddress);

	$url = htmlentities("?page=account&action=edit&accountid=" . $obAccount->ID . "&domainid=" . $obDomain->ID);

	//webmail parser
	if(isset($hmail_config['webmail']))
		$Webmail = str_replace("[domain]", $domainname, $hmail_config['webmail']);
?>
        <li class="has-children user <?php if (strpos('hm_account,hm_account_externalaccounts', $page) !== false) echo 'active' ?>">
          <a href="<?php echo $url ?>"><?php echo $accountaddress ?></a>
          <ul>
            <li><a href="?page=account_externalaccounts&accountid=<?php echo $obAccount->ID ?>&domainid=<?php echo $obDomain->ID ?>"><?php EchoTranslation("External accounts") ?></a></li>
          </ul>
        </li>
        <?php if(isset($Webmail)) { ?><li class="webmail"><a href="<?php echo $Webmail ?>"><?php EchoTranslation("Webmail") ?></a></li><?php } ?>
<?php
}

//domain
if (hmailGetAdminLevel() == 1) {
	$domainname = hmailGetUserDomainName($username);
	$obDomain = $obBaseApp->Domains->ItemByName($domainname);

	GetStringForDomain($obDomain,1);
}

//admin
if (hmailGetAdminLevel() == 2) {
	$obSettings = $obBaseApp->Settings();

	//counters
	$Domains = $obBaseApp->Domains();
	$TotalDomains = $Domains->Count();

	$Rules = $obBaseApp->Rules();
	$TotalRules = $Rules->Count();

	$Blacklists = $obSettings->Antispam->DNSBlackLists();
	$TotalBlacklists = $Blacklists->Count();

	$IpRanges = $obSettings->SecurityRanges();
	$TotalIpRanges = $IpRanges->Count();

	$Routes = $obSettings->Routes();
	$TotalRoutes = $Routes->Count();

	$Relays = $obSettings->IncomingRelays();
	$TotalRelays = $Relays->Count();

	$Ports = $obSettings->TCPIPPorts();
	$TotalPorts = $Ports->Count();
?>
        <li class="status <?php if ($page=='hm_status') echo 'active' ?>"><a href="?page=status"><?php EchoTranslation("Dashboard") ?></a></li>
        <li class="has-children domains <?php if (strpos('hm_domains,hm_domain,hm_accounts,hm_account,hm_aliases,hm_aliase,hm_distributionlists,hm_distributionlist,hm_domain_aliasname', $page) !== false) echo 'active' ?>">
          <a href="?page=domains"><?php EchoTranslation("Domains") ?><span class="count"><?php echo $TotalDomains ?></span></a>
          <ul>
<?php
for ($i = 1; $i <= $TotalDomains; $i++) {
	$obDomain = $obBaseApp->Domains[$i-1];

	GetStringForDomain($obDomain,2);
}
?>
          </ul>
        </li>
        <li class="rules <?php if (strpos('hm_rules,hm_rule', $page) !== false) echo 'active' ?>">
          <a href="?page=rules"><?php EchoTranslation("Rules") ?><span class="count"><?php echo $TotalRules ?></span></a>
        </li>
      </ul>
      <ul>
        <li class="cd-label"><?php EchoTranslation("Configuration") ?></li>
        <li class="has-children settings">
          <a href="#"><?php EchoTranslation("Settings") ?></a>
          <ul>
            <li class="has-children">
              <a href="#"><?php EchoTranslation("Protocols") ?></a>
              <ul>
                <li class="has-children">
                  <a href="?page=smtp"><?php EchoTranslation("SMTP") ?></a>
                  <ul>
                    <li><a href="?page=routes"><?php EchoTranslation("Routes") ?><span class="count"><?php echo $TotalRoutes ?></span></a></li>
                  </ul>
                </li>
                <li><a href="?page=pop3"><?php EchoTranslation("POP3") ?></a></li>
                <li><a href="?page=imap"><?php EchoTranslation("IMAP") ?></a></li>
              </ul>
            </li>
            <li class="has-children">
              <a href="?page=smtp_antispam"><?php EchoTranslation("Anti-spam") ?></a>
              <ul>
                <li><a href="?page=dnsblacklists"><?php EchoTranslation("DNS blacklists") ?><span class="count"><?php echo $TotalBlacklists ?></span></a></li>
                <li><a href="?page=surblservers"><?php EchoTranslation("SURBL servers") ?></a></li>
                <li><a href="?page=greylisting"><?php EchoTranslation("Greylisting") ?></a></li>
                <li><a href="?page=whitelistaddresses"><?php EchoTranslation("White listing") ?></a></li>
              </ul>
            </li>
            <li><a href="?page=smtp_antivirus"><?php EchoTranslation("Anti-virus") ?></a></li>
            <li><a href="?page=logging"><?php EchoTranslation("Logging") ?></a></li>
            <li class="has-children">
              <a href="#"><?php EchoTranslation("Advanced") ?></a>
              <ul>
                <li><a href="?page=sslcertificates"><?php EchoTranslation("SSL certificates") ?></a></li>
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
        <li class="has-children utilities">
          <a href="#"><?php EchoTranslation("Utilities") ?></a>
          <ul>
            <li><a href="?page=backup"><?php EchoTranslation("Backup") ?></a></li>
            <li><a href="?page=diagnostics"><?php EchoTranslation("Diagnostics") ?></a></li>
          </ul>
        </li>
        <li class="help">
          <a href="<?php echo $DocumentationLink ?>" target="_blank"><?php EchoTranslation("Documentation") ?></a>
        </li>
      </ul>
      <ul>
        <li class="cd-label"><?php EchoTranslation("Quick links") ?></li>
        <li class="dns-blacklists <?php if ($page=='hm_dnsblacklists') echo 'active' ?>">
          <a href="?page=dnsblacklists"><?php EchoTranslation("DNS blacklists") ?><span class="count"><?php echo $TotalBlacklists ?></span></a>
        </li>
        <li class="ip-ranges <?php if ($page=='hm_securityranges') echo 'active' ?>">
          <a href="?page=securityranges"><?php EchoTranslation("IP Ranges") ?><span class="count"><?php echo $TotalIpRanges ?></span></a>
        </li>
        <li class="logs <?php if ($page=='hm_logviewer') echo 'active' ?>">
          <a href="?page=logviewer"><?php EchoTranslation("Log viewer") ?></a>
        </li>
      </ul>
      <ul>
        <li class="cd-label"><?php EchoTranslation("Action") ?></li>
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
		$state = $obLanguage->String("Stopped");
		break;
	case 2:
		$state = $obLanguage->String("Starting");
		break;
	case 3:
		$state = $obLanguage->String("Running");
		break;
	case 4:
		$state = $obLanguage->String("Stopping");
		break;
	default:
		$state = "Unknown";
		break;
}

switch($ServerState) {
	case 1:
	case 4:
		$controlaction = 1;
		$controlbutton = $obLanguage->String("Resume");
		break;
	case 2:
	case 3:
		$controlaction = 0;
		$controlbutton = $obLanguage->String("Pause");
		break;
	default:
		$state = "Unknown";
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

function GetStringForDomain($obDomain, $parentid) {
	global $dtree, $dtitem, $domain_root;

	$current_domainid = hmailGetVar("domainid",0);
	$current_accountid = hmailGetVar("accountid",0);

	$domainname = $obDomain->Name;
	$domainname = PreprocessOutput($domainname);
	$domainname = str_replace("'", "\'", $domainname);

	//define $page variable again
	$page = hmailGetVar("page");
	$domainid = hmailGetVar("domainid");
	if ($domainid==$obDomain->ID) $domainname = '<span style="border-bottom:1px solid;" title="' . $domainname . '">' . $domainname . '</span>';

	if ($current_domainid != $obDomain->ID && hmailGetAdminLevel() == ADMIN_SERVER) {
		// If the user is logged on as a system administrator, only show accounts
		// for the currently selected domain.
		echo '            <li><a href="?page=domain&action=edit&domainid=' . $obDomain->ID . '">' . $domainname . '</a></li>' . PHP_EOL;
		return;
	} else
		$Accounts  = $obDomain->Accounts();
		$TotalAccounts = $Accounts->Count();
		$accounts_root = $dtitem++;

		$Aliases = $obDomain->Aliases();
		$TotalAliases = $Aliases->Count();
		$aliases_root = $dtitem++;

		$DistributionLists = $obDomain->DistributionLists();
		$TotalDistributionLists = $DistributionLists->Count();

		echo '            <li class="has-children">
              <a href="?page=domain&action=edit&domainid=' . $obDomain->ID . '">' . $domainname . '</a>
              <ul>
                <li><a href="?page=accounts&domainid=' . $obDomain->ID . '">' . GetStringForJavaScript("Accounts") . '<span class="count">' . $TotalAccounts . '</a></li>
                <li><a href="?page=aliases&domainid=' . $obDomain->ID . '">' . GetStringForJavaScript("Aliases") . '<span class="count">' . $TotalAliases . '</a></li>
                <li><a href="?page=distributionlists&domainid=' . $obDomain->ID . '">' . GetStringForJavaScript("Distribution lists") . '<span class="count">' . $TotalDistributionLists . '</a></li>
              </ul>
            </li>' . PHP_EOL;
}

unset($obDomain);
unset($obAccount);
?>