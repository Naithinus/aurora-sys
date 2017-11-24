<?php
 
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    Include('../Includes/bdd.php');
    $idCreative = $_POST['id_Creative'];
	
	$requete = $bdd->prepare('select name_Creative from creatives where id_Creative = ?');
    $requete->execute(array($idCreative));
    $row = $requete->fetch();
    $nameCreative = $row['name_Creative'];
		 
    unlink('../../Creatives/'.$nameCreative);
 
    $requete = $bdd->prepare('delete from creatives where id_Creative = ?');
    $requete->execute(array($idCreative));
?>