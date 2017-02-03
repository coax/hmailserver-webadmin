<?php
//ini_set('display_errors', 1);
define('IN_WEBADMIN', true);

require_once("../config.php");
require_once("../include/initialization_test.php");
require_once("../initialize.php");

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

function filter_result($str, $findme, $type=true) {
	if ( ($pos = stripos($str, $findme)) !== false && (!$type || $pos < 3)) {
		return $str;
	}
}

$Type = !empty($_POST['LogType']) ? $_POST['LogType'] : 'SMTPD';
$Filter = !empty($_POST['LogFilter']) ? $_POST['LogFilter'] : null;
$Filename = !empty($_POST['LogFilename']) ? $_POST['LogFilename'] : date("Y-m-d");
$Filename = 'hmailserver_' . $Filename . '.log';
$Path = $obBaseApp->Settings->Directories->LogDirectory;
$Filename = $Path . '\\' . $Filename;

if (file_exists($Filename)) {
	$Filesize = filesize($Filename);
	$File = fopen($Filename, 'r');

	if ($File) {
		$events=array();
		while (($Line = fgets($File)) !== false) {
			if($Type == 'RAW'){
				$events[0]['type'] = 'RAW';
				$events[0]['data'][] = $Line;
				continue;
			}

			$Unfiltered = $Line;
			$Filtered = $Type == 'ALL' ? $Unfiltered : filter_result($Unfiltered, $Type, true);
			if (!is_null($Filter)) {
				$Filtered = filter_result($Filtered, $Filter, false);
				$Filtered = preg_replace("/\w*?$Filter\w*/i", "{em}$0{/em}", $Filtered);
			}

			if (!is_null($Filtered)) prase($Filtered);
			//echo $Filtered;
		}
		fclose($File);
		echo events();
	} else {
		echo 'Error opening log file.';
	}
} else echo 'Log file not found.';

function prase($line){
	global $events;
	$line = cleanString($line);
	$data = explode("\t", $line);
	switch($data[0]){
		case 'SMTPD':
		case 'SMTPC':
			prase_smtp($data);
			break;
		case 'POP3D':
		case 'POP3C':
		case 'IMAPD':
			prase_imap($data);
			break;
		case 'TCPIP':
		case 'DEBUG':
		case 'APPLICATION':
		case 'ERROR':
			prase_error($data);
	}
}

$datastore = array();
function prase_smtp($data){
	global $datastore,$events;

	if (!isset($events[$data[0].$data[2]]['type'])) $events[$data[0].$data[2]]['type'] = $data[0];
	if (!isset($events[$data[0].$data[2]]['id'])) $events[$data[0].$data[2]]['id'] = $data[2];
	if (!isset($events[$data[0].$data[2]]['ip'])) $events[$data[0].$data[2]]['ip'] = $data[4];

	// AUTH LOGIN decoder.
	// First we get a RECEIVED: AUTH LOGIN
	// The next RECEIVED: line contains login username which is e-mail address, base64 encoded.
	if (isset($datastore[$data[0] . $data[2]]) && strpos($data[5],'RECEIVED: ') !== false) {
		// We got it.
		$base64 = substr($data[5] ,strrpos($data[5]," ") + 1, strlen($data[5]));
		$data[5] = 'RECEIVED: ' . base64_decode($base64);
		unset($datastore[$data[0] . $data[2]]);
	} else if (strpos($data[5],'RECEIVED: AUTH LOGIN') !== false) {
		// Wait for it.
		$datastore[$data[0] . $data[2]] = true;
	}

	$events[$data[0].$data[2]]['data'][] = $data;
}

function prase_imap($data){
	global $events;

	if (!isset($events[$data[0].$data[2]]['type'])) $events[$data[0].$data[2]]['type'] = $data[0];
	if (!isset($events[$data[0].$data[2]]['id'])) $events[$data[0].$data[2]]['id'] = $data[2];
	if (!isset($events[$data[0].$data[2]]['ip'])) $events[$data[0].$data[2]]['ip'] = $data[4];

	$events[$data[0].$data[2]]['data'][] = $data;
}

function prase_error($data){
	global $events;

	if (!isset($events[$data[0].$data[2]]['type'])) $events[$data[0].$data[2]]['type'] = $data[0];
	if (!isset($events[$data[0].$data[2]]['id'])) $events[$data[0].$data[2]]['id'] = $data[2];

	$events[$data[0].$data[2]]['data'][] = $data;
}

function events(){
	global $events;
	$out = '';
	foreach ($events as $data) {
		switch($data['type']){
			case 'SMTPD':
			case 'SMTPC':
			case 'IMAPD':
			case 'POP3D':
			case 'POP3C':
				$out .= out_smtp($data);
				break;
			case 'RAW':
				$out .= out_raw($data);
				break;
			case 'TCPIP':
			case 'DEBUG':
			case 'APPLICATION':
			case 'ERROR':
				$out .= out_error($data);
		}
	}
	if (empty($out)) $out = 'No entries in log.';
	return $out;
}

function out_smtp($data){
	$out = '<div id="group-' . $data['type'] . '-' . $data['id'] . '"><span>' . $data['type'] . ' &nbsp;&ndash;&nbsp; ' . $data['id'] . ' &nbsp;&ndash;&nbsp; ' . $data['ip'] . ' <sup><a href="https://href.li/?http://ip-api.com/line/' . $data['ip'] . '" target="_blank">?</a></sup></span><ul>';
	foreach ($data['data'] as $col) {
		$out .= '<li><div>' . $col[3] . '</div><div>' . $col[5] . '</div></li>';
	}
	$out .= '</ul></div>';
	return $out;
}

function out_error($data){
	$out = '<div id="group-' . $data['type'] . '-' . $data['id'] . '"><span>' . $data['type'] . '</span><ul>';
	foreach ($data['data'] as $col) {
		$out .= '<li><div>' . $col[2] . '</div><div>' . $col[3] . '</div></li>';
	}
	$out .= '</ul></div>';
	return $out;
}

function out_raw($data){
	$out = '';
	foreach ($data['data'] as $col) {
		$out .= htmlentities($col, ENT_COMPAT, 'UTF-8') . '<br>';
	}
	return $out;
}

function cleanString($str) {
	$search = array('"','<','>','[nl]','{em}','{/em}');
	$replace = array('','&lt;','&gt;','<br>','<em>','</em>');
	return str_replace($search,$replace,$str);
}
?>