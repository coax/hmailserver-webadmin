<?php
define("STSMTP", 1);
define("STPOP3", 3);
define("STIMAP", 5);

$obStatus = $obBaseApp->Status();
$ServerUptime = $obStatus->StartTime();
$ServerVersion = $obBaseApp->Version;

$MessagesProcessed = $obStatus->ProcessedMessages();
$MessagesVirus = $obStatus->RemovedViruses();
$MessagesSpam = $obStatus->RemovedSpamMessages();
$MessagesUndelivered = $obStatus->UndeliveredMessages();

$SessionsSmtp = $obStatus->SessionCount(STSMTP);
$SessionsPop3 = $obStatus->SessionCount(STPOP3);
$SessionsImap = $obStatus->SessionCount(STIMAP);
?>
    <div class="box">
      <h2><?php EchoTranslation("Server") ?></h2>
      <div id="activity" style="margin:15px auto; width:100%; height:180px;"></div>
      <div class="grey">
        <div><span><?php echo $state ?></span><br><?php EchoTranslation("status") ?></div>
        <div><span><time class="timeago" datetime="<?php echo $ServerUptime ?>"><?php echo $ServerUptime ?></time></span><br><?php EchoTranslation("server uptime") ?></div>
        <div><span><?php echo $ServerVersion ?></span><br><?php EchoTranslation("version") ?></div>
      </div>
    </div>

    <div class="box">
      <h2><?php EchoTranslation("Processed messages") ?></h2>
      <div id="processed" style="margin:30px auto; width:150px; height:150px;"></div>
      <div class="grey">
        <div><span id="legit"><?php echo $MessagesProcessed ?></span><br><?php EchoTranslation("Processed") ?></div>
        <div><span id="virus"><?php echo $MessagesVirus ?></span><br><?php EchoTranslation("Virus") ?></div>
        <div><span id="spam"><?php echo $MessagesSpam ?></span><br><?php EchoTranslation("Spam") ?></div>
      </div>
    </div>

    <div class="box">
      <h2><?php EchoTranslation("Open sessions") ?></h2>
      <div id="sessions" style="margin:30px auto; width:150px; height:150px;"></div>
      <div class="grey">
        <div><span id="smtp"><?php echo $SessionsSmtp ?></span><br>SMTP</div>
        <div><span id="pop3"><?php echo $SessionsPop3 ?></span><br>POP3</div>
        <div><span id="imap"><?php echo $SessionsImap ?></span><br>IMAP</div>
      </div>
    </div>

    <div class="box large">
      <h2><?php EchoTranslation("Delivery queue") ?></h2>
      <div style="margin:0 18px;">
        <table class="queue" style="width:99%;">
          <thead>
            <tr>
              <th>ID</th>
              <th><?php EchoTranslation("Created") ?></th>
              <th><?php EchoTranslation("From") ?></th>
              <th><?php EchoTranslation("To") ?></th>
              <th><?php EchoTranslation("Next try") ?></th>
              <th><?php EchoTranslation("Retries") ?></th>
            </tr>
          </thead>
        </table>
      </div>
      <div style="margin:0 18px; max-height:400px; overflow-y:scroll;">
        <table class="queue" id="queue">
          <tbody>
<?php
$QueueCount = 0;
if (strlen($MessagesUndelivered) > 0) {
	$list = explode("\r\n", $MessagesUndelivered);
	$QueueCount = count($list);

	foreach ($list as $line) {
		$columns = explode("\t", $line);

		if (count($columns)>4) {
			if ($columns[4] == "1901-01-01 00:00:00") $columns[4] = "ASAP";
			else $columns[4] = date_format(date_create($columns[4]), 'd.m.Y H:i:s');

			echo '            <tr>
              <td><a href="' . $columns[5] . '" rel="facebox">' . $columns[0] . '</a></td>
              <td>' . date_format(date_create($columns[1]), 'd.m.Y H:i:s') . '</td>
              <td>' . PreprocessOutput($columns[2]) . '</td>
              <td>' . PreprocessOutput($columns[3]) . '</td>
              <td>' . $columns[4] . '</td>
              <td>' . $columns[6] . '</td>
            </tr>' . PHP_EOL;
		}
	}
}
?>
          </tbody>
        </table>
      </div>
      <div class="grey">
        <div style="width:100%;"><span><?php echo $QueueCount ?></span><br><?php EchoTranslation("messages in queue") ?></div>
      </div>
    </div>