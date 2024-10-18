<?php
define('IN_WEBADMIN', true);

require_once("./config.php");
require_once("./include/initialization_test.php");
require_once("./initialize.php");

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

function dnsbllookup($ip) {
	// Add your preferred list of DNSBL's
	$dnsbl_lookup = [
		'dnsbl-1.uceprotect.net',
		'dnsbl-2.uceprotect.net',
		'dnsbl-3.uceprotect.net',
		'dnsbl.dronebl.org',
		'dnsbl.sorbs.net',
		'zen.spamhaus.org',
		'bl.spamcop.net',
		'list.dsbl.org',
		'sbl.spamhaus.org',
		'xbl.spamhaus.org'
	];
	$listed = '';
	if ($ip) {
		$reverse_ip = implode('.', array_reverse(explode('.', $ip)));
		foreach ($dnsbl_lookup as $host) {
			if (checkdnsrr($reverse_ip . '.' . $host . '.', 'A')) $listed .= $reverse_ip . '.' . $host . '<br>';
		}
	}
	if (empty($listed)) echo '<font class="green">' . Translate('Not listed') . '</font>';
	else echo '<font class="red">' . Translate('Listed') . ':</font><br>' . $listed;
}

echo '<p>';
if (isset($_GET['ip']) && $_GET['ip'] != null) {
	$ip = $_GET['ip'];
	if (filter_var($ip, FILTER_VALIDATE_IP)) {
		dnsbllookup($ip);
	} else {
		echo Translate('Enter a valid IP address');
	}
} else {
	echo Translate('Enter a valid IP address');
}
echo '</p>';
?>