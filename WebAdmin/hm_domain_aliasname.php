<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$domainid = hmailGetVar("domainid",0);
$action = hmailGetVar("action","");
?>
    <div class="box">
      <h2><?php EchoTranslation("Alias") ?></h2>
      <form action="index.php" method="post" onsubmit="return $(this).validation();" class="cd-form">
<?php
	PrintHidden("page", "background_domain_name_save");
	PrintHidden("action", $action);
	PrintHidden("domainid", $domainid);

	PrintPropertyEditRow("aliasname", "Domain name", "", 100, "req");

	PrintSaveButton();
?>
      </form>
    </div>