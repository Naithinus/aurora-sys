<?php
	
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
	
    $name_ServerProvider			 = $_POST['txtName'];
	$note_ServerProvider             = $_POST['txtNote'];
	$website_ServerProvider          = $_POST['txtWebSite'];
	$logo                            = '';
	$logoBdd						 = '';
    // UPDATE	
    if(isset($_POST['id_ServerProvider']))
    {
      $id_ServerProvider = $_POST['id_ServerProvider'];
	  if(strlen($_FILES['logo']['name'])!=0)
	  {
	  
	     $requete = $bdd->prepare('select logo_ServerProvider from serverprovider where id_ServerProvider = ?');
	     $requete->execute(array($id_ServerProvider));
	     $row = $requete->fetch();
	     $oldLogo = $row['logo_ServerProvider'];
		 
	     unlink('Images/'.$oldLogo);
	     upload();
		 $requete = $bdd->prepare('update serverprovider set name_ServerProvider = ? , note_ServerProvider = ? , website_ServerProvider = ? , logo_ServerProvider = ? where id_ServerProvider = ?');
		 $requete->execute(array($name_ServerProvider,$note_ServerProvider,$website_ServerProvider,$logoBdd,$id_ServerProvider));
	  }
	  else
	  {
	     $requete = $bdd->prepare('update serverprovider set name_ServerProvider = ? , note_ServerProvider = ? , website_ServerProvider = ?  where id_ServerProvider = ?');
		 $requete->execute(array($name_ServerProvider,$note_ServerProvider,$website_ServerProvider,$id_ServerProvider));
	  }
      
    }
    
    // INSERT	
    else
    {
      upload();
	  $logo = $_FILES['logo']['name']; 
      
	  $requete = $bdd->prepare('insert into serverprovider Values(?,?,?,?,?)');
	  $requete->execute(array(NULL,$name_ServerProvider,$note_ServerProvider,$website_ServerProvider,$logoBdd));
	  
    }
   
    function upload()
    {
	  global $logoBdd,$name_ServerProvider;
      $logo = $_FILES['logo']['name'];
	  $extension = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
	  $validExtensions = array('jpg','png','jpeg','gif');
	  
	  $logoBdd = $name_ServerProvider.'.'.$extension;
	  echo $logoBdd;
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES['logo']['tmp_name'],'Images/'.$logoBdd);
    }
   
    header('location:ShowServerProviders.php');
   
   
?>