    <div class="box">
      <h2>Server</h2>
      <p class="info"><span class="green"><?php echo $state ?></span><br />status</p>
      <div class="grey">
        <p><span><time class="timeago" datetime="<?php echo $statusstarttime ?>"><?php echo $statusstarttime ?></time></span><br />server uptime</p>
        <p><span><?php echo $obBaseApp->Version ?></span><br />version</p>
        <p><a href="https://www.hmailserver.com/download" target="_blank">Check for updates</a></p>
      </div>
    </div>

    <div class="box">
      <h2>Processed messages</h2>
      <div id="processed" style="margin:30px auto; width:150px; height:150px;"></div>
      <div class="grey">
        <div><span id="legit"><?php echo ($statusprocessedmessages-$statusmessageswithvirus-$statusmessageswithspam) ?></span><br />Legit</div>
        <div><span id="virus"><?php echo $statusmessageswithvirus ?></span><br />Virus</div>
        <div><span id="spam"><?php echo $statusmessageswithspam ?></span><br />Spam</div>
      </div>
    </div>

    <div class="box">
      <h2>Open sessions</h2>
      <div id="sessions" style="margin:30px auto; width:150px; height:150px;"></div>
      <div class="grey">
        <div><span id="smtp"><?php echo $sessions_smtp ?></span><br />SMTP</div>
        <div><span id="pop3"><?php echo $sessions_pop3 ?></span><br />POP3</div>
        <div><span id="imap"><?php echo $sessions_imap ?></span><br />IMAP</div>
      </div>
    </div>

    <div class="box large">
      <h2>Delivery queue</h2>
      <div style="margin:0 18px;">
        <table class="queue" style="width:99%;">
          <thead>
            <tr>
              <th>ID</th>
              <th>Created</th>
              <th>From</th>
              <th>To</th>
              <th>Next try</th>
              <th>Retries</th>
            </tr>
          </thead>
        </table>
      </div>
      <div style="margin:0 18px 18px; max-height:400px; overflow-y:scroll;">
        <table class="queue" id="queue">
          <tbody>
<?php
$undeliveredMessages = $obStatus->UndeliveredMessages;

$QueueCount = 0;
if (strlen($undeliveredMessages) > 0) {
	$list = explode("\r\n", $undeliveredMessages);
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
      <p class="info"><span><?php echo $QueueCount ?></span><br />messages in queue</p>
    </div>