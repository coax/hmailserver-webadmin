<?php
if (!defined('IN_WEBADMIN'))
	exit();
?>
  <div style="margin:15% 10%;">
    <div class="box login">
      <div class="logo">hMailServer</div>
      <form action="<?php echo $hmail_config['rooturl']; ?>index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_login");

$error = hmailGetVar("error");
if ($error == "1") echo '<p class="warning bottom">' . Translate("Incorrect username or password.") . '</p>';
?>
        <p><?php EchoTranslation("User name") ?></p>
        <input type="text" name="username" size="25" maxlength="255" class="req">
        <p><?php EchoTranslation("Password") ?></p>
        <input type="password" name="password" size="25" maxlength="255" autocomplete="off" class="req">
        <p><button><?php EchoTranslation("Sign in") ?></button></p>
      </form>
    </div>
  </div>