<?php
 
include('../Includes/bdd.php');
 
//$idList = $_POST['id_List'];
$idList = intval($_POST['id_List']);
/*for($i=intval($idList);$i<($idList+5);$i++)
{
	$requete = $bdd->prepare('delete from list where id_List=?');
	$requete->execute(array($i));
}*/

        $requete = $bdd->prepare('delete from list where id_List=?');
       	$requete->execute(array($idList));



?>
