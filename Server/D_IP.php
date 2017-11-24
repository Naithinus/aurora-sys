<?php


	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
	$idIP = $_POST['idIP'];
	$requete = $bdd->prepare('delete from ip where id_IP=?');
	$requete->execute(array($idIP));
?>	