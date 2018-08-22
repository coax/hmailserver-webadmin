<?php
if (!defined('IN_WEBADMIN'))
	exit();

// Request variables
$DomainId = hmailGetVar("domainid", 0, true);
$AccountId = hmailGetVar("accountid", 0, true);
$FolderId = hmailGetVar("folderid", 0, true);
//$SubFolderId = hmailGetVar("subfolderid", 0, true);
$Action = hmailGetVar("action", "");

$obDomain = $obBaseApp->Domains->ItemByDBID($DomainId);

// Not domain admin
if (hmailGetAdminLevel() == 0)
	hmailHackingAttemp();

// Domain admin but not for this domain
if (hmailGetAdminLevel() == 1 && $DomainId != hmailGetDomainID())
	hmailHackingAttemp();

// Request variables
$FolderName = hmailGetVar("foldername", "Unnamed");
//$FolderParent = hmailGetVar("folderparentid", -1);
$FolderSubscribed = hmailGetVar("folderissubscribed", 0);

$obAccount = $obDomain->Accounts->ItemByDBID($AccountId);
$Folders = $obAccount->IMAPFolders();

// Actions
if ($Action == "edit")
	$Folder = $Folders->ItemByDBID($FolderId);
elseif ($Action == "add") {
	$Folders->Add($FolderName);
	header("Location: index.php?page=account&action=edit&domainid=$DomainId&accountid=$AccountId");
	exit();
} elseif ($Action == "delete") {
	$Folders->DeleteByDBID($FolderId);
	header("Location: index.php?page=account&action=edit&domainid=$DomainId&accountid=$AccountId");
	exit();
}

// Save the changes
$Folder->Name = $FolderName;
//$Folder->ParentID = $FolderParent;
$Folder->Subscribed = $FolderSubscribed;

$Folder->Save();

header("Location: index.php?page=account&action=edit&domainid=$DomainId&accountid=$AccountId");
?>