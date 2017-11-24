<?php

header("Access-Control-Allow-Origin: *");

include('bdd.php');

$idMailer = $_POST['idMailer'];

$requete = $bdd->prepare('update notification_mailer set is_Readed = 1 where id_mailer = ?');
$requete->execute(array($idMailer));

?>