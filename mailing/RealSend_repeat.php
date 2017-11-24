<?php
include('../Includes/bdd.php');
set_time_limit(0);
include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

$id_Send    = $_POST['id_Send'];
$fraction_post   = $_POST['fraction'];
$repeat = $_POST["repeat"];
$seed       = $_POST['seed'];
$xDelay     = $_POST['xDelay'];

$requete = $bdd->prepare('select * from send where id_Send = ?');
$requete->execute(array($id_Send));
$row = $requete->fetch();

$ips = $row['IPS_Send'];
$startFrom = $row['startFrom_Send'];
$mailerLastName = $_SESSION['lastName_Employer']; 
$tableName      = $mailerLastName.$id_Send;
$idISP          = $row['id_ISP_Send'];

$ipsSplit = explode(PHP_EOL,$ips);
$cptSeed  = 0;

$requeteCount = $bdd->query("select count(*) from $tableName");
$rowCount = $requeteCount->fetch();
$countOriginal=$rowCount[0]-$startFrom;

//Initialize CURL
$ch = curl_init();
$fraction=round($fraction_post/count($ipsSplit),0,PHP_ROUND_HALF_DOWN);

$chaine = 'id_Send='.$id_Send.'&start_From='.$startFrom.'&fraction='.$fraction.'&ips='.$ips.'&domain='
.$domain.'&server='.$aliasServer.'&tableName='.$tableName.'&idIP='.$idIP.'&cptSeed='.$cptSeed.'&seed='.$seed;
$url = 'http://'.$ipsSplit[0].'/exactarget/Send/RealSend_POST.php';

$fields = array(
	'id_Send'    => urlencode($id_Send),
	'start_From' => urlencode($startFrom),
	'fraction'   => urlencode($fraction),
	'ips' 		 => urlencode($ips),
	'domain'     => urlencode($domain),
	'server'     => urlencode($aliasServer),
	'tableName'  => urlencode($tableName),
	'idIP'       => urlencode($idIP),
	'cptSeed'    => urlencode($cptSeed),
	'xDelay'    =>  urlencode($xDelay),
	'seed'       => urlencode($seed)
);

foreach($fields as $key=>$value) { 
	$fields_string .= $key.'='.$value.'&'; 

	}
rtrim($fields_string, '&');

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
$result = curl_exec($ch);


if($idISP != 3)
{
	$startFrom+=$fraction;
}
echo $fraction;
curl_close ($ch); 

$requeteCount = $bdd->query("select count(*) from $tableName");
$rowCount = $requeteCount->fetch();
$countList = $rowCount[0]-$startFrom;
$emailSent=$fraction_post*$repeat;

if($idISP != 3)
{
	if($startFrom >= $rowCount[0])
	{
		$startFrom = $rowCount[0];
		echo "\nEmpty email list";
	}
	else
		echo "\n$emailSent emails sent successfully | Count: $countOriginal -> $countList\n\n";
}
else
{
	echo "\nWarm up finished | Count: $countOriginal -> $countList\n\n";
}	

$requete = $bdd->prepare('update send set startFrom_Send = ? where id_Send = ?');
$requete->execute(array($startFrom,$id_Send));

$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
$requete->execute(array($host,$id_Send));
echo 'End Of Send'; 

$countList = $rowCount[0]-$startFrom;
echo '/'.$countList;

?>