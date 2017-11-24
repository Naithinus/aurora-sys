<?php
 
    include_once('../Includes/sessionVerification.php'); 
	$monUrl        = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include('../Includes/bdd.php');
    
	$id_Domain     = $_POST['id_Domain'];
    $requete       = $bdd->prepare('delete from domain where id_Domain = ?');
    $requete->execute(array($id_Domain));
    header('location:ShowDomains.php');
?>