<?php
if (!defined('IN_WEBADMIN'))
	die;

require_once("include/functions.php");
require_once("include_versioncheck.php");

session_start();

// Enable CSRF protection
ensure_csrf_session_token_exists();

// Connect to hMailServer
try {
	$obBaseApp = new COM("hMailServer.Application", NULL, CP_UTF8);
}
catch(Exception $e) {
	echo '<p>' . $e->getMessage() . '</p>' . PHP_EOL;
	echo '<p>This problem is often caused by DCOM permissions not being set.' . PHP_EOL;
	die;
}

if ($obBaseApp->Version != REQUIRED_VERSION) {
	echo '<p>The hMailServer version does not match the WebAdmin version.</p>' . PHP_EOL;
	echo '<p>hMailServer version: ' . $obBaseApp->Version . '</p>' . PHP_EOL;
	echo '<p>WebAdmin version: ' . REQUIRED_VERSION . '</p>' . PHP_EOL;
	echo '<p>Don\'t worry, just edit include_versioncheck.php edit version to match your own.</p>' . PHP_EOL;
	die;
}

try {
	$obBaseApp->Connect();

	if (isset($_SESSION['session_username']) && isset($_SESSION['session_password'])) {
		// Authenticate the user
		$obBaseApp->Authenticate($_SESSION['session_username'], $_SESSION['session_password']);
	}
}
catch(Exception $e) {
	echo $e->getMessage();
	die;
}

$obLanguage = new translate($obBaseApp->GlobalObjects->Languages->ItemByName($hmail_config['defaultlanguage']),$hmail_config['defaultlanguage']);
?>