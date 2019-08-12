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
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_servermessage_save");
PrintHidden("messageid", "$messageid");
?>
        <p><?php EchoTranslation("Name") ?></p>
        <b><?php echo PreprocessOutput($messagename) ?></b>
<?php
PrintPropertyAreaRow("messagetext", "Message text", $messagetext, 10, 100);

PrintSaveButton(null, null, '?page=servermessages');
?>
      </form>
    </div>