<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$dmarcfilename = hmailGetVar("dfn", "");

if(file_exists($dmarcfilename)) unlink($dmarcfilename);

header("Location: index.php?page=dmarcreports");