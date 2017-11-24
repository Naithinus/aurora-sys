<?php
 
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_Vertical = $_POST['id_Vertical'];
    $requete = $bdd->prepare('delete from vertical where id_Vertical = ?');
    $requete->execute(array($id_Vertical));
    header('location:ShowVerticals.php');
?>