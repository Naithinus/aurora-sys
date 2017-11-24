<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
 
include('../Includes/bdd.php');

$idSend = $_POST['idS'];
$type   = $_POST['type'];

switch($type)
{
	
	case "ips":
	
	  $requete = $bdd->prepare('select IPS_Send from send where id_Send = ?');
	  $requete->execute(array($idSend));
	  $row = $requete->fetch();
	  $ips = $row['IPS_Send'];
	  echo $ips;
	  
	break;
	
	
	case "emailTest":
	
	  $requete = $bdd->prepare('select emailTest_Send from send where id_Send = ?');
	  $requete->execute(array($idSend));
	  $row = $requete->fetch();
	  $emailTest = $row['emailTest_Send'];
	  echo $emailTest;
	  
	break;
	
	
	case "startFrom":
	
	  $requete = $bdd->prepare('select startFrom_Send from send where id_Send = ?');
	  $requete->execute(array($idSend));
	  $row = $requete->fetch();
	  $startFrom = $row['startFrom_Send'];
	  echo $startFrom;
	  
	break;
	
}


?>