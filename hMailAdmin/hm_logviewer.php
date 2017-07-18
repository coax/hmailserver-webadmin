<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();
?>
    <div class="box large">
      <h2><?php EchoTranslation("hMailServer log parser") ?></h2>
      <div style="margin:0 18px 18px;">
        <form action="" class="form" id="log-parser">
<?php
PrintHiddenCsrfToken();
?>
          <p><?php EchoTranslation("Date") ?>:</p>
          <input type="text" name="LogFilename" value="<?php echo date("Y-m-d") ?>" maxlength="10" data-toggle="datepicker" class="small">
          <p><?php EchoTranslation("Show logs only for") ?>:</p>
          <select name="LogType" class="small">
            <option value="SMTPD" selected><?php EchoTranslation("SMTP server (daemon)") ?></option>
            <option value="SMTPC"><?php EchoTranslation("SMTP client") ?></option>
            <option value="POP3D"><?php EchoTranslation("POP3") ?></option>
            <option value="POP3C"><?php EchoTranslation("POP3 fetch") ?></option>
            <option value="IMAPD"><?php EchoTranslation("IMAP") ?></option>
            <option value="DEBUG"><?php EchoTranslation("Debug") ?></option>
            <option value="TCPIP"><?php EchoTranslation("TCP/IP") ?></option>
            <option value="APPLICATION"><?php EchoTranslation("Application") ?></option>
            <option value="ERROR"><?php EchoTranslation("Errors") ?></option>
            <option value="ALL"><?php EchoTranslation("All") ?></option>
            <option value="RAW"><?php EchoTranslation("Unparsed (raw)") ?></option>
          </select>
          <p><?php EchoTranslation("Filter results by") ?>:</p>
          <input type="text" name="LogFilter" value="" maxlength="50" class="small">
          <div class="buttons"><input type="submit" value="<?php EchoTranslation("Parse log") ?>"></div>
          <p><?php EchoTranslation("Results") ?>:</p>
          <div id="results">
            <?php EchoTranslation("Click on \"Parse log\" button") ?>
          </div>
          <div class="buttons"><input type="button" value="<?php EchoTranslation("Clear results") ?>"></div>
        </form>
      </div>
    </div>