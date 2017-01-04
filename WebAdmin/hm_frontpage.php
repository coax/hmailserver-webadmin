<?php
switch (hmailGetAdminLevel()){
	case 0:
		echo "<h2>Account $accountaddress</h2>";
		break;
	case 1:
		echo "<h2>Domain $domainname</h2>";
		break;
	case 2:
		include 'hm_status.php';
}
?>