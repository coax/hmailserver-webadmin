<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();
?>
    <div class="box large">
      <h2><?php EchoTranslation("hMailServer Log Parser") ?></h2>
      <div style="margin:0 18px 18px;">
        <form action="" class="cd-form" id="log-parser">
<?php
PrintHiddenCsrfToken();
?>
          <p><?php EchoTranslation("Date") ?>:</p>
          <input type="text" name="LogFilename" value="<?php echo date("Y-m-d") ?>" maxlength="10" data-toggle="datepicker" class="small">
          <p><?php EchoTranslation("Show logs only for") ?>:</p>
          <select name="LogType" class="small">
            <option value="SMTPD" selected>SMTP server (daemon)</option>
            <option value="SMTPC">SMTP client</option>
            <option value="POP3D">POP3</option>
            <option value="POP3C">POP3 fetch</option>
            <option value="IMAPD">IMAP</option>
            <option value="DEBUG">Debug</option>
            <option value="TCPIP">TCP/IP</option>
            <option value="APPLICATION">Application</option>
            <option value="ERROR">Errors</option>
            <option value="ALL">All</option>
            <option value="RAW">Unparsed (raw)</option>
          </select>
          <p><?php EchoTranslation("Filter results by") ?>:</p>
          <input type="text" name="LogFilter" value="" maxlength="50" class="small">
          <div class="buttons"><input type="submit" value="Parse log"></div>
          <p><?php EchoTranslation("Results") ?>:</p>
          <div id="results">
            <?php EchoTranslation("Click on \"Parse log\" button") ?>
          </div>
          <div class="buttons"><input type="button" value="Clear results"></div>
        </form>
      </div>
    </div>