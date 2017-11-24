<?php
 include('../Includes/bdd.php');
 
 
 $id_Offer = $_POST['id_Offer'];
 
 $requete = $bdd->prepare('delete from froms where id_Offer_From = ?');
 $requete->execute(array($id_Offer));
 
 $requete = $bdd->prepare('delete from subjects where id_Offer_Subject = ?');
 $requete->execute(array($id_Offer));
 
 $requete = $bdd->prepare('delete from creatives where id_Offer_Creative = ?');
 $requete->execute(array($id_Offer));
 
 $requete = $bdd->prepare('delete from offer where id_Offer = ?');
 $requete->execute(array($id_Offer));
 
 
 header('location:ShowOffers.php');
?>