<?php
if (!isset($errstr) || !isset($errfile))
	die;

if (!defined('IN_WEBADMIN'))
	die;
?>
      <h2><?php EchoTranslation("Operation failed") ?></h2>
      <p>Description: <?php echo PreprocessOutput($errstr) ?></p>
      <p>Line: <?php echo $errline ?></p>
      <p>Script: <?php echo PreprocessOutput($errfile) ?></p>