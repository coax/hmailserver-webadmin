<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // The user is not server administrator

$messageid = hmailGetVar("messageid",0);
$obServerMessage = $obBaseApp->Settings->ServerMessages->ItemByDBID($messageid);
$messagename = $obServerMessage->Name;
$messagetext = $obServerMessage->Text;
?>
    <div class="box medium">
      <h2><?php EchoTranslation("Server message") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "background_servermessage_save");
	PrintHidden("messageid", "$messageid");
?>
        <p><?php EchoTranslation("Name")?></p>
        <?php echo PreprocessOutput($messagename)?>
<?php
	PrintPropertyAreaRow("messagetext", "Message text", $messagetext, 20, 100);

	PrintSaveButton();
?>
      </form>
    </div>