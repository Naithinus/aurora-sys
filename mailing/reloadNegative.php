<?php

     include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
 
 include('../Includes/bdd.php');
 
 $idMailer = $_SESSION['id_Employer'];
 
    echo '<option value="0">Select Negative</option>';

						
						
	$requete = $bdd->prepare('select * from negative where id_mailer = ?');
	$requete->execute(array($idMailer));
	
	while($row = $requete->fetch())
	{
			echo '<option value="'.$row['id_negative'].'">'.$row['name_negative'].'</option>';
	} 

 
 
?>