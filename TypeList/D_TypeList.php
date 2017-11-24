<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_TypeList = $_POST['id_TypeList'];
    $requete = $bdd->prepare('delete from typelist where id_TypeList = ?');
    $requete->execute(array($id_TypeList));
    header('location:ShowTypeLists.php');
?>