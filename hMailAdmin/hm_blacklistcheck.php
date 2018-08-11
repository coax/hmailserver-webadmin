<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // The user is not server administrator
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Blacklist check") ?></h2>
      <form action="" method="get" class="form" id="blacklist-check">
        <p><?php EchoTranslation("Enter IP to check") ?></p>
        <input type="text" value="" name="ip" class="req ip">
        <div class="buttons bottom"><button><?php EchoTranslation("Check"); ?></button></div>
        <div id="results"><?php EchoTranslation("Results will be shown here"); ?></div>
      </form>
    </div>