<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp();

$tlsfilename = hmailGetVar("tls", "");

if(file_exists($tlsfilename)) unlink($tlsfilename);

header("Location: index.php?page=tlsreports");