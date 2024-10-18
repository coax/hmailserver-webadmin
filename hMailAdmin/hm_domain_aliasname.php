<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$domainid = hmailGetVar("domainid", 0, true);
$action = hmailGetVar("action","");
?>
    <div class="box small">
      <h2><?php EchoTranslation("Alias") ?></h2>
      <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_domain_name_save");
PrintHidden("action", $action);
PrintHidden("domainid", $domainid);

PrintPropertyEditRow("aliasname", "Domain name", "", 80, " ");

PrintSaveButton();
?>
      </form>
    </div>