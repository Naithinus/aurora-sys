<?php

include_once('../Includes/sessionVerificationMailer.php');
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
	
header("Access-Control-Allow-Origin: *");
include('../Includes/bdd.php');

$ipTxt  = $_POST['ipTxt'];

$ips = '';


   $requete = $bdd->prepare('select * from vmta where ip = ? and id_Mailer = ?');
   $requete->execute(array($ipTxt,$_SESSION['id_Employer']));
   
   $row = $requete->fetch();
   if($row)
   {
	   echo '1-'.$row['domain'];
   }
   else
	   echo '0';

?>