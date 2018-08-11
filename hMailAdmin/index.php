<?php
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Content-Security-Policy: default-src \'none\'; script-src \'self\' \'unsafe-inline\'; connect-src \'self\'; img-src \'self\'; style-src \'self\' \'unsafe-inline\'; font-src \'self\' \'unsafe-inline\';');
header('X-XSS-Protection: 1; mode=block');

if (!file_exists("config.php")) {
	echo "Please rename config-dist.php to config.php. The file is found in the hMailAdmin root folder.";
	die;
}

define('IN_WEBADMIN', true);
define('CSRF_ENABLED', true);

require_once("config.php");
require_once("include/initialization_test.php");
require_once("initialize.php");

$hmail_config['version']=1.4;

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

//Check that the page really exists
$page = stripslashes($page);
$page = basename($page, ".php");

if (!file_exists('./' . $page . '.php'))
	hmailHackingAttemp();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $isbackground) {
	validate_csrf_token_supplied();
}

//If it's a background page, run here
if ($isbackground) {
	include './' . $page . '.php';
	//Page is run, die now
	die;
}

$csrftoken = get_csrf_session_token();

//Dynamic documentation link
$DocumentationLink = 'https://www.hmailserver.com/documentation/latest/?page=reference_' . hmailGetVar("page");

$username = isset($_SESSION['session_username'])?$_SESSION['session_username']:'';
?>
<!DOCTYPE html>
<html>
<head>
  <title>hMailAdmin</title>
  <meta charset="utf-8">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico">
  <script>var hmail_config = {weekStart:<?php echo $hmail_config['datepicker_weekStart'] ?>};</script>
  <script src="js/jquery.js"></script>
  <script src="js/modernizr.js"></script>
  <script src="js/core.js"></script>
  <script src="js/timeago.js"></script>
<?php if ($hmail_config['defaultlanguage'] != 'english') echo '<script src="js/timeago.' . $hmail_config['defaultlanguage'] . '.js"></script>'; ?>
  <script src="js/tablesort.js"></script>
  <script src="js/facebox.js"></script>
  <script src="js/chartist.js"></script>
  <link href="css/chartist.css" rel="stylesheet">
  <script src="js/chartist-tooltip.js"></script>
  <script src="js/datepicker.js"></script>
  <script src="js/autosize.js"></script>
<?php if ($hmail_config['defaultlanguage'] != 'english') echo '<script src="js/datepicker.' . $hmail_config['defaultlanguage'] . '.js"></script>'; ?>
  <link rel="stylesheet" href="css/datepicker.css">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/core.css">
</head>

<body>
<?php
if (hmail_isloggedin()) {
?>
  <header>
    <a href="index.php" class="logo"><span>hMailServer</span></a>
    <nav>
      <ul id="top">
        <li class="has-children account">
          <a href="#"><?php echo $username ?></a>
          <ul>
            <li><a href="logout.php"><?php EchoTranslation("Logout") ?></a></li>
          </ul>
        </li>
      </ul>
    </nav>
    <a href="#" id="mobile"><?php EchoTranslation("Menu") ?><span></span></a>
  </header>
  <main>
    <div id="sidebar">
      <ul>
<?php
	include "include_treemenu.php";
?>
      </ul>
      <a href="./impressum.php" rel="facebox" class="impressum"><?php

	if (hmailGetAdminLevel() == 2) {
		if(!isset($_SESSION['version']))$_SESSION['version'] = Version();
		$version = $_SESSION['version'];
		if ($hmail_config['version'] < $version)
			echo '<span class="warning" style="width:62%; margin:auto;">hMailAdmin ' . $version . ' available</span>';
		else
			echo 'About hMailAdmin ' . $hmail_config['version'];
	}
	else
		echo 'About hMailAdmin ' . $hmail_config['version'];
?></a>
    </div>
    <div id="content">
<?php
	include './' . $page . '.php';
?>
    </div>
  </main>
<?php
} else {
	include "hm_login.php";
}
?>

</body>
</html>