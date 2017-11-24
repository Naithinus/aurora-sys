<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl      = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_DomainProvider = $_POST['id_DomainProvider'];
	
	$requete = $bdd->prepare('select logo_DomainProvider from domainprovider where id_DomainProvider = ?');
	$requete->execute(array($id_DomainProvider));
	$row = $requete->fetch();
	$oldLogo = $row['logo_DomainProvider'];
		 
	unlink('Images/'.$oldLogo);
	
    $requete           = $bdd->prepare('delete from domainprovider where id_DomainProvider = ?');
    $requete->execute(array($id_DomainProvider));
    header('location:ShowDomainProviders.php');
?>