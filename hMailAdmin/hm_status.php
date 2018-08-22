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
      <h2><?php EchoTranslation("Current sessions") ?></h2>
      <div id="sessions" style="margin:30px auto; width:150px; height:150px;"></div>
      <div class="grey">
        <div><span id="smtp"><?php echo $SessionsSmtp ?></span><br>SMTP</div>
        <div><span id="pop3"><?php echo $SessionsPop3 ?></span><br>POP3</div>
        <div><span id="imap"><?php echo $SessionsImap ?></span><br>IMAP</div>
      </div>
    </div>
    <div class="box large">
      <h2><?php EchoTranslation("Delivery queue") ?> <span>(<span id="count">0</span>)</span></h2>
      <div style="position:relative; max-height:455px; overflow-y:auto;">
        <table id="queue">
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
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <div class="box large">
      <h2><?php EchoTranslation("Live logging") ?></h2>
      <div id="live-logging">
        <div id="results" style="display:none; height:300px;"></div>
		<?php $state = isset($_SESSION['livelogging']) && $_SESSION['livelogging'] == 'enabled' ? 'enabled' : 'disabled' ?>
        <button data-state="<?php echo $state ?>"><?php EchoTranslation(($state=='enabled' ? "Stop" : "Start")) ?></button><div style="display:none; margin-left:18px;"><input type="checkbox" name="autoscroll" id="autoscroll" value="1"><label for="autoscroll"><?php EchoTranslation("Autoscrolling") ?></label></div>
      </div>
    </div>