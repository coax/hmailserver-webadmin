<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obTCPIPPorts = $obSettings->TCPIPPorts;
$action = hmailGetVar("action","");
$Count = $obTCPIPPorts->Count();
?>
    <div class="box large">
      <h2><?php EchoTranslation("TCP/IP ports") ?> <span>(<?php echo $Count ?>)</span></h2>
      <div style="margin:0 18px 18px;">
        <table class="tablesort">
          <thead>
            <tr>
              <th><?php EchoTranslation("Protocol")?></th>
              <th style="width:45%;"><?php EchoTranslation("TCP/IP port")?></th>
              <th style="width:32px;" class="no-sort">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
$str_SMTP = $obLanguage->String("SMTP");
$str_POP3 = $obLanguage->String("POP3");
$str_IMAP = $obLanguage->String("IMAP");
$str_yes = $obLanguage->String("Yes");
$str_no = $obLanguage->String("No");
$str_delete = $obLanguage->String("Remove");
$str_confirm = $obLanguage->String("Confirm delete");

for ($i = 0; $i < $Count; $i++) {
	$obTCPIPPort = $obTCPIPPorts->Item($i);

	$portprotcol = $obTCPIPPort->Protocol;
	$portid = $obTCPIPPort->ID;
	$portnumber = $obTCPIPPort->PortNumber;

	$protocol_name = "";
	switch ($portprotcol) {
		case 1:
			$protocol_name = $str_SMTP;
			break;
		case 3:
			$protocol_name = $str_POP3;
			break;
		case 5:
			$protocol_name = $str_IMAP;
		break;
	}

	echo '            <tr>
              <td><a href="?page=tcpipport&action=edit&tcpipportid=' . $portid . '">' . $protocol_name . '</a></td>
              <td><a href="?page=tcpipport&action=edit&tcpipportid=' . $portid . '">' . $portnumber . '</a></td>
              <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . $protocol_name . ' ' . $portnumber . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_tcpipport_save&csrftoken=' . $csrftoken . '&action=delete&tcpipportid=' . $portid . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
            </tr>' . PHP_EOL;
}
?>
          </tbody>
        </table>
        <div class="buttons center"><a href="?page=tcpipport&action=add" class="button"><?php EchoTranslation("Add new port") ?></a></div>
      </div>
    </div>