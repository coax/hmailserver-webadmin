<?php
if (!defined('IN_WEBADMIN'))
	exit();

if (hmailGetAdminLevel() != ADMIN_SERVER)
	hmailHackingAttemp();

$obSettings = $obBaseApp->Settings();
$obGreyListingWhiteAddresses = $obSettings->AntiSpam->GreyListingWhiteAddresses();
$Count = $obGreyListingWhiteAddresses->Count();
$str_yes = Translate("Yes");
$str_no = Translate("No");
$str_delete = Translate("Remove");
$str_confirm = Translate("Confirm delete");
?>
    <div class="box large">
      <h2><?php EchoTranslation("Greylisting White listing") ?> <span>(<?php echo $Count ?>)</span></h2>
      <table class="tablesort">
        <thead>
          <tr>
            <th data-sort="string"><?php EchoTranslation("Description")?></th>
            <th style="width:25%;" data-sort="ipaddress"><?php EchoTranslation("IP address")?></th>
            <th style="width:32px;" class="no-sort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
<?php
if ($Count>0) {
	for ($i = 0; $i < $Count; $i++) {
		$obGreyListingWhiteAddress = $obGreyListingWhiteAddresses->Item($i);
		$description = $obGreyListingWhiteAddress->Description;
		$ipadress = $obGreyListingWhiteAddress->IPAddress;
		$id = $obGreyListingWhiteAddress->ID;
		$description = PreprocessOutput($description);

	   	echo '          <tr>
            <td><a href="?page=greylistingwhiteaddress&action=edit&ID=' . $id . '">' . $description . '</a></td>
            <td><a href="?page=greylistingwhiteaddress&action=edit&ID=' . $id . '">' . $ipadress . '</a></td>
            <td><a href="#" onclick="return Confirm(\'' . $str_confirm . ' <b>' . addslashes($description) . '</b>:\',\'' . $str_yes . '\',\'' . $str_no . '\',\'?page=background_greylistingwhiteaddress_save&csrftoken=' . $csrftoken . '&action=delete&ID=' . $id . '\');" class="delete" title="' . $str_delete . '">' . $str_delete . '</a></td>
          </tr>' . PHP_EOL;
	}
} else {
	echo '          <tr class="empty">
            <td colspan="2">' . Translate("You haven't added any greylisting white addresses") . '</td>
          </tr>' . PHP_EOL;
}
?>
        </tbody>
      </table>
      <div class="buttons center"><a href="?page=greylistingwhiteaddress&action=add" class="button"><?php EchoTranslation("Add new greylisting whitelist") ?></a></div>
    </div>