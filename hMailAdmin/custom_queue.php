<?php
define('IN_WEBADMIN', true);

require_once("./config.php");
require_once("./initialize.php");

define("STSMTP", 1);
define("STPOP3", 3);
define("STIMAP", 5);

$obStatus = $obBaseApp->Status();

$ProcessedMessages = $obStatus->ProcessedMessages();
$VirusMessages = $obStatus->RemovedViruses();
$SpamMessages = $obStatus->RemovedSpamMessages();
$q1 = '[' . $ProcessedMessages . ', ' . $VirusMessages . ', ' . $SpamMessages . ']';

$SessionsSmtp = $obStatus->SessionCount(STSMTP);
$SessionsPop3 = $obStatus->SessionCount(STPOP3);
$SessionsImap = $obStatus->SessionCount(STIMAP);
$q2 = '[' . $SessionsSmtp . ', ' . $SessionsPop3 . ', ' . $SessionsImap . ']';
$q4 = $SessionsSmtp + $SessionsPop3 + $SessionsImap;

$UndeliveredMessages = $obStatus->UndeliveredMessages();
$QueueCount = 0;
if (strlen($UndeliveredMessages) > 0) {
	$list = explode("\r\n", $UndeliveredMessages);
	$QueueCount = count($list);
	$as_soon_as_possible = Translate("ASAP");
	$q3 = '[';
	foreach ($list as $line) {
		$columns = explode("\t", $line);

		if (count($columns) > 4) {
			$columns[4] = makeIsoDate($columns[4]);
			if ($columns[4] <= "1970-01-01 01:00:00") $columns[4] = $as_soon_as_possible;

			//escape invalid characters
			$characters = array("\\", "{", "}");
			$replacements = array("/", "<", ">");
			$columns[5] = str_replace($characters, $replacements, $columns[5]);

  			if ($q3 != '[') $q3 .= ', ';
			$q3 .= '[' . $columns[0] . ', "' . makeIsoDate($columns[1]) . '", "' . PreprocessOutput($columns[2]) . '", "' . PreprocessOutput($columns[3]) . '", "' . $columns[4] . '", "' . $columns[5] . '", ' . $columns[7] . ']';
		}
	}
	$q3 .= ']';
} else $q3 = '[]';

$q5 = $QueueCount;

$obSettings = $obBaseApp->Settings();
$obLogging = $obSettings->Logging();

$livelog = $obLogging->LiveLog;

if($livelog){
	require_once("./include/log_functions.php");
	$loglines = explode(PHP_EOL, $livelog);
	foreach($loglines as $line){
		parse($line);
	}
	$livelog = events();
}
$q6 = json_encode($livelog);

header('Content-Type: application/json');
echo '[' . $q1 . ', ' . $q2 . ', ' . $q3 . ', ' . $q4 . ', ' . $q5 . ', ' . $q6 . ']';
?>