<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

	include('../Includes/bdd.php');
	$id_Employer = $_POST['id_Employer'];
	$requete = $bdd->prepare('delete from employer where id_Employer = ?');
	$requete->execute(array($id_Employer));
	header('location:ShowEmployers.php');
?>