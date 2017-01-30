<?php
define('IN_WEBADMIN', true);

require_once("../config.php");
require_once("../initialize.php");

define("STSMTP", 1);
define("STPOP3", 3);
define("STIMAP", 5);

$obStatus = $obBaseApp->Status();

switch ($_GET['q']){
	case 1:
		$ProcessedMessages = $obStatus->ProcessedMessages();
		$VirusMessages = $obStatus->RemovedViruses();
		$SpamMessages = $obStatus->RemovedSpamMessages();

		header('Content-Type: application/json');
		echo '[' . $ProcessedMessages . ', ' . $VirusMessages . ', ' . $SpamMessages . ']';
		break;

	case 2:
		$SessionsSmtp = $obStatus->SessionCount(STSMTP);
		$SessionsPop3 = $obStatus->SessionCount(STPOP3);
		$SessionsImap = $obStatus->SessionCount(STIMAP);
		header('Content-Type: application/json');
		echo '[' . $SessionsSmtp . ', ' . $SessionsPop3 . ', ' . $SessionsImap . ']';
		break;

	case 3:
		$UndeliveredMessages = $obStatus->UndeliveredMessages();
		$QueueCount = 0;
		if (strlen($UndeliveredMessages) > 0) {
			$list = explode("\r\n", $UndeliveredMessages);
			$QueueCount = count($list);
			$as_soon_as_possible = $obLanguage->String("As soon as possible");

			foreach ($list as $line) {
				$columns = explode("\t", $line);

				if (count($columns) > 4) {
					if ($columns[4] == "1901-01-01 00:00:00")
					$columns[4] = $as_soon_as_possible;

					//escape invalid characters
					$characters = array("\\", "{", "}");
					$replacements = array("/", "[", "]");
					$columns[5] = str_replace($characters, $replacements, $columns[5]);

					echo '          <tr>
            <td><a href="#" onclick="$.facebox({ajax:\'modern/view.php?q=' . $columns[5] . '\'}); return false;">' . $columns[0] . '</a></td>
            <td>' . $columns[1] . '</td>
            <td>' . PreprocessOutput($columns[2]) . '</td>
            <td>' . PreprocessOutput($columns[3]) . '</td>
            <td>' . $columns[4] . '</td>
            <td>' . $columns[6] . '</td>
          </tr>' . PHP_EOL;
				}
			}
		}
		break;
	case 4:
		$SessionsSmtp = $obStatus->SessionCount(STSMTP);
		$SessionsPop3 = $obStatus->SessionCount(STPOP3);
		$SessionsImap = $obStatus->SessionCount(STIMAP);
		echo $SessionsSmtp + $SessionsPop3 + $SessionsImap;
}
?>