<?php
   if (!defined('IN_WEBADMIN'))
      exit();

   if (hmailGetAdminLevel() != 2)
   	hmailHackingAttemp(); // Only server admins can change this.
   
   $ID 		= hmailGetVar("ID",0);
   $action	      = hmailGetVar("action","");
   
   $obGreyListingWhiteAddresses	= $obBaseApp->Settings()->AntiSpam()->GreyListingWhiteAddresses;

   if ($action == "edit")
      $obAddress = $obGreyListingWhiteAddresses->ItemByDBID($ID);  
   elseif ($action == "add")
      $obAddress = $obGreyListingWhiteAddresses->Add();  
   elseif ($action == "delete")
   {
      $obGreyListingWhiteAddresses->DeleteByDBID($ID);  
      header("Location: index.php?page=greylistingwhiteaddresses");
      exit();
   }
      
   $IPAddress = hmailGetVar("IPAddress",0);
   $Description    = hmailGetVar("Description",0);

   $obAddress->IPAddress  = $IPAddress;
   $obAddress->Description     = $Description;
   
   $obAddress->Save();
   
   
   
   header("Location: index.php?page=greylistingwhiteaddresses");
?>

