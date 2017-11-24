<?php
set_time_limit(0);
include_once('../Includes/sessionVerificationMailer2.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 if (!verify($monUrl)) {
	echo "Mailer not verified";
 }

include('../Includes/bdd.php');

$pid = getmypid(); // PHP Process ID
$host = $_SERVER['HTTP_HOST']; // Host Server

$id_Send		 = $_POST['id_Send']; // Offer ID
$fraction_post = $_POST['fraction'];
$repeat 		 = $_POST["repeat"];
$seed 		 = $_POST['seed'];
$xDelay 		 = $_POST['xDelay'];
if (empty($fraction_post)){
	$fraction_post=0;
}



// Update sendprocess table
$requete = $bdd->prepare('insert into sendprocess Values(?,?,?,?)');
$requete->execute(array(NULL,$id_Send,$host,$pid));

// Get send info from SEND table
$requete = $bdd->prepare('select * from send where id_Send = ?');
$requete->execute(array($id_Send));

$row = $requete->fetch();
	$ips 		  = $row['IPS_Send']; // VMTAs
	$startFrom 	  = $row['startFrom_Send']; // Start from 
	$mailerLastName = $_SESSION['lastName_Employer']; // Mailer name
	$tableName      = $mailerLastName.$id_Send; // Name of the table where the send info will be stored
	$idISP          = $row['id_ISP_Send']; // ID of the ISP

// Converts IPS into an array
$ipsSplit = explode(PHP_EOL,$ips);
$cptSeed  = 0;

// Divide fractions equally for each IP
if ($fraction_post==0){
	$fraction=0;
} else{
	$fraction=round($fraction_post/count($ipsSplit),0,PHP_ROUND_HALF_DOWN);
}

if($ips==0){
	echo "NO VMTA SELECTED\n\n";
	die();
} else {
	echo count($ipsSplit)." VMTA selected\n\n";
}

//Initialize CURL
$ch = curl_init();

// Repeat loop
for ($i=1;$i<=$repeat;$i++){
	// For each IP
    for ($i=0;$i<=count($ipsSplit)-1;$i++)
    {
		$ip = trim($ipsSplit[$i]); // trim IP from any space
		
		$requeteIP = $bdd->prepare('select i.id_IP,s.alias_Server,d.name_Domain from domain d,ip i,server s 
								where i.id_Domain_IP = d.id_Domain and i.IP_IP = ? and i.id_Server_IP = s.id_Server');
		$requeteIP->execute(array($ip));
		
		$rowIP     = $requeteIP->fetch();	
		$domain    = $rowIP['name_Domain'];
		$idIP      = $rowIP['id_IP'];
		$aliasServer = $rowIP['alias_Server']; 
	 
		// ------------------- BEGIN IP  -------------------

		$chaine = 'id_Send='.$id_Send.'&start_From='.$startFrom.'&fraction='.$fraction.'&ip='.$ip.'&domain='.$domain.'&server='.$aliasServer.'&tableName='.$tableName.'&idIP='.$idIP.'&cptSeed='.$cptSeed.'&seed='.$seed;

		$url = 'http://'.$ip.'/exactarget/Send/RealSend_POST.php';
		$fields = array(
			'id_Send'    => urlencode($id_Send),
			'start_From' => urlencode($startFrom),
			'fraction'   => urlencode($fraction),
			'ip' 		 => urlencode($ip),
			'domain'     => urlencode($domain),
			'server'     => urlencode($aliasServer),
			'tableName'  => urlencode($tableName),
			'idIP'       => urlencode($idIP),
			'cptSeed'    => urlencode($cptSeed),
			'xDelay'    =>  urlencode($xDelay),
			'seed'       => urlencode($seed)
		);
		
		foreach($fields as $key=>$value){ 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');

		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		$time=$info['total_time'];
		$cptSeed = trim($result);
		// echo $cptSeed;
		
		// ------------------- END IP  -------------------	

		if($idISP != 3)
		{
			$startFrom+=$fraction;
		}
		echo "$ip => $fraction emails in $time \n";
	}  
}	

//Close CURL
curl_close ($ch); 

$requeteCount = $bdd->query("select count(*) from $tableName");
$rowCount	  = $requeteCount->fetch();
$countList = $rowCount[0]-$startFrom;

if($idISP != 3)
{
	if($startFrom >= $rowCount[0])
	{
	  $startFrom = $rowCount[0];
	  echo '\nList Empty | $countList\n\n';
	}
	else {
	  echo "\nSend Finished | $countList\n\n";
	}
}
else
{
	echo '\nWarm up finished | $countList\n\n';
}	

$requete = $bdd->prepare('update send set startFrom_Send = ? where id_Send = ?');
$requete->execute(array($startFrom,$id_Send));

$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
$requete->execute(array($host,$id_Send));



?>