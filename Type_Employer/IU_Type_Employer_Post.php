<?php
	
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
	
    $name_type_Employer = $_POST['txtNameTypeEmployer'];
    $id_type_Employer   = '';
   
   // UPDATE
   if(isset($_POST['idTypeEmployer']))
   {
      
      $id_type_Employer = $_POST['idTypeEmployer'];
	  $requete = $bdd->prepare('update type_employer set name_type_Employer=? where id_type_Employer = ?');
	  $requete->execute(array($name_type_Employer,$id_type_Employer));
	  
   }
   
   // INSERT
   else
   {
      
	  $requete = $bdd->prepare('insert into type_employer VALUES(?,?)');
	  $requete->execute(array(NULL,$name_type_Employer));
	 
   }
   
    header('location:ShowTypes.php');
?>