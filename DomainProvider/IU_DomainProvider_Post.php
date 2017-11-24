<?php

	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
      $name_DomainProvider			 = $_POST['txtName'];
	  $note_DomainProvider           = $_POST['txtNote'];
	  $website_DomainProvider        = $_POST['txtWebSite'];
	  $logo                          = '';
	  $logoBdd						 = '';

  // UPDATE
  
   if(isset($_POST['id_DomainProvider']))
   {
      $id_DomainProvider = $_POST['id_DomainProvider'];
	  if(strlen($_FILES['logo']['name'])!=0)
	  {
	  
	     $requete = $bdd->prepare('select logo_DomainProvider from domainprovider where id_DomainProvider = ?');
	     $requete->execute(array($id_DomainProvider));
	     $row = $requete->fetch();
	     $oldLogo = $row['logo_DomainProvider'];
		 
	     unlink('Images/'.$oldLogo);
	     upload();
		 $requete = $bdd->prepare('update domainprovider set name_DomainProvider = ? , note_DomainProvider = ? , website_DomainProvider = ? , logo_DomainProvider = ? where id_DomainProvider = ?');
		 $requete->execute(array($name_DomainProvider,$note_DomainProvider,$website_DomainProvider,$logoBdd,$id_DomainProvider));
	  }
	  else
	  {
	     $requete = $bdd->prepare('update domainprovider set name_DomainProvider = ? , note_DomainProvider = ? , website_DomainProvider = ?  where id_DomainProvider = ?');
		 $requete->execute(array($name_DomainProvider,$note_DomainProvider,$website_DomainProvider,$id_DomainProvider));
	  }
  
   }
   
   
    // INSERT
   else
   {
      $id_DomainProvider = $_POST['id_ServerProvider'];
	
      upload();
	  $logo = $_FILES['logo']['name']; 
     
	  $requete = $bdd->prepare('insert into domainprovider Values(?,?,?,?,?)');
	  $requete->execute(array(NULL,$name_DomainProvider,$note_DomainProvider,$website_DomainProvider,$logoBdd));
	  
   }
   
   //FUNCTION UPLOAD LOGO
   function upload()
   {
      global $logoBdd,$name_DomainProvider;
      $logo = $_FILES['logo']['name'];
	  $extension = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
	  $validExtensions = array('jpg','png','jpeg','gif');
	  
	  $logoBdd = $name_DomainProvider.'.'.$extension;
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES['logo']['tmp_name'],'Images/'.$logoBdd);
   }
   
   header('location:ShowDomainProviders.php');
   
   
?>