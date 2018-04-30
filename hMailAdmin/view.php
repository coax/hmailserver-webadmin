<?php
define('IN_WEBADMIN', true);

require_once("./config.php");
require_once("./include/initialization_test.php");
require_once("./initialize.php");

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$Filename = $_GET['q'];

//revert original characters
$characters = array("/", "<", ">");
$replacements = array("\\", "{", "}");
$Filename = str_replace($characters, $replacements, $Filename);

echo '<h2>' . basename($Filename) . '</h2>
  <div style="margin:0 18px 18px; width:800px; height:500px; overflow:hidden; overflow-y:scroll;">' . PHP_EOL;
$File = fopen($Filename, 'r');
if (!$File) {
	echo '<p class="warning">' . $obLanguage->String("Message no longer in queue.") . '</p>' . PHP_EOL;
} else {
	while ($Line = fgets($File)) {
		echo html_entity_decode($Line, ENT_COMPAT, 'UTF-8') . '<br>' . PHP_EOL;
	}
	fclose($File);
}
echo '</div>' . PHP_EOL;
?>