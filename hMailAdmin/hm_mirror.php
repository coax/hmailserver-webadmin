<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$action = hmailGetVar("action","");

if($action == "save")
	$obSettings->MirrorEMailAddress= hmailGetVar("mirroremailaddress",0);
	$mirroremailaddress = $obSettings->MirrorEMailAddress;
?>
    <div class="box">
      <h2><?php EchoTranslation("Mirror") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
        <div class="warning">A copy of all e-mails sent on this server, including both incoming and outgoing messages, will be sent to the e-mail address entered as mirror-address below.</div>
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "mirror");
PrintHidden("action", "save");

PrintPropertyEditRow("mirroremailaddress", "Mirror e-mail address", $mirroremailaddress, 40, "email");

PrintSaveButton();
?>
      </form>
    </div>