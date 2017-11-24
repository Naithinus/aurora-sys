<?php
	
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 


    include('../Includes/bdd.php');
	
    $name_Vertical = $_POST['txtVertical'];
   
    // UPDATE
    if(isset($_POST['id_Vertical']))
    {
      
      $id_Vertical = $_POST['id_Vertical'];
	  $requete = $bdd->prepare('update vertical set name_Vertical = ? where id_Vertical = ?');
	  $requete->execute(array($name_Vertical,$id_Vertical));
	  
    }
   
    // INSERT
    else
    {
      
	  $requete = $bdd->prepare('insert into vertical VALUES(?,?)');
	  $requete->execute(array(NULL,$name_Vertical));
	 
    }
   
    header('location:ShowVerticals.php');
?>