<?php
if (!defined('IN_WEBADMIN'))
	exit();

$version = explode('.', phpversion());
if ((int) $version[0] < 5) {
	echo '<p>hMailServer WebAdmin runs on PHP 5 or higher.</p>' . PHP_EOL;
	die;
}
// Make sure that settings in config.php are specified.
if ($hmail_config['rooturl'] === "CHANGE-ME") {
	echo '<p>Please update config.php to match your system.</p>' . PHP_EOL;
	die;
}
if (!isset($hmail_config['rule_editing_level'])) {
	echo '<p>The config.php file which is in use is not compatible with this version of WebAdmin.</p>' . PHP_EOL;
	echo '<p>To resolve this, please use the config.php which comes with this version of WebAdmin.</p>' . PHP_EOL;
	die;
}

// Create "logs" folder with subfolders if doesn't exist
$logsDir = './logs/';
$dmarcDir = './logs/dmarc/';
$tlsDir = './logs/tls/';
if (!file_exists($logsDir)) {
	mkdir($logsDir, 0777, true);
}
if (!file_exists($dmarcDir)) {
	mkdir($dmarcDir, 0777, true);
}
if (!file_exists($tlsDir)) {
	mkdir($tlsDir, 0777, true);
}
?>