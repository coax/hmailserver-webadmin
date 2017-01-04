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
		$statusprocessedmessages = $obStatus->ProcessedMessages();
		$statusmessageswithvirus = $obStatus->RemovedViruses();
		$statusmessageswithspam = $obStatus->RemovedSpamMessages();
		header('Content-Type: application/json');
		echo '[' . ($statusprocessedmessages-$statusmessageswithvirus-$statusmessageswithspam) . ', ' . $statusmessageswithvirus . ', ' . $statusmessageswithspam . ']';
		break;

	case 2:
		$sessions_smtp = $obStatus->SessionCount(STSMTP);
		$sessions_pop3 = $obStatus->SessionCount(STPOP3);
		$sessions_imap = $obStatus->SessionCount(STIMAP);
		header('Content-Type: application/json');
		echo '[' . $sessions_smtp . ', ' . $sessions_pop3 . ', ' . $sessions_imap . ']';
		break;

	case 3:
		$undeliveredMessages = $obStatus->UndeliveredMessages;

		$QueueCount = 0;
		if (strlen($undeliveredMessages) > 0) {
			$list = explode("\r\n", $undeliveredMessages);
			$QueueCount = count($list);
			$as_soon_as_possible = $obLanguage->String("As soon as possible");

			foreach ($list as $line) {
				$columns = explode("\t", $line);

				if (count($columns) > 4) {
					if ($columns[4] == "1901-01-01 00:00:00")
					$columns[4] = $as_soon_as_possible;

					echo '          <tr>
            <td><a href="' . $columns[5] . '" rel="facebox">' . $columns[0] . '</a></td>
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
	//reserved for future
}
?>