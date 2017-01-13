<?php
if (!defined('IN_WEBADMIN'))
	exit();

/* FIXME */
$dtitem = 0;
$dtree = "d.add(" . $dtitem++ .",-1,'" . GetStringForJavaScript("Welcome") . "','index.php','','','','');\r\n";


/* FIXME */
if (hmailGetAdminLevel() == 0) {
	// User
	$domainname = hmailGetUserDomainName($username);

	$obDomain = $obBaseApp->Domains->ItemByName($domainname);
	$obAccounts = $obDomain->Accounts;

	$obAccount = $obAccounts->ItemByAddress($username);

	$accountaddress = $obAccount->Address;
	$accountaddress = str_replace("'", "\'", $accountaddress);
	$accountaddress = PreprocessOutput($accountaddress);

	$url = htmlentities("index.php?page=account&action=edit&accountid=" . $obAccount->ID . "&domainid=" . $obDomain->ID);
	$di = $dtitem++;

	$dtree .= "d.add($di,0,'" . $accountaddress . "','$url','','','" . "images/user.png','" . "images/user.png');\r\n";
	$dtree .= "d.add(" . $dtitem++ . ",$di,'" . GetStringForJavaScript("External accounts") . "','index.php?page=account_externalaccounts&accountid=" . $obAccount->ID . "&domainid=" . $obDomain->ID. "');\r\n";
}

/* FIXME */
if (hmailGetAdminLevel() == 1) {
	// Domain
	//$dtree .= "d.add(" . $dtitem++ .",0,'" . GetStringForJavaScript("Domains") . "','','','','" . "images/server.png','" . "images/server.png');\r\n";

	$domainname = hmailGetUserDomainName($username);
	$obDomain = $obBaseApp->Domains->ItemByName($domainname);
	$domain_root = $dtitem++;

	GetStringForDomain($obDomain,1);
}

/* tree menu for Administrator */
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
?>
        <li class="cd-label">Main</li>
        <li class="status <?php if ($page=='hm_status') echo 'active' ?>"><a href="index.php?page=status">Dashboard</a></li>
        <li class="has-children domains <?php if (strpos('hm_domains,hm_domain,hm_accounts,hm_account,hm_aliases,hm_aliase,hm_distributionlists,hm_distributionlist,hm_domain_aliasname', $page) !== false) echo 'active' ?>">
          <a href="index.php?page=domains">Domains<span class="count"><?php echo $TotalDomains ?></span></a>
          <ul>
<?php
for ($i = 1; $i <= $TotalDomains; $i++) {
	$obDomain = $obBaseApp->Domains[$i-1];
	$domain_root = $dtitem++;

	GetStringForDomain($obDomain,2);
}
?>
          </ul>
        </li>
        <li class="rules <?php if (strpos('hm_rules,hm_rule', $page) !== false) echo 'active' ?>">
          <a href="index.php?page=rules">Rules<span class="count"><?php echo $TotalRules ?></span></a>
        </li>
      </ul>
      <ul>
        <li class="cd-label">Configuration</li>
        <li class="has-children settings">
          <a href="#">Settings</a>
          <ul>
            <li class="has-children">
              <a href="#">Protocols</a>
              <ul>
                <li class="has-children">
                  <a href="index.php?page=smtp">SMTP</a>
                  <ul>
                    <li><a href="?page=routes">Routes<span class="count"><?php echo $TotalRoutes ?></span></a></li>
                  </ul>
                </li>
                <li><a href="index.php?page=pop3">POP3</a></li>
                <li><a href="index.php?page=imap">IMAP</a></li>
              </ul>
            </li>
            <li class="has-children">
              <a href="index.php?page=smtp_antispam">Anti-spam</a>
              <ul>
                <li><a href="index.php?page=dnsblacklists">DNS blacklists<span class="count"><?php echo $TotalBlacklists ?></span></a></li>
                <li><a href="index.php?page=surblservers">SURBL servers</a></li>
                <li><a href="index.php?page=greylisting">Greylisting</a></li>
                <li><a href="index.php?page=whitelistaddresses">White listing</a></li>
              </ul>
            </li>
            <li><a href="index.php?page=smtp_antivirus">Anti-virus</a></li>
            <li><a href="index.php?page=logging">Logging</a></li>
            <li class="has-children">
              <a href="#">Advanced</a>
              <ul>
                <li><a href="index.php?page=sslcertificates">SSL certificates</a></li>
                <li><a href="index.php?page=autoban">Auto ban</a></li>
                <li><a href="index.php?page=securityranges">IP Ranges<span class="count"><?php echo $TotalIpRanges ?></span></a></li>
                <li><a href="index.php?page=incomingrelays">Incoming relays<span class="count"><?php echo $TotalRelays ?></span></a></li>
                <li><a href="index.php?page=mirror">Mirror</a></li>
                <li><a href="index.php?page=performance">Performance</a></li>
                <li><a href="index.php?page=servermessages">Server messages</a></li>
                <li><a href="index.php?page=ssltls">SSL/TLS</a></li>
                <li><a href="index.php?page=scripts">Scripts</a></li>
                <li><a href="index.php?page=tcpipports">TCP/IP ports</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="has-children utilities">
          <a href="#">Utilities</a>
          <ul>
            <li><a href="index.php?page=backup">Backup</a></li>
            <li><a href="index.php?page=diagnostics">Diagnostics</a></li>
          </ul>
        </li>
        <li class="help">
          <a href="https://www.hmailserver.com/documentation/latest/?page=overview" target="_blank">Documentation</a>
        </li>
      </ul>
      <ul>
        <li class="cd-label">Quick links</li>
        <li class="dns-blacklists <?php if ($page=='hm_dnsblacklists') echo 'active' ?>">
          <a href="index.php?page=dnsblacklists">DNS blacklists<span class="count"><?php echo $TotalBlacklists ?></span></a>
        </li>
        <li class="ip-ranges <?php if ($page=='hm_securityranges') echo 'active' ?>">
          <a href="index.php?page=securityranges">IP Ranges<span class="count"><?php echo $TotalIpRanges ?></span></a>
        </li>
        <li class="logs <?php if ($page=='hm_logviewer') echo 'active' ?>">
          <a href="index.php?page=logviewer">Log viewer</a>
        </li>
      </ul>
      <ul>
        <li class="cd-label">Action</li>
<?php
$serverstate = $obBaseApp->ServerState();
$action = hmailGetVar("action","");

if ($action == "control") {
	$controlaction = hmailGetVar("controlaction","");
	if ($controlaction == "1")
		$obBaseApp->Start();
	else if ($controlaction == "0")
		$obBaseApp->Stop();
}

switch($serverstate) {
	case 1:
	case 4:
		$controlaction = 1;
		$controlbutton = $obLanguage->String("Start");
		break;
	case 2:
	case 3:
		$controlaction = 0;
		$controlbutton = $obLanguage->String("Stop");
		break;
	default:
		$state = "Unknown";
		break;
}
?>
        <li class="action-btn"><form action="index.php" method="post" onSubmit="return formCheck(this);"><input type="submit" value="<?php echo $controlbutton?> server" /></form></li>
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

	echo '            <li class="has-children">
              <a href="?page=domain&action=edit&domainid=' . $obDomain->ID . '">' . $domainname . '</a>
              <ul>' . PHP_EOL;

	if ($current_domainid != $obDomain->ID && hmailGetAdminLevel() == ADMIN_SERVER) {
		// If the user is logged on as a system administrator, only show accounts
		// for the currently selected domain.
		echo '              </ul>' . PHP_EOL;
		return;
	}

	$Accounts  = $obDomain->Accounts();
	$TotalAccounts = $Accounts->Count();
	$accounts_root = $dtitem++;

	echo '                <li><a href="?page=accounts&domainid=' . $obDomain->ID . '">Accounts<span class="count">' . $TotalAccounts . '</a></li>' . PHP_EOL;

	/* hidden */
/*
	for ($j = 0; $j < $TotalAccounts; $j++) {
		$obAccount = $Accounts->Item($j);

		$accountaddress = $obAccount->Address;
		$accountaddress = PreprocessOutput($accountaddress);
		$accountaddress = str_replace("'", "\'", $accountaddress);

		$accountid = $obAccount->ID;

		$di = $dtitem++;
		$url = htmlentities("index.php?page=account&action=edit&accountid=" . $accountid . "&domainid=" . $obDomain->ID);
		$dtree .= "d.add($di,$accounts_root,'" . $accountaddress . "','$url','','','" . "images/user.png','" . "images/user.png');\r\n";

		// Only show sub-nodes for the currently selected account.
		if ($current_accountid == $accountid) {
			$dtree .= "d.add(" . $dtitem++ . ",$di,'" . GetStringForJavaScript("External accounts") . "','index.php?page=account_externalaccounts&accountid=" . $accountid . "&domainid=" . $obDomain->ID. "');\r\n";
		}
	}
*/
	$Aliases = $obDomain->Aliases();
	$TotalAliases = $Aliases->Count();
	$aliases_root = $dtitem++;

	echo '                <li><a href="?page=aliases&domainid=' . $obDomain->ID . '">Aliases<span class="count">' . $TotalAliases . '</a></li>' . PHP_EOL;

	/* hidden */
/*
	for ($j = 0; $j < $TotalAliases; $j++) {
		$obAlias = $Aliases->Item($j);

		$aliasname = $obAlias->Name;
		$aliasname = PreprocessOutput($aliasname);
		$aliasname = str_replace("'", "\'", $aliasname);

		$di = $dtitem++;
		$dtree .= "d.add($di,$aliases_root,'" . $aliasname . "','index.php?page=alias&action=edit&aliasid=" . $obAlias->ID . "&domainid=" . $obDomain->ID  . "','','','" . "images/arrow_switch.png','" . "images/arrow_switch.png');\r\n";
	}
*/
	$DistributionLists = $obDomain->DistributionLists();
	$TotalDistributionLists = $DistributionLists->Count();

	echo '                <li><a href="?page=distributionlists&domainid=' . $obDomain->ID . '">Distribution lists<span class="count">' . $TotalDistributionLists . '</a></li>' . PHP_EOL;

	/* hidden */
/*
	for ($j = 0; $j < $TotalDistributionLists; $j++) {
		$obDistributionList = $DistributionLists->Item($j);
		$di = $dtitem++;

		$address = PreprocessOutput($obDistributionList->Address);
		$address = str_replace("'", "\'", $address);

		$dtree .= "d.add($di,$dlist_root,'" . $address .  "','index.php?page=distributionlist&action=edit&distributionlistid=" . $obDistributionList->ID . "&domainid=" . $obDomain->ID . "','','','" . "images/arrow_out.png','" . "images/arrow_out.png');\r\n";
		$dtree .= "d.add(" . $dtitem++ .",$di,'" . GetStringForJavaScript("Members") . " (" . $obDistributionList->Recipients->Count() . ")','index.php?page=distributionlist_recipients&distributionlistid=" . $obDistributionList->ID . "&domainid=" . $obDomain->ID. "');\r\n";
	}
*/
	echo '              </ul>
            </li>' . PHP_EOL;
}

unset($obDomain);
unset($obAccount);
?>
