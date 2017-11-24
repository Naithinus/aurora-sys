<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_ISP = $_POST['id_ISP'];
	
	$requete = $bdd->prepare('select logo_isp from isp where id_Isp = ?');
	$requete->execute(array($id_ISP));
	$row = $requete->fetch();
	$oldLogo = $row['logo_isp'];
		 
	unlink('Images/'.$oldLogo);
	
	
    $requete = $bdd->prepare('delete from isp where id_Isp = ?');
    $requete->execute(array($id_ISP));
    header('location:ShowISPS.php');
?>