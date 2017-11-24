<?php
/*
function	fill_combo_ips_send($p_id_send)
	{
		include('../Includes/bdd.php');
		$result	=	null;
		$ips 	= 	get_ips_send_by_id_send($p_id_send);
		if(!is_null($ips))
		{
			foreach($ips as $addresse_ip)
			{
				$requete = $bdd->prepare(
				'
					SELECT 	S.alias_Server,I.id_IP,I.IP_IP,D.name_Domain
					FROM 	server S,ip I,domain D
					WHERE 	S.id_Server		=		I.id_Server_IP
					AND		I.id_Domain_IP	=		D.id_Domain
					AND		I.IP_IP			=		?
					LIMIT	0,1
				');
				$requete->execute(array(trim($addresse_ip)));
				$row = $requete->fetch();
				
				$result.="IP: ".$row['IP_IP'].' | ';
			}
		}

		if(!is_null($result))
			echo $result;
	}
	
	function	get_ips_send_by_id_send($p_id_send)
	{
		include('../Includes/bdd.php');
		$result		=	null;
		$requete 	= 	$bdd->prepare(
		'
			SELECT 	S.IPS_Send
			FROM 	send S
			WHERE 	S.id_Send		=		?
		');
		$requete->execute(array($p_id_send));
		$row = $requete->fetch();
		if($row)
		{
			$strIPs	=	trim($row['IPS_Send']);
			if(!empty($strIPs))
				$result	=	explode(PHP_EOL,$strIPs);
			else
				$result	=	null;
		}
		
		return $result;
	}  
fill_combo_ips_send("6379");
*/
include('../Includes/bdd.php');
$result		=	null;
$requete 	= 	$bdd->prepare(
'
	SELECT 	S.IPS_Send
	FROM 	send S
	WHERE 	S.id_Send		=		?
');
$requete->execute(array("6379"));
$row = $requete->fetch();
if($row)
{
	$strIPs	=	trim($row['IPS_Send']);
	if(!empty($strIPs))
		$result	=	explode(PHP_EOL,$strIPs);
	else
		$result	=	null;
}

foreach($result as $addresse_ip)
			{
				echo $addresse_ip;
				$requete = $bdd->prepare(
				'
					SELECT 	S.alias_Server,I.id_IP,I.IP_IP,D.name_Domain
					FROM 	server S,ip I,domain D
					WHERE 	S.id_Server		=		I.id_Server_IP
					AND		I.id_Domain_IP	=		D.id_Domain
					AND		I.IP_IP			=		?
					LIMIT	0,1
				');
				$requete->execute(array(trim($addresse_ip)));
				$row = $requete->fetch();
				
				$result.="IP: ".$row['IP_IP'].' | ';
			}
?>