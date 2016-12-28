<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

if (!file_exists("config.php")) {
	echo "Please rename config-dist.php to config.php. The file is found in the PHPWebAdmin root folder.";
	die;
}

define('IN_WEBADMIN', true);

require_once("config.php");
require_once("include/initialization_test.php");
require_once("initialize.php");

set_exception_handler("ExceptionHandler");
set_error_handler("ErrorHandler");

$page = hmailGetVar("page");
if ($page == "")
	$page = "frontpage";

$isbackground = (substr($page, 0,10) == "background");

if ($isbackground)
	$page = "$page.php";
else
	$page = "hm_$page.php";

// Check that the page really exists.
$page = stripslashes($page);
$page = basename($page, ".php");

if (!file_exists('./' . $page . '.php'))
	hmailHackingAttemp();

// If it's a background page, run here.
if ($isbackground) {
	include './' . $page . '.php';
	// Page is run, die now.
	die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
  <title>hMailServer: webadmin</title>
  <script type="text/javascript" src="include/formcheck.js"></script>
  <!--modern-->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="modern/js/modernizr.js"></script>
  <script type="text/javascript" src="modern/js/core.js"></script>
  <script type="text/javascript" src="modern/js/timeago.js"></script>
  <script type="text/javascript" src="modern/js/menu-aim.js"></script>
  <script type="text/javascript" src="modern/js/tablesort.js"></script>
  <script type="text/javascript" src="modern/js/facebox.js"></script>
  <script type="text/javascript" src="modern/js/chartist.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,700" rel="stylesheet">
  <link href="modern/css/reset.css" rel="stylesheet">
  <link href="modern/css/core.css" rel="stylesheet">
</head>

<body>
<?php
if (hmail_isloggedin()) {
?>
  <header class="cd-main-header">
    <a href="index.php" class="cd-logo"><span>hMailServer</span></a>
<!--
    <div class="cd-search is-hidden">
      <form action="#">
        <input type="search" placeholder="Search...">
      </form>
    </div>
-->
    <a href="#" class="cd-nav-trigger">Menu<span></span></a>
    <nav class="cd-nav">
      <ul class="cd-top-nav">
        <li class="has-children account">
          <a href="#">Administrator</a>
          <ul>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <main class="cd-main-content">
    <nav class="cd-side-nav">
      <ul>
<?php
include "include_treemenu.php";
?>
        <li class="cd-label">Main</li>
        <li class="status <?php if ($page=='hm_status') echo 'active' ?>"><a href="index.php?page=status">Dashboard</a></li>
        <li class="has-children domains <?php if ($page=='hm_domains') echo 'active' ?>">
          <a href="index.php?page=domains">Domains<span class="count"><?php echo $DomainsCount ?></span></a>
          <ul>
<?php
	for ($i = 1; $i <= $DomainsCount; $i++)
	{
		$obDomain = $obBaseApp->Domains[$i-1];
		$domain_root = $dtitem++;
		GetStringForDomain($obDomain,2);
	}
?>
          </ul>
        </li>
        <li class="rules <?php if ($page=='hm_rules') echo 'active' ?>">
          <a href="index.php?page=rules">Rules</a>
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
                <li><a href="index.php?page=smtp">SMTP</a></li>
                <li><a href="index.php?page=pop3">POP3</a></li>
                <li><a href="index.php?page=imap">IMAP</a></li>
              </ul>
            </li>
            <li class="has-children">
              <a href="index.php?page=smtp_antispam">Anti-spam</a>
              <ul>
                <li><a href="index.php?page=dnsblacklists">DNS blacklists</a></li>
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
                <li><a href="index.php?page=securityranges">IP Ranges<span class="count"><?php echo $IpRangesCount ?></span></a></li>
                <li><a href="index.php?page=incomingrelays">Incoming relays<span class="count"><?php echo $IncomingRelaysCount ?></span></a></li>
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
      </ul>
      <ul>
        <li class="cd-label">Quick links</li>
        <li class="dns-blacklists <?php if ($page=='hm_dnsblacklists') echo 'active' ?>"><a href="index.php?page=dnsblacklists">DNS blacklists</a></li>
        <li class="ip-ranges <?php if ($page=='hm_securityranges') echo 'active' ?>"><a href="index.php?page=securityranges">IP Ranges<span class="count"><?php echo $IpRangesCount ?></span></a></li>
      </ul>
      <ul>
        <li class="cd-label">Action</li>
<?php
define("STSMTP", 1);
define("STPOP3", 3);
define("STIMAP", 5);

$obStatus = $obBaseApp->Status();
$serverstate = $obBaseApp->ServerState();
$action = hmailGetVar("action","");

$statusstarttime = $obStatus->StartTime();
$statusprocessedmessages = $obStatus->ProcessedMessages();
$statusmessageswithvirus = $obStatus->RemovedViruses();
$statusmessageswithspam = $obStatus->RemovedSpamMessages();

$sessions_smtp = $obStatus->SessionCount(STSMTP);
$sessions_pop3 = $obStatus->SessionCount(STPOP3);
$sessions_imap = $obStatus->SessionCount(STIMAP);

if ($action == "control") {
	$controlaction = hmailGetVar("controlaction","");
	if ($controlaction == "1")
		$obBaseApp->Start();
	else if ($controlaction == "0")
		$obBaseApp->Stop();
}

switch($serverstate) {
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
      </ul>
      <a href="impressum.php" rel="facebox" class="impressum">WebAdmin 0.9 [beta]</a>
    </nav>

    <div class="content-wrapper">
<?php
	include './' . $page . '.php';
?>
    </div>
<?php
} else {
	include "hm_login.php";
}
?>

  </main>

</body>
</html>