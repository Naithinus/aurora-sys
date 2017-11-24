<?php
 
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 


    include('../Includes/bdd.php');
    $id_Sponsor = $_POST['id_Sponsor'];
	
	$requete = $bdd->prepare('select logo_Sponsor from sponsor where id_Sponsor = ?');
	$requete->execute(array($id_Sponsor));
    $row = $requete->fetch();
    $oldLogo = $row['logo_Sponsor'];
		 
	unlink('Images/'.$oldLogo);
		 
    $requete = $bdd->prepare('delete from sponsor where id_Sponsor = ?');
    $requete->execute(array($id_Sponsor));
    header('location:ShowSponsors.php');
?>