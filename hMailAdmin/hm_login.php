<?php
if (!defined('IN_WEBADMIN'))
	exit();
?>
  <div style="width:100%; height:100%; display:flex; justify-content:center;">
    <div class="box login">
      <div class="container"><div class="logo"></div><h1>hMailServer</h1></div>
      <form action="<?php echo $hmail_config['rooturl']; ?>index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_login");
?>
        <p><?php EchoTranslation("Username") ?></p>
        <input type="text" name="username" size="25" maxlength="255" autocomplete="off" class="req">
        <p><?php EchoTranslation("Password") ?></p>
        <div><input type="password" name="password" size="25" maxlength="255" autocomplete="off" class="req"><i data-feather="eye-off" id="toggle-password"></i></div>
<?php
$error = hmailGetVar("error");
if ($error == "1") echo '<span class="warning bottom">' . Translate("Incorrect username or password.") . '</span>';
?>
        <p><button><?php EchoTranslation("Sign in") ?></button></p>
      </form>
    </div>
  </div>