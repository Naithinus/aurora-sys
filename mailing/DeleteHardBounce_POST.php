<?php

set_time_limit(0);

include('../Includes/bdd.php');

$file = $_FILES['fileHardBounce']['name'];

$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));


move_uploaded_file($_FILES['fileHardBounce']['tmp_name'],'hardBounce/'.$file);
   
$fileHardBounce	= fopen('hardBounce/'.$file,'a+');  

while($line = fgets($fileHardBounce))
{
    $mail = trim($line);
	$requete = $bdd->prepare('delete from email where email_Email = ?');
	$requete->execute(array($mail));
} 

header('location:../indexOfficial.php');

?>