<?php
if (!defined('IN_WEBADMIN'))
	exit();
?>
  <div class="box" style="margin:auto; float:none; margin-top:10%;">
    <div style="width:100%; margin-bottom:18px; font-size:11pt; color:#fff; line-height:45px; text-indent:62px; background:url(modern/css/paperplane.svg) 3% 40% no-repeat #1784c7; background-size:auto 63%;">hMailServer</div>
    <form action="<?php echo $hmail_config['rooturl']; ?>index.php" method="post" onsubmit="return $(this).validation();" class="cd-form" name="mainform">
<?php
PrintHidden("page", "background_login");

$error = hmailGetVar("error");

if ($error == "1") {
	echo '<div class="warning">' . $obLanguage->String("Incorrect username or password.") . '</div>';
}
?>
      <p><?php EchoTranslation("User name") ?></p>
      <input type="text" name="username" size="25" maxlength="256" class="req">
      <p><?php EchoTranslation("Password") ?></p>
      <input type="password" name="password" size="25" maxlength="256" class="req">
      <p><input type="submit" value="<?php EchoTranslation("Sign in") ?>"></p>
    </form>
  </div>