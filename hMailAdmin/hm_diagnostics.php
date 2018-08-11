<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obDiagnostics	= $obBaseApp->Diagnostics();
$action = hmailGetVar("action", "");
$resultString = "";

if($action == "performTests") {
	$obDiagnostics->LocalDomainName = hmailGetVar("LocalDomainName", "");
	$obResults = $obDiagnostics->PerformTests();
	$resultString = "";

	for ($i = 0; $i < $obResults->Count; $i++) {
		$obResult = $obResults->Item($i);

		if ($obResult->Result) {
			$resultString = $resultString . '<font color="green">' . $obResult->Name . '</font><br>';
		} else {
			$resultString = $resultString . '<font color="red">' . $obResult->Name . '</font><br>';
		}

		$details = $obResult->ExecutionDetails;
		$detailsRows = explode("\r\n", $details);

		for ($subRow = 0 ; $subRow < sizeof($detailsRows) ; $subRow++) {
			if (strpos($detailsRows[$subRow], "ERROR:") === 0) {
				$resultString .= '<font color="red">';
				$resultString .= PreprocessOutput($detailsRows[$subRow]) . '<br>';
				$resultString .= '</font>';
			} else {
				$resultString .= PreprocessOutput($detailsRows[$subRow]) . '<br>';
			}
		}
		$resultString .= "<br>";
	}
	$resultString .= "<br><br>";
}
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Diagnostics") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "diagnostics");
PrintHidden("action", "performTests");
?>
        <p class="warning bottom"><?php EchoTranslation("This tool lets you perform diagnostics on your hMailServer installation."); ?></p>
        <p><?php EchoTranslation("Select the domain you want to run tests on."); ?></p>
        <select name="LocalDomainName">
<?php
$obDomains = $obBaseApp->Domains;
for ($i = 0; $i < $obDomains->Count; $i++) {
	$domain = $obDomains[$i];
	echo '          <option value="' . $domain->Name . '">' . $domain->Name .  '</option>';
}
?>
        </select>
        <p>
<?php
if (strlen($resultString) > 0) {
	echo "<p>";
	echo $resultString;
	echo "</p>";
}
?>
        </p>
<?php
PrintSaveButton("Start");
?>
      </form>
    </div>