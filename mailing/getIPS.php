<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
 
header("Access-Control-Allow-Origin: *");
include('../Includes/bdd.php');
$servers = $_POST['cmbServers'];
$ips = '';

foreach($servers as $idServer)
{
   $requete = $bdd->prepare('select IP_IP from ip where id_Server_IP = ?');
   $requete->execute(array($idServer));
   while($row = $requete->fetch())
   {
     $ips.=$row['IP_IP'].PHP_EOL;
   }
}

echo rtrim($ips);
?>