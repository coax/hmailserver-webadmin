<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();
?>
    <div class="box large">
      <h2><?php EchoTranslation("hMailServer log parser") ?></h2>
      <form action="" class="form" id="log-parser">
<?php
PrintHiddenCsrfToken();
?>
        <p><?php EchoTranslation("Date") ?>:</p>
        <input type="text" name="LogFilename" value="<?php echo date("Y-m-d") ?>" maxlength="10" data-toggle="datepicker" class="small">
        <p><?php EchoTranslation("Filter results by") ?>:</p>
        <input type="text" name="LogFilter" value="" maxlength="50" class="small">
        <p><?php EchoTranslation("Show logs only for") ?>:</p>
        <p><input type="checkbox" name="LogTypes[]" value="ALL" id="checkAll"><label for="checkAll"><?php EchoTranslation("All") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="SMTPD" checked id="LogType_0"><label for="LogType_0"><?php EchoTranslation("SMTP server (daemon)") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="SMTPC" id="LogType_1"><label for="LogType_1"><?php EchoTranslation("SMTP client") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="POP3D" id="LogType_2"><label for="LogType_2"><?php EchoTranslation("POP3") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="POP3C" id="LogType_3"><label for="LogType_3"><?php EchoTranslation("POP3 fetch") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="IMAPD" id="LogType_4"><label for="LogType_4"><?php EchoTranslation("IMAP") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="DEBUG" id="LogType_5"><label for="LogType_5"><?php EchoTranslation("Debug") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="TCPIP" id="LogType_6"><label for="LogType_6"><?php EchoTranslation("TCP/IP") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="APPLICATION" id="LogType_7"><label for="LogType_7"><?php EchoTranslation("Application") ?></label></p>
        <p><input type="checkbox" name="LogTypes[]" value="ERROR" id="LogType_8"><label for="LogType_8"><?php EchoTranslation("Errors") ?></label></p>
        <p><input type="checkbox" name="LogType" value="RAW" id="checkRaw"><label for="checkRaw"><?php EchoTranslation("Unparsed (raw)") ?></label></p>

        <div class="buttons"><button><?php EchoTranslation("Parse log") ?></button> &nbsp; <a href="#" class="button" id="clear"><?php EchoTranslation("Clear results") ?></a></div>
      </form>
<script>
$("input[name='LogTypes[]']").click(function() {
	if(this.checked) $("#checkRaw").prop('checked', false);
else $("#checkAll").prop('checked', false);
});
$("#checkAll").click(function() {
	$("input[name='LogTypes[]']").prop('checked', this.checked);
	if(this.checked) $("#checkRaw").prop('checked', false);
});
$("#checkRaw").click(function() {
	if(this.checked) $("input[name='LogTypes[]']").prop('checked', false);
});
</script>
    </div>