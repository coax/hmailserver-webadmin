<?php
define('IN_WEBADMIN', true);
define('CSRF_ENABLED', true);

require_once("config.php");
require_once("include/initialization_test.php");
require_once("initialize.php");
?>
<h2>Impressum</h2>
<p>hMailAdmin (PHPWebAdmin redesign project) version <?php echo $hmail_config['version'] ?>, source on <a href="https://github.com/coax/hmailserver-webadmin" target="_blank">GitHub</a>
<?php
$version = Version();
if ($hmail_config['version']<$version) echo '<p class="warning"><a href="https://github.com/coax/hmailserver-webadmin/releases/latest" target="_blank">New version ' . $version . ' available on GitHub</a></p>' . PHP_EOL;
?>
<p>Redesign &amp; coding by <a href="https://matija.matecic.com/" target="_blank">Matija Matecic</a></p>
<p>Additional coding by <a href="https://github.com/tunis78" target="_blank">Andreas Tunberg</a> and <a href="https://github.com/RvdHout" target="_blank">Ruud van den Hout</a></p>
<h3>hMailServer</h3>
<p>hMailServer version <?php echo $obBaseApp->Version ?>, source on <a href="https://github.com/hmailserver/hmailserver/" target="_blank">GitHub</a></p>
<p>Author Martin Knafve</p>
<h3>Third party components</h3>
<p>This section lists third party components and libraries used in hMailAdmin:</p>
<ul style="padding:0 10px;">
  <li style="padding-bottom:4px;"><a href="https://jquery.com/" target="_blank">&middot; jQuery</a></li>
  <li style="padding-bottom:4px;"><a href="https://modernizr.com/" target="_blank">&middot; Modernizr</a></li>
  <li style="padding-bottom:4px;"><a href="http://defunkt.io/facebox/" target="_blank">&middot; Defunkt Facebox</a></li>
  <li style="padding-bottom:4px;"><a href="https://github.com/joequery/Stupid-Table-Plugin" target="_blank">&middot; stupidtable</a></li>
  <li style="padding-bottom:4px;"><a href="https://github.com/fengyuanchen/datepicker" target="_blank">&middot; datepicker</a></li>
  <li style="padding-bottom:4px;"><a href="https://github.com/jackmoore/autosize" target="_blank">&middot; autosize</a></li>
  <li style="padding-bottom:4px;"><a href="https://timeago.yarp.com/" target="_blank">&middot; timeago</a></li>
  <li style="padding-bottom:4px;"><a href="https://gionkunz.github.io/chartist-js/" target="_blank">&middot; Chartist</a></li>
  <li style="padding-bottom:4px;"><a href="https://github.com/StephanWagner/jBox" target="_blank">&middot; jBox</a></li>
  <li style="padding-bottom:10px;"><a href="https://feathericons.com/" target="_blank">&middot; Feather</a></li>
</ul>
<h3>Donate</h3>
<p>Don't worry, hMailAdmin will stay free - but I do need your support in order to continue developing it! Thank you :)</p>
<a href="https://www.paypal.me/MatijaMatecic/10EUR" target="_blank" style="display:inline-block; width:30%; height:70px; background:url(css/logo-paypal.svg) 50% 10% no-repeat; background-size:60%; margin-right:4%; padding-top:50px; text-align:center;">Buy me a coffee</a>
<a href="https://pay.withbitcoin.org/api/v1/invoices?storeId=8thKA61gYGhmax2SeNo1LLzDg4yG1eMoo9dV8Mm2PUgx&price=10&currency=EUR" target="_blank" style="display:inline-block; width:30%; height:70px; background:url(css/logo-bitcoin.svg) 50% 10% no-repeat; background-size:60%; margin-right:4%; padding-top:50px; text-align:center;">Buy me a coffee</a>