<?php
	
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
	
    $name_TypeList = $_POST['txtNameTypeList'];
    $abr_TypeList  = $_POST['txtAbreviation'];
   
    // UPDATE
    if(isset($_POST['id_TypeList']))
    {
      
      $id_TypeList = $_POST['id_TypeList'];
	  $requete = $bdd->prepare('update typelist set name_TypeList = ? , abr_TypeList = ? where id_TypeList = ?');
	  $requete->execute(array($name_TypeList,$abr_TypeList,$id_TypeList));
	  
    }
   
   // INSERT
    else
    {
      
	  $requete = $bdd->prepare('insert into typelist VALUES(?,?,?)');
	  $requete->execute(array(NULL,$name_TypeList,$abr_TypeList));
	 
    }
   
    header('location:ShowTypeLists.php');
?>