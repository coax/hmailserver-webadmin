<?php
if (!isset($errstr) || !isset($errfile))
	die;

if (!defined('IN_WEBADMIN'))
	die;
?>
<div class="warning">
  Operation failed<br />
  Description: <?php echo $errstr?><br />
  Script: <?php echo $errfile?>
</div>