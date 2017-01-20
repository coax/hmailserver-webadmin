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
  <title>hMailServer: webadmin</title>
  <!--modern-->
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="modern/js/modernizr.js"></script>
  <script type="text/javascript" src="modern/js/core.js"></script>
  <script type="text/javascript" src="modern/js/timeago.js"></script>
  <script type="text/javascript" src="modern/js/menu-aim.js"></script>
  <script type="text/javascript" src="modern/js/tablesort.js"></script>
  <script type="text/javascript" src="modern/js/facebox.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css" rel="stylesheet">
  <script type="text/javascript" src="modern/js/chartist-tooltip.js"></script>
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
      <a href="impressum.php" rel="facebox" class="impressum">WebAdmin 0.9.5 [beta]</a>
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