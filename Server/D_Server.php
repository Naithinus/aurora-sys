<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 


    include('../Includes/bdd.php');
    $id_Server = $_POST['id_Server'];
 
    $requete = $bdd->prepare('delete from ip where id_Server_IP = ?');
    $requete->execute(array($id_Server));
 
 
    $requete = $bdd->prepare('delete from server where id_Server = ?');
    $requete->execute(array($id_Server));
 
    header('location:ShowServers.php');
?>