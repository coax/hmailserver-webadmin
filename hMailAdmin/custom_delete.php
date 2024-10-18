<?php
define('IN_WEBADMIN', true);

require_once("./config.php");
require_once("./initialize.php");

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$message_id = $_GET['q'];

echo '<div style="margin:18px; text-align:center;">';
if (filter_var($message_id, FILTER_VALIDATE_INT) !== false) {
	$obGlobalObjects = $obBaseApp->GlobalObjects();
	$obDeliveryQueue = $obGlobalObjects->DeliveryQueue();
	$obDeliveryQueue->Remove($message_id);

	echo Translate("Message deleted from delivery queue.");
} else {
	echo Translate("Message no longer in queue.");
}
echo '</div>';
?>