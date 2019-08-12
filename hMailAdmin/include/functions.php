<?php
if (!defined('IN_WEBADMIN'))
	exit();

define("ADMIN_USER", 0);
define("ADMIN_DOMAIN", 1);
define("ADMIN_SERVER", 2);

define("CONNECTION_SECURITY_NONE", 0);
define("CONNECTION_SECURITY_TLS", 1);
define("CONNECTION_SECURITY_STARTTLSOPTIONAL", 2);
define("CONNECTION_SECURITY_STARTTLSREQUIRED", 3);

define("CSRF_TOKEN_PARAMNAME", "csrftoken");

function hmailGetVar($p_varname, $p_defaultvalue = null, $p_isnumeric = false) {
	$retval = $p_defaultvalue;
	if(isset($_GET[$p_varname])) {
		$retval = $_GET[$p_varname];
	} else if (isset($_POST[$p_varname])) {
		$retval = $_POST[$p_varname];
	} else if (isset($_REQUEST[$p_varname])) {
		$retval	= $_REQUEST[$p_varname];
	}

	if (get_magic_quotes_gpc())
		$retval = stripslashes($retval);

	if ($p_isnumeric) {
		$retval = intval($retval);
	}

	return $retval;
}

function hmailGetUserDomainName($username) {
	$atpos = strpos($username, "@");
	$domain = substr($username, $atpos + 1);
	return $domain;
}

function hmailGetAdminLevel() {
	if (isset($_SESSION["session_adminlevel"]))
		return $_SESSION["session_adminlevel"];
	else
		return -1;
}

function hmailGetDomainID() {
	if (isset($_SESSION["session_domainid"]))
		return $_SESSION["session_domainid"];
	else
		return -1;
}

function hmailGetAccountID() {
	if (isset($_SESSION["session_accountid"]))
		return $_SESSION["session_accountid"];
	else
		return -1;
}

function hmail_isloggedin() {
	if (isset($_SESSION['session_loggedin']) &&
		$_SESSION['session_loggedin'] == "1")
		return true;
	else
		return false;
}

function hmailHackingAttemp() {
	include "hm_permission_denied.php";
	exit();
}

function hmailHasDomainAccess($domainid) {
	if (hmailGetAdminLevel() == 2)
		return true;

	if (hmailGetAdminLevel() == 1 && hmailGetDomainID() == $domainid)
		return true;

	return false;
}

function hmailCheckedIf1($value) {
	if ($value == "1")
		return "checked";
	else
		return "";
}

function GetStringForJavaScript($StringID) {
	global $obLanguage;

	$value = $obLanguage->String($StringID);
	$value = str_replace('\'', '\\\'', $value);

	return $value;
}

function EchoTranslation($string) {
	global $obLanguage;

	echo $obLanguage->String($string);
}

function HMEscape($string) {
	$string = str_replace('\'', '\\\'', $string);
	return $string;
}

function EscapeStringForJs($string) {
	return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}

function ExceptionHandler($exception) {
	$errno = $exception->getCode();
	$errfile = basename($exception->getFile());
	$errline = $exception->getLine();
	$errstr = $exception->getMessage();

	include "error.php";

	die;
}

function ErrorHandler($errno, $errstr, $errfile, $errline) {
	$errfile = basename($errfile);

	include "error.php";

	die;
}

function  PrintPropertyRow($caption, $value) {
	global $obLanguage;
	$caption = $obLanguage->String($caption);

	echo '          <p>' . $caption . '</p><b>' . $value . '</b>' . PHP_EOL;
}

function PrintPropertyEditRow($name, $caption, $value, $length = 255, $checktype = null, $class = null) {
	global $obLanguage;
	$caption = $obLanguage->String($caption);
	$value = PreprocessOutput($value);
	$req = '';
	if (isset($checktype)) $req = 'req ';

	echo '          <p>' . $caption . '</p><input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" checktype="' . $checktype . '" size="' . $length . '" maxlength="' . $length . '" class="' . $req . $class . ' ' . $checktype . '">' . PHP_EOL;
}

function PrintPropertyAreaRow($name, $caption, $value, $rows = 5, $cols = 20, $class = "") {
	global $obLanguage;
	$caption = $obLanguage->String($caption);
	$value = PreprocessOutput($value);

	echo '          <p>' . $caption . '</p><textarea name="' . $name . '" id="' . $name . '" rows="' . $rows . '" cols="' . $cols . '" class="' . $class . '">' . $value . '</textarea>' . PHP_EOL;
}


function PrintPasswordEntry($name, $caption, $length = 20, $class = "") {
	global $obLanguage;
	$caption = $obLanguage->String($caption);

	echo '          <p>' . $caption . '</p><input type="password" name="' . $name . '" id="' . $name . '" size="' . $length . '" class="' . $class . '" autocomplete="off">' . PHP_EOL;
}

function PrintCheckboxRow($name, $caption, $checked) {
	global $obLanguage;
	$caption = $obLanguage->String($caption);
	$checked_text = hmailCheckedIf1($checked);

	echo '          <p><input type="checkbox" name="' . $name . '" id="' . $name . '" value="1" ' . $checked_text . '><label for="' . $name . '">' . $caption . '</label></p>' . PHP_EOL;
}

function PrintLargeTableHeader($caption) {
	global $obLanguage;
	$caption = $obLanguage->String($caption);

	echo '<tr>
  <td colspan=\"2\"><h2>$caption</h2></td>
</tr>' . PHP_EOL;
}

function PrintSaveButton($Caption = null, $Cancel = null, $Url = null) {
	if (!$Caption) $Caption = Translate('Save');
	if (!$Cancel) $Cancel = Translate('Cancel');
	if (!$Url) $Url = 'javascript:window.history.back();';

	echo '          <div class="buttons"><button>' . $Caption . '</button><a href="' . $Url . '" class="cancel">' . $Cancel . '</a></div>' . PHP_EOL;
}

function PrintHidden($name, $value) {
	$name = PreprocessOutput($name);
	$value = PreprocessOutput($value);

	echo '          <input type="hidden" name="' . $name . '" value="' . $value . '">' . PHP_EOL;
}

function PrintHiddenCsrfToken() {
	PrintHidden("csrftoken", get_csrf_session_token());
}

function GetConfirmDelete() {
	global $obLanguage;
	return GetStringForJavaScript($obLanguage->String("Are you sure you want to delete %s?"));
}

function PreprocessOutput($outputString) {
	return htmlspecialchars($outputString, ENT_COMPAT, 'utf-8', true);
}

function GetHasRuleAccess($domainid, $accountid) {
	global $hmail_config;

	if (hmailGetAdminLevel() == ADMIN_SERVER) {
		// server admin always have access.
		return true;
	} else if (hmailGetAdminLevel() == ADMIN_DOMAIN) {
		// Domain admin has access if domain access is enabled.
		if ($hmail_config['rule_editing_level'] == ADMIN_DOMAIN && hmailGetDomainID() == $domainid && $accountid != 0) {
			return true;
		}
		// Domain admin has access if user-level is permitted and the account
		// is under the domain admins control.
		if ($hmail_config['rule_editing_level'] == ADMIN_USER && hmailGetDomainID() == $domainid) {
			return true;
		}
	} else if (hmailGetAdminLevel() == ADMIN_USER) {
		// user has access if enabled and the rule is connected to his account.
		if ($hmail_config['rule_editing_level'] == ADMIN_USER && hmailGetDomainID() == $domainid && hmailGetAccountID() == $accountid) {
			return true;
		}
	}
	return false;
}

function generate_random_string() {
	if (function_exists("openssl_random_pseudo_bytes"))	 {
		$bytes = openssl_random_pseudo_bytes(10);
		return bin2hex($bytes);
	}  else  {
		$value = mt_rand();
		return sha1(strval($value));
	}
}

function ensure_csrf_session_token_exists() {
	 if (isset($_SESSION["session_csrf_token"]) && !empty($_SESSION["session_csrf_token"]))
		return;

	$token = generate_random_string();

	$_SESSION["session_csrf_token"] = $token;
}

function get_csrf_session_token() {
	return $_SESSION['session_csrf_token'];
}

function validate_csrf_token_supplied() {
	if (!defined('CSRF_ENABLED') || CSRF_ENABLED !== true)  {
		// CSRF validaton has been disabled.
		return;
	}

	$expected_token = get_csrf_session_token();

	$actual_token = hmailGetVar("csrftoken");

	if (strcmp($expected_token, $actual_token) !== 0) {
		echo "Invalid CSRF token.";
		die;
	}
}

//convert any date to ISO date format
function makeIsoDate($date) {
	return date('Y-m-d H:i:s', strtotime($date));
}

// New translation class
class translate {
	private $obLanguage;
	private $translate = false;
	private $web_translations_def;
	private $web_translations;

	public function __construct($obLanguage,$language) {
		if($language === 'english') return;
		$this->obLanguage = $obLanguage;
		//$this->obLanguage = $obBaseApp->GlobalObjects->Languages->ItemByName($language);
		$this->translate = true;
		$this->web_translations_def = $this->load_translation('english');
		$this->web_translations = $this->load_translation($language);
	}

	public function String($string) {
		if(!$this->translate) return $string;
		if(($value = $this->obLanguage->String($string)) !== $string) return $value;
		return $this->WebTranslation($string);
	}

	private function WebTranslation($phrase2translate) {
		$arrayKey = array_search($phrase2translate, $this->web_translations_def);
		if ($arrayKey !== FALSE && isset($this->web_translations[$arrayKey]))
			return $this->web_translations[$arrayKey];

		return $phrase2translate;
	}

	private function load_translation($language) {
		if(!file_exists('./languages/' . $language . '.php')) return array();
		return include('./languages/' . $language . '.php');
	}
}

// Version check
function Version() {
	$json = file_get_contents("https://raw.githubusercontent.com/coax/hmailserver-webadmin/master/version.txt");
	$parsed = json_decode($json);
	return $parsed;
}

// Translate without echo
function Translate($string) {
	global $obLanguage;
	return $obLanguage->String($string);
}

// IP 2 Country
function GeoIp($ip) {
	global $obLanguage;
	if ($ip === '0.0.0.0') return Translate('Unknown');
	$regex = '/(127\.0\.0\.1)|^(10\.)|^(192\.168\.)|^(169\.254\.)|^(172\.(1[6-9]|2[0-9]|3[0-1]))/';
	if (preg_match($regex, $ip)) return Translate('Local IP range');

	$json = file_get_contents('https://extreme-ip-lookup.com/json/' . $ip);
	$parsed = json_decode($json);

	if(!$parsed->countryCode) return Translate('Unknown');
	return '<p><img src="flags/' . $parsed->countryCode . '.gif" style="margin-right:5px;">' . $parsed->country . '</p>';
}
?>