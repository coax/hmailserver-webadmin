<?php
//ini_set('display_errors', 1);
define('IN_WEBADMIN', true);

require_once("./config.php");
require_once("./initialize.php");

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

if(!empty($_POST['LiveLogging'])){
	$obSettings = $obBaseApp->Settings();
	$obLogging = $obSettings->Logging();
	$_SESSION['livelogging'] = $_POST['LiveLogging'];
	if($_POST['LiveLogging']=='enabled'){
		$obLogging->EnableLiveLogging(true);
		EchoTranslation("Stop");
	} else {
		$obLogging->EnableLiveLogging(false);
		EchoTranslation("Start");
	}
	exit();
}

$Types = !empty($_POST['LogTypes']) ? $_POST['LogTypes'] : array('SMTPD');
$AllTypes = in_array('ALL', $Types);
$RawType = !empty($_POST['LogType']) ? true : false;
$Filter = !empty($_POST['LogFilter']) ? $_POST['LogFilter'] : null;
$Filename = !empty($_POST['LogFilename']) ? $_POST['LogFilename'] : date("Y-m-d");
$Filename = 'hmailserver_' . $Filename . '.log';
$Path = $obBaseApp->Settings->Directories->LogDirectory;
$Filename = $Path . '\\' . $Filename;

if (file_exists($Filename)) {
	$Filesize = filesize($Filename);
	$File = fopen($Filename, 'r');

	if ($File) {
		require_once("./include/log_functions.php");
		while (($Line = fgets($File)) !== false) {
			if ($RawType){
				if (!isset($events[0])) $events[0][0] = array('RAW');
				$events[0][1][] = htmlentities(cleanNonUTF8($Line));
				continue;
			}

			$Unfiltered = $Line;
			$Filtered = $AllTypes ? $Unfiltered :filter_result_type($Unfiltered, $Types);
			if (!is_null($Filter)) {
				$Filtered = filter_result($Filtered, $Filter, false);
				$Filtered = preg_replace("/\w*?$Filter\w*/i", "{em}$0{/em}", $Filtered);
			}

			if (!is_null($Filtered)) parse($Filtered);
		}
		fclose($File);
		$out = events();
	} else {
		$out = Translate("Error opening log file");
	}
} else {
	$out = Translate("Log file not found");
}

header('Content-Type: application/json');
$out = json_encode($out);
echo $out;