<?php
	
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
	
    $name_OS = $_POST['txtNameOS'];
    $bit_OS  = $_POST['cmbBit'];
   
   
    // UPDATE
    if(isset($_POST['id_OS']))
    {
      
      $id_OS = $_POST['id_OS'];
	  $requete = $bdd->prepare('update os set name_OS = ? , bit_OS = ? where id_OS = ?');
	  $requete->execute(array($name_OS,$bit_OS,$id_OS));
	  
   }
   
   // INSERT
   else
   {
      
	  $requete = $bdd->prepare('insert into os VALUES(?,?,?)');
	  $requete->execute(array(NULL,$name_OS,$bit_OS));
   }
   
    header('location:ShowOS.php');
?>