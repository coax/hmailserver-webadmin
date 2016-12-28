<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obTCPIPPorts = $obSettings->TCPIPPorts;
$action = hmailGetVar("action","");
?>
    <div class="box large">
      <h2><?php EchoTranslation("TCP/IP ports") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:50%;"><?php EchoTranslation("Protocol")?></th>
            <th style="width:45%;"><?php EchoTranslation("TCP/IP port")?></th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$Count = $obTCPIPPorts->Count();
$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++) {
	$obTCPIPPort = $obTCPIPPorts->Item($i);

	$portprotcol = $obTCPIPPort->Protocol;
	$portid = $obTCPIPPort->ID;
	$portnumber = $obTCPIPPort->PortNumber;

	$protocol_name = "";
	switch ($portprotcol) {
		case 1:
			$protocol_name = $obLanguage->String("SMTP");
			break;
		case 3:
			$protocol_name = $obLanguage->String("POP3");
			break;
		case 5:
			$protocol_name = $obLanguage->String("IMAP");
		break;
	}

	echo '          <tr>
            <td><a href="?page=tcpipport&action=edit&portid=' . $portid . '">' . $protocol_name . '</a></td>
            <td><a href="?page=tcpipport&action=edit&portid=' . $portid . '">' . $portnumber . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $protocol_name . '</b>:\',\'Yes\',\'?page=background_tcpipport_save&action=delete&portid=' . $portid . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=tcpipport&action=add" class="button">Add port</a></div>
      </div>
    </div>