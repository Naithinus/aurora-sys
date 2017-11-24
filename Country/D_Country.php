<?php

 include_once('../Includes/sessionVerification.php'); 
 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 verify($monUrl);
 
 include('../Includes/bdd.php');
 
 $id_Country     = $_POST['id_Country'];
 
 $requete = $bdd->prepare('select flag_Country from country where id_Country = ?');
 $requete->execute(array($id_Country));
 $row = $requete->fetch();
 $oldLogo = $row['flag_Country'];
		 
 unlink('Images/'.$oldLogo);

		 
		 
 $requete        = $bdd->prepare('delete from country where id_Country = ?');
 $requete->execute(array($id_Country));
 header('location:ShowCountrys.php');
 
?>