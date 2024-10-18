<?php
if (!defined('IN_WEBADMIN'))
	exit();

// Request variables
$DomainId = hmailGetVar("domainid", 0, true);
$AccountId = hmailGetVar("accountid", 0, true);
$FolderId = hmailGetVar("folderid", 0, true);
//$SubFolderId = hmailGetVar("subfolderid", 0, true);
$Action = hmailGetVar("action", "");

// Not domain admin
if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

// Domain admin but not for this domain
if (hmailGetAdminLevel() == 1 && $DomainId != hmailGetDomainID())
	hmailHackingAttemp();

// Define variables
$obDomain = $obBaseApp->Domains->ItemByDBID($DomainId);
$obAccount = $obDomain->Accounts->ItemByDBID($AccountId);
$Folders = $obAccount->IMAPFolders();

// Actions
if ($Action == "edit") {
	$Folder = $Folders->ItemByDBID($FolderId);
	$FolderName = $Folder->Name;
	$FolderParent = $Folder->ParentID;
	$FolderSubscribed = $Folder->Subscribed;

/*
	// API doesn't support adding/changing subfolders
	if ($SubFolderId > 0) {
		$SubFolders = $Folder->SubFolders;
		$SubFolder = $SubFolders->ItemByDBID($SubFolderId);
		$FolderName = $SubFolder->Name;
		$FolderParent = $SubFolder->ParentID;
		$FolderSubscribed = $SubFolder->Subscribed;
	}
*/
} else {
	$FolderName = "";
	$FolderParent = "";
	$FolderSubscribed = false;
}
?>
      <div class="box small">
        <h2><?php EchoTranslation("IMAP folder") ?></h2>
        <form action="index.php" method="post" class="form">
<?php
PrintHiddenCsrfToken();
PrintHidden("page", "background_account_imapfolder_save");
PrintHidden("domainid", $DomainId);
PrintHidden("accountid", $AccountId);
PrintHidden("folderid", $FolderId);
//PrintHidden("subfolderid", $SubFolderId);
PrintHidden("action", $Action);
?>
          <p><?php EchoTranslation("Folder name") ?></p>
          <input type="text" name="foldername" value="<?php echo PreprocessOutput($FolderName)?>" maxlength="255" class="req">
<!--
          <p><?php EchoTranslation("Parent folder") ?></p>
          <select name="folderparentid">
            <option value="-1">Root</option>
<?php
	// Folders object
	$TotalFolders = $Folders->Count();

	for ($i = 0; $i < $TotalFolders; $i++) {
		// Folder object
		$Folder = $Folders->Item($i);
		$FolderId = $Folder->ID;
		$FolderName = $Folder->Name;

		// Select folder in dropdown
		($FolderParent == $FolderId) ? $Selected = " selected" : $Selected = "";

		echo '            <option value="' . $FolderId . '"' . $Selected . '>' . $FolderName . '</option>' . PHP_EOL;
	}
?>
          </select>
-->
<?php
PrintCheckboxRow("folderissubscribed", "Subscribed", $FolderSubscribed);

PrintSaveButton(null, null, "?page=account&action=edit&domainid=$DomainId&accountid=$AccountId");
?>
        </form>
      </div>