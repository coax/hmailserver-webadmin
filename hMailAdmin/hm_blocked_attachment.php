<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp(); // Only server can change these settings.

$action = hmailGetVar("action","");
$id = hmailGetVar("id","");

$wildcard = "";
$description = "";

if ($action == "edit") {
	$obSettings = $obBaseApp->Settings();
	$obAntivirus	= $obSettings->AntiVirus();
	$blockedAttachment = $obAntivirus->BlockedAttachments->ItemByDBID($id);

	$wildcard = $blockedAttachment->Wildcard;
	$description = $blockedAttachment->Description;
}
?>
    <div class="box">
      <h2><?php EchoTranslation("Blocked attachment") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_blocked_attachment_save");
PrintHidden("action", $action);
PrintHidden("id", $id);

PrintPropertyEditRow("wildcard", "Wildcard", $wildcard, 80, " ");
PrintPropertyAreaRow("description", "Description", $description, 4);

PrintSaveButton(null, null, '?page=smtp_antivirus');
?>
      </form>
    </div>