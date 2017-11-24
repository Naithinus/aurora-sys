<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
	
    $name_isp			  = $_POST['txtNameISP'];
	$is_free_isp          = isset($_POST['chkIsFree']) ? '1' : '0';
    $logo_isp             = '';
	$logoBdd 			  = '';
    //UPDATED	
   if(isset($_POST['idISP']))
   {
      $idISP = $_POST['idISP'];
	  if(strlen($_FILES['logoISP']['name'])!=0)
	  {
	     $requete = $bdd->prepare('select logo_isp from isp where id_Isp = ?');
		 $requete->execute(array($idISP));
		 $row = $requete->fetch();
		 $oldLogo = $row['logo_isp'];
		 
		 unlink('Images/'.$oldLogo);
	     upload();
		 $requete = $bdd->prepare('update isp set name_isp = ? , is_free_isp = ?  , logo_isp = ? where id_isp = ?');
		 $requete->execute(array($name_isp,$is_free_isp,$logoBdd,$idISP));
	  }
	  else
	  {
	     $requete = $bdd->prepare('update isp set name_isp = ? , is_free_isp = ? where id_isp = ?');
		 $requete->execute(array($name_isp,$is_free_isp,$idISP));
	  }
     
   }
   
   
   //INSERT
   else
   {
      upload();
	  $logo_isp = $_FILES['logoISP']['name']; 

	  $requete = $bdd->prepare('insert into isp Values(?,?,?,?)');
	  $requete->execute(array(NULL,$name_isp,$logoBdd,$is_free_isp));
	  
   }
   
   
   //FUNCTION UPLOAD LOGO
   function upload()
   {
      global $name_isp;
	  global $logoBdd;
	  
      $logo_isp = $_FILES['logoISP']['name'];
	  $extension = strtolower(pathinfo($logo_isp, PATHINFO_EXTENSION));
	  $validExtensions = array('jpg','png','jpeg','gif');
	  
	  $logoBdd = $name_isp.'.'.$extension;
	  
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES['logoISP']['tmp_name'],'Images/'.$name_isp.'.'.$extension);
   }
   
   header('location:ShowISPS.php');
   
   
?>