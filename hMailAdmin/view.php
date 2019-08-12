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
  <div style="width:800px; height:500px; overflow:hidden; overflow-y:scroll; white-space:nowrap;">' . PHP_EOL;

function htmlToPlainText($str){
	$str = str_replace('&nbsp;', ' ', $str);
	$str = html_entity_decode($str, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
	$str = html_entity_decode($str, ENT_HTML5, 'UTF-8');
	$str = html_entity_decode($str);
	$str = htmlspecialchars_decode($str);
	$str = strip_tags($str);

	return $str;
}

if (file_exists($Filename)) {
	$File = fopen($Filename, 'r');
	while ($Line = fgets($File)) {
		echo htmlToPlainText($Line) . '<br>' . PHP_EOL;
	}
	fclose($File);
} else
	echo '<p class="warning">' . Translate("Message no longer in queue.") . '</p>' . PHP_EOL;

echo '</div>' . PHP_EOL;
?>