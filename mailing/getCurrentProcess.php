<?php
  
  include_once('../Includes/sessionVerificationMailer.php'); 
  $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  verify($monUrl);
 
  Include('../Includes/bdd.php');
  $idSend = $_POST['idSend'];
  
  $requete = $bdd->prepare('select * from sendprocess where id_Send = ? and pid!=0');
  $requete->execute(array($idSend));
  while($row = $requete->fetch())
  {
	  echo $row['host'].'-'.$row['pid'].PHP_EOL;
  }
?>