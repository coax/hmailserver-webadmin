<?php
switch (hmailGetAdminLevel()){
	case 0:
		echo '<h2>' . Translate("Account") . ' ' . $accountaddress . '</h2><p>' . Translate("Please select option from left menu.") . '</p>';
		break;
	case 1:
		echo '<h2>' . Translate("Domain") . ' ' . $domainname . '</h2><p>' . Translate("Please select option from left menu.") . '</p>';
		break;
	case 2:
		include 'hm_status.php';
}
?>