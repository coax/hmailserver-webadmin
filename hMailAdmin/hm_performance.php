<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obCache = $obSettings->Cache();
$action = hmailGetVar("action","");

if($action == "save") {
	$obCache->Enabled = hmailGetVar("cacheenabled",0);
	$obCache->DomainCacheTTL = hmailGetVar("cachedomainttl",0);
	$obCache->AccountCacheTTL = hmailGetVar("cacheaccountttl",0);
	$obCache->AliasCacheTTL = hmailGetVar("cachealiasttl",0);
	$obCache->DistributionListCacheTTL = hmailGetVar("cachedistributionlistttl",0);
	$obSettings->TCPIPThreads = hmailGetVar("tcpipthreads", 0);
	$obSettings->MaxDeliveryThreads = hmailGetVar("maxdeliverythreads", 0);
	$obSettings->MaxAsynchronousThreads = hmailGetVar("MaxAsynchronousThreads", 0);
	$obSettings->WorkerThreadPriority = hmailGetVar("workerthreadpriority", 0);
	$obSettings->MessageIndexing->Enabled = hmailGetVar("MessageIndexingEnabled", 0);
} else if ($action == "ClearMessageIndexingCache") {
	$obSettings->MessageIndexing->Clear();
}

$cacheenabledchecked = $obCache->Enabled; //modified
$cachedomainttl = $obCache->DomainCacheTTL;
$cacheaccountttl = $obCache->AccountCacheTTL;
$cachedomainhitrate = $obCache->DomainHitRate;
$cacheaccounthitrate = $obCache->AccountHitRate;
$cachealiashitrate = $obCache->AliasHitRate;
$cachealiasttl = $obCache->AliasCacheTTL;
$cachedistributionlisthitrate = $obCache->DistributionListHitRate;
$cachedistributionlistttl = $obCache->DistributionListCacheTTL;
$tcpipthreads = $obSettings->TCPIPThreads;
$maxdeliverythreads = $obSettings->MaxDeliveryThreads;
$MaxAsynchronousThreads = $obSettings->MaxAsynchronousThreads;
$workerthreadpriority = $obSettings->WorkerThreadPriority;
$obMessageIndexingSettings = $obSettings->MessageIndexing;
$MessageIndexingEnabled = $obMessageIndexingSettings->Enabled;
$TotalMessageCount = $obMessageIndexingSettings->TotalMessageCount;
$TotalIndexedCount = $obMessageIndexingSettings->TotalIndexedCount;
?>
<script type="text/javascript">
function ClearMessageIndexingCache() {
	document.forms["mainform"].elements["action"].value = "ClearMessageIndexingCache";
	document.forms["mainform"].submit();
}
</script>
    <div class="box medium">
      <h2><?php EchoTranslation("Performance") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form" name="mainform">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "performance");
PrintHidden("action", "save");
?>
        <h3><?php EchoTranslation("Cache")?></h3>
<?php
PrintCheckboxRow("cacheenabled", "Enabled", $cacheenabledchecked);
?>
        <table>
          <tr>
            <th style="width:30%;"><?php EchoTranslation("Type")?></th>
            <th style="width:50%;"><?php EchoTranslation("Time to live (seconds)")?></th>
            <th style="width:20%;"><?php EchoTranslation("Hit rate")?></th>
          </tr>
          <tr>
            <td><?php EchoTranslation("Domain")?></td>
            <td><input type="text" name="cachedomainttl" value="<?php echo PreprocessOutput($cachedomainttl)?>" checkallownull="false" checktype="number" checkmessage="<?php EchoTranslation("Domain")?>" class="medium"></td>
            <td><?php echo $cachedomainhitrate?></td>
          </tr>
          <tr>
            <td><?php EchoTranslation("Account")?></td>
            <td><input type="text" name="cacheaccountttl" value="<?php echo PreprocessOutput($cacheaccountttl)?>" checkallownull="false" checktype="number" checkmessage="<?php EchoTranslation("Account")?>" class="medium"></td>
            <td><?php echo $cacheaccounthitrate?></td>
          </tr>
          <tr>
            <td><?php EchoTranslation("Alias")?></td>
            <td><input type="text" name="cachealiasttl" value="<?php echo PreprocessOutput($cachealiasttl)?>" checkallownull="false" checktype="number" checkmessage="<?php EchoTranslation("Alias")?>" class="medium"></td>
            <td><?php echo $cachealiashitrate?></td>
          </tr>
          <tr>
            <td><?php EchoTranslation("Distribution list")?></td>
            <td><input type="text" name="cachedistributionlistttl" value="<?php echo PreprocessOutput($cachedistributionlistttl)?>" checkallownull="false" checktype="number" checkmessage="<?php EchoTranslation("Distribution list")?>" class="medium"></td>
            <td><?php echo $cachedistributionlisthitrate?></td>
          </tr>
        </table>
        <h3><?php EchoTranslation("Threading")?></h3>
<?php
PrintPropertyEditRow("tcpipthreads", "Max number of command threads", $tcpipthreads, 25, "number", "small");
PrintPropertyEditRow("maxdeliverythreads", "Delivery threads", $maxdeliverythreads, 25, "number", "small");
PrintPropertyEditRow("MaxAsynchronousThreads", "Max number of asynchronous task threads", $MaxAsynchronousThreads, 4, "number", "small");
?>
        <p><?php EchoTranslation("Worker thread priority")?></p>
        <select name="workerthreadpriority" class="medium">
          <option value="2" <?php if ($workerthreadpriority == "2") echo "selected";?> >Highest</option>
          <option value="1" <?php if ($workerthreadpriority == "1") echo "selected";?> >Above normal</option>
          <option value="0" <?php if ($workerthreadpriority == "0") echo "selected";?> >Normal</option>
          <option value="-1" <?php if ($workerthreadpriority == "-1") echo "selected";?> >Below normal</option>
          <option value="-2" <?php if ($workerthreadpriority == "-2") echo "selected";?> >Lowest</option>
          <option value="-15" <?php if ($workerthreadpriority == "-15") echo "selected";?> >Idle</option>
        </select>
        <h3><?php EchoTranslation("Message indexing")?></h3>
<?php
PrintCheckboxRow("MessageIndexingEnabled", "Enabled", $MessageIndexingEnabled);
?>
        <p><?php EchoTranslation("Status")?></p>
        <?php echo $TotalIndexedCount . " / ". $TotalMessageCount;?>
        <p><a href="#" onclick="ClearMessageIndexingCache();" class="button"><?php EchoTranslation("Clear")?></a></p>
<?php
PrintSaveButton();
?>
      </form>
    </div>