<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

include('../Includes/bdd.php');

$idSend    = $_POST['idS'];
$ips       = $_POST['ips'];
$emailTest = $_POST['emailTest'];
$startFrom = $_POST['startFrom'];
 
$requete = $bdd->prepare('update send set IPS_Send = ? , emailTest_Send = ? , startFrom_Send = ? where id_Send = ?');
$requete->execute(array($ips,$emailTest,$startFrom,$idSend));

echo 'Update Done';

?>