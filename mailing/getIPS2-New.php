<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
 
header("Access-Control-Allow-Origin: *");
include('../Includes/bdd.php');
$servers = $_POST['cmbServers'];
$ips = null;

$ips='<option selected disabled>Original VMTAS</option>';

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
		$ips.='<option value=mta-'.$row['IP_IP'].'>'.$row['alias_Server'].' | '.$row['IP_IP'].' | '.$row['name_Domain'].'</option>';
	}
	
}

$ips.='<option selected disabled>Fake VMTAS</option>';

foreach($servers as $idServer)
{
	$requete = $bdd->prepare('select v.*,s.alias_Server from vmta v,server s where v.id_Server = s.id_Server and v.id_Server = ? and id_Mailer = ?');
	$requete->execute(array($idServer,$_SESSION['id_Employer']));
	
	while($row = $requete->fetch())
	{
		$ips.='<option value=vmta-'.$row['ip'].'-'.$row['id_Mailer'].'>'.$row['alias_Server'].' | '.$row['ip'].' | '.$row['domain'].'</option>';
	}
}


if($ips)
	echo rtrim($ips);
else
	echo "No result";
?>