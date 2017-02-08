<?php
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Content-Security-Policy: default-src \'none\'; script-src \'self\' \'unsafe-inline\'; connect-src \'self\'; img-src \'self\'; style-src \'self\' \'unsafe-inline\'; font-src \'self\' \'unsafe-inline\';');
header('X-XSS-Protection: 1; mode=block');

if (!file_exists("config.php")) {
	echo "Please rename config-dist.php to config.php. The file is found in the PHPWebAdmin root folder.";
	die;
}

define('IN_WEBADMIN', true);
define('CSRF_ENABLED', true);

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $isbackground) {
	validate_csrf_token_supplied();
}

// If it's a background page, run here.
if ($isbackground) {
	include './' . $page . '.php';
	// Page is run, die now.
	die;
}

$csrftoken = get_csrf_session_token();

//dynamic documentation link
$DocumentationLink = 'https://www.hmailserver.com/documentation/latest/?page=reference_' . hmailGetVar("page");

$username = isset($_SESSION['session_username'])?$_SESSION['session_username']:''; //moved from include_treemenu.php
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <title>hMailAdmin</title>
  <!--modern-->
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <script type="text/javascript" src="modern/js/jquery.js"></script>
  <script type="text/javascript" src="modern/js/modernizr.js"></script>
  <script type="text/javascript" src="modern/js/core.js"></script>
  <script type="text/javascript" src="modern/js/menu-aim.js"></script>
  <script type="text/javascript" src="modern/js/timeago.js"></script>
  <script type="text/javascript" src="modern/js/tablesort.js"></script>
  <script type="text/javascript" src="modern/js/facebox.js"></script>
  <script type="text/javascript" src="modern/js/chartist.js"></script>
  <link href="modern/css/chartist.css" rel="stylesheet">
  <script type="text/javascript" src="modern/js/chartist-tooltip.js"></script>
  <script type="text/javascript" src="modern/js/datepicker.js"></script>
  <link href="modern/css/datepicker.css" rel="stylesheet">
  <link href="modern/css/reset.css" rel="stylesheet">
  <link href="modern/css/core.css" rel="stylesheet">
</head>

<body>
<?php
if (hmail_isloggedin()) {
?>
  <header class="cd-main-header">
    <a href="index.php" class="cd-logo"><span>hMailServer</span></a>
<!-- not needed yet
    <div class="cd-search is-hidden">
      <form action="#">
        <input type="search" placeholder="Search...">
      </form>
    </div>
-->
    <a href="#" class="cd-nav-trigger"><?php EchoTranslation("Menu") ?><span></span></a>
    <nav class="cd-nav">
      <ul class="cd-top-nav">
        <li class="has-children account">
          <a href="#"><?php echo $username ?></a>
          <ul>
            <li><a href="logout.php"><?php EchoTranslation("Logout") ?></a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>
  <main class="cd-main-content">
    <nav class="cd-side-nav">
      <ul>
<?php
//build tree menu
include "include_treemenu.php";
?>
      </ul>
      <a href="modern/impressum.php" rel="facebox" class="impressum">hMailAdmin 1.0</a>
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