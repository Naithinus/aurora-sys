<?php

include_once('../Includes/sessionVerificationMailer.php');
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

header("Access-Control-Allow-Origin: *");
include('../Includes/bdd.php');

$idServer  = $_POST['idServer'];

$ips = '';


   $requete = $bdd->prepare('select IP_IP from ip where id_Server_IP = ?');
   $requete->execute(array($idServer));
   
   echo '<option value="-1">Select IP...</option>';
   
   while($row = $requete->fetch())
   {
      echo '<option value="'.$row['IP_IP'].'">'.$row['IP_IP'].'</option>';
   }

?>