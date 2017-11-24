<?php
	ini_set('display_errors', 1); 
	error_reporting(E_ALL);
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 


    include('../Includes/bdd.php');
	
    $mails = $_POST['txtListWarmUP'];
    $mailerLastName = 	$_SESSION['lastName_Employer']; 
	$listName       = 	$mailerLastName.'WU';
	$idEmplyer		=	$_SESSION['id_Employer']; 
	
	$requete = $bdd->prepare('select id_List from list where name_List = ?');
	$requete->execute(array($listName));
	$row = $requete->fetch();
	$idList = $row['id_List'];
	
	
	
	
	
	$requete = $bdd->prepare('delete from email where id_List_Email = ?');
	$requete->execute(array($idList));
	
	$requete = $bdd->prepare('delete from email_list_warmup where id_employer = ?');
	$requete->execute(array($idEmplyer));
	
	$explode = explode(PHP_EOL,$mails);
	foreach($explode as $line)
	{
		if(!empty($line))
		{
			$tab = explode('/',$line);
			if(count($tab)==2)
			{
				$mail		=	$tab[0];
				$password	=	$tab[1];
				
				
				//Suppression de l'email :
				$reqDeleteEmail = $bdd->prepare('delete from email where email_Email = ?');
				$reqDeleteEmail->execute(array(trim($mail)));
				
				//Insertion :
				$requete 	= $bdd->prepare('insert into email(email_Email,id_List_Email) Values(?,?)');
				$requete->execute(array(trim($mail),$idList));
				$id_inserted_email = $bdd->lastInsertId();
				if($id_inserted_email>0)
				{
					$requete = $bdd->prepare('insert into email_list_warmup Values(?,?,?)');
					$requete->execute(array($idEmplyer,$id_inserted_email,$password));
				}
			}
		}
	}
    	
    header('location:../indexOfficial.php');
?>