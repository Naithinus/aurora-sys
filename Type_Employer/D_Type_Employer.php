<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
    $id_Type_Employer = $_POST['id_type_Employer'];
    $requete = $bdd->prepare('delete from type_employer where id_type_Employer = ?');
    $requete->execute(array($id_Type_Employer));
    header('location:ShowTypes.php');
?>