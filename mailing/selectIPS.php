<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
 
 
header("Access-Control-Allow-Origin: *");
include('../Includes/bdd.php');
$servers = $_POST['cmbServers'];
$ips = null;
$selected=explode("\n",$_POST["selected"]);
$IPSelected=array();
foreach($selected as $s){
	$sel=trim($s);
	$IPSelected[]=$sel;
}
foreach($servers as $idServer)
{
	$requete = $bdd->prepare(
	'
		SELECT 	S.alias_Server,I.id_IP,I.IP_IP,D.name_Domain
		FROM 	server S,ip I,domain D
		WHERE 	S.id_Server		=		I.id_Server_IP
		AND		I.id_Domain_IP	=		D.id_Domain
		AND		S.id_Server		=		?
	');
	$requete->execute(array($idServer));
	while($row = $requete->fetch())
	{
		//echo '<option value='.$row['id_IP'].'>'.$row['IP_IP'].' ('.$row['name_Domain'].')</option>';
		if(in_array($row['IP_IP'],$IPSelected))
		{
			$ips.= '<option selected="selected" value='.$row['id_IP'].'>'.$row['IP_IP'].' ('.$row['name_Domain'].')</option>';
		}else{
			$ips.= '<option value='.$row['id_IP'].'>'.$row['IP_IP'].' ('.$row['name_Domain'].')</option>';
		}
	}
}
if($ips)
echo rtrim($ips);
?>