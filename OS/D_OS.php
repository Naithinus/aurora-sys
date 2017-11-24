<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_OS = $_POST['id_OS'];
    $requete = $bdd->prepare('delete from os where id_OS = ?');
    $requete->execute(array($id_OS));
    header('location:ShowOS.php');
?>