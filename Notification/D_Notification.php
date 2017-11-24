<?php

include('../Includes/bdd.php');

$idNotification = $_POST['id_Notification'];

$requete = $bdd->prepare('delete from notification where id_Notification = ?');
$requete->execute(array($idNotification));


$requete = $bdd->prepare('delete from notification_mailer where id_notification = ?');
$requete->execute(array($idNotification));



?>