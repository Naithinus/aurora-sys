<?php
    date_default_timezone_set('UTC');
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include_once('../Includes/bdd.php');
	
	$txtNotification = $_POST['txtNotification'];
	$mailersYES      = $_POST['cmbMailersYes'];
	
	if(isset($_POST['idNotification']))
	{
		$idN = $_POST['idNotification'];
		
		$requete = $bdd->prepare('update notification set text_Notification = ? where id_Notification = ?');
		$requete->execute(array($txtNotification,$idN));
		
		$requete = $bdd->prepare('delete from notification_mailer where id_notification = ?');
		$requete->execute(array($idN));
		
		foreach($mailersYES as $idMailer)
		{
			$requete = $bdd->prepare('insert into notification_mailer Values(?,?,?,?)');
			$requete->execute(array(NULL,$idN,$idMailer,0));
		}
		
	}
	
	else
	{
		$requete = $bdd->prepare('insert into notification Values(?,?)');
		$requete->execute(array(NULL,$txtNotification));
		$idN  = $bdd->lastInsertId();
		
		foreach($mailersYES as $idMailer)
		{
			$requete = $bdd->prepare('insert into notification_mailer Values(?,?,?,?)');
			$requete->execute(array(NULL,$idN,$idMailer,0));
		}
	}

    header('location:ShowNotifications.php');	
	
?>	