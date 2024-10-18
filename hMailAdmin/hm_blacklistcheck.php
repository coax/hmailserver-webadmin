<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // The user is not server administrator
?>
    <div class="box">
      <h2><?php EchoTranslation("Blacklist check") ?></h2>
      <form action="" class="form" id="blacklist-check" onsubmit="$(this).validation(blacklistCheck);">
        <p><?php EchoTranslation("Enter IP to check") ?></p>
        <input type="text" value="" name="ip" class="req ip">
        <div class="buttons"><button><?php EchoTranslation("Check"); ?></button></div>
      </form>
    </div>