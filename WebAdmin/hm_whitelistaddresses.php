<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != 2)
	hmailHackingAttemp(); // Only server admins can change this.

$obWhiteListAddresses = $obBaseApp->Settings()->AntiSpam()->WhiteListAddresses;
?>
    <div class="box large">
      <h2><?php EchoTranslation("White listing") ?></h2>
      <div style="margin:0 18px 18px;">
        <table>
          <tr>
            <th style="width:25%;"><?php EchoTranslation("Description")?></th>
            <th style="width:25%;"><?php EchoTranslation("Lower IP")?></th>
            <th style="width:25%;"><?php EchoTranslation("Upper IP")?></th>
            <th style="width:20%;"><?php EchoTranslation("E-mail address")?></th>
            <th style="width:5%;">&nbsp;</th>
          </tr>
<?php
$Count = $obWhiteListAddresses->Count();
$str_delete = $obLanguage->String("Remove");

for ($i = 0; $i < $Count; $i++) {
	$obAddress = $obWhiteListAddresses->Item($i);
	$ID = $obAddress->ID;
	$LowerIPAddress = $obAddress->LowerIPAddress;
	$UpperIPAddress = $obAddress->UpperIPAddress;
	$EmailAddress = $obAddress->EmailAddress;
	$Description = $obAddress->Description;
	$EmailAddress = PreprocessOutput($EmailAddress);
	$Description = PreprocessOutput($Description);

	echo '          <tr>
            <td><a href="?page=whitelistaddress&action=edit&ID=' . $ID . '">' . $Description . '</a></td>
            <td><a href="?page=whitelistaddress&action=edit&ID=' . $ID . '">' . $LowerIPAddress . '</a></td>
            <td><a href="?page=whitelistaddress&action=edit&ID=' . $ID . '">' . $UpperIPAddress . '</a></td>
            <td><a href="?page=whitelistaddress&action=edit&ID=' . $ID . '">' . $EmailAddress . '</a></td>
            <td><a href="#" onclick="return Confirm(\'Confirm delete <b>' . $Description . '</b>:\',\'Yes\',\'?page=background_whitelistaddress_save&action=delete&ID=' . $ID . '\');" class="delete">Delete</a></td>
          </tr>' . PHP_EOL;
}
?>
        </table>
        <div class="buttons center"><a href="?page=whitelistaddress&action=add" class="button">New new whitelist</a></div>
      </div>
    </div>