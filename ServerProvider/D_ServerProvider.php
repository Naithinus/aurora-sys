<?php
 
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_ServerProvider = $_POST['id_ServerProvider'];
	
	$requete = $bdd->prepare('select logo_ServerProvider from serverprovider where id_ServerProvider = ?');
	$requete->execute(array($id_ServerProvider));
	$row = $requete->fetch();
	$oldLogo = $row['logo_ServerProvider'];
		 
	unlink('Images/'.$oldLogo);
		 
		 
    $requete = $bdd->prepare('delete from serverprovider where id_ServerProvider = ?');
    $requete->execute(array($id_ServerProvider));
    header('location:ShowServerProviders.php');
?>