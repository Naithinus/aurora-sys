<?php
    
	include_once('../Includes/sessionVerification.php'); 
	 
	$monUrl    				  =   "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
    
    include('../Includes/bdd.php');
	
	$name_Country			  = $_POST['txtNameCountry'];
	$logo                     = '';
	$logoBdd                  = '';  
   //UPDATE	  
   if(isset($_POST['idCountry']))
   {
     
      $id_Country = $_POST['idCountry'];
	  
	  if(strlen($_FILES['logo']['name'])!=0)
	  {
	
		 $requete = $bdd->prepare('select flag_Country from country where id_Country = ?');
		 $requete->execute(array($id_Country));
		 $row = $requete->fetch();
		 $oldLogo = $row['flag_Country'];
		 
	     unlink('Images/'.$oldLogo);
	     upload();
		 $requete = $bdd->prepare('update country set name_Country = ? , flag_Country = ? where id_Country = ?');
		 $requete->execute(array($name_Country,$logoBdd,$id_Country));
	  }
	  
	  else
	  {
	     $requete = $bdd->prepare('update country set name_Country = ? where id_Country = ?');
		 $requete->execute(array($name_Country,$id_Country));
	  }
      
   }
   
   
   //INSERT
   else
   {
      upload();
	  $logo    = $_FILES['logo']['name']; 
	  $requete = $bdd->prepare('insert into country Values(?,?,?)');
	  $requete->execute(array(NULL,$name_Country,$logoBdd));
	  
   }
   
   //FUNCTION - UPLOAD FLAG COUNTRY
   function upload()
   {
      global $logoBdd,$name_Country;
      $logo            = $_FILES['logo']['name'];
	  $extension       = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
	  $validExtensions = array('jpg','png','jpeg','gif');
	  
	  $logoBdd = $name_Country.'.'.$extension;
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES['logo']['tmp_name'],'Images/'.$logoBdd);
   }
   
   header('location:ShowCountrys.php');
   
   
?>