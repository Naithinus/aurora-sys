<?php
set_time_limit(0);
include('../Includes/bdd.php');
include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
$id_Send    = $_POST['id_Send'];
$host			= $_SERVER['HTTP_HOST'];
$fraction   = $_POST['fraction'];
$seed       = $_POST['seed'];
if(empty($seed)){
	$seed=0;
}
$xDelay     = $_POST['xDelay'];
$host = $_SERVER['HTTP_HOST'];
if(empty($xDelay)){
	$xDelay=1000;
}

// GET SEND DETAILS
$requete = $bdd->prepare('select * from send where id_Send = ?');
$requete->execute(array($id_Send));
$row = $requete->fetch();
$ips = $row['IPS_Send'];
$ips= str_ireplace("\r\n"," ",$ips);
$ipsSplit = explode(PHP_EOL,$ips);
$startFrom = $row['startFrom_Send'];
$mailerLastName = $_SESSION['lastName_Employer'];
$tableName      = $mailerLastName.$id_Send;
$idISP          = $row['id_ISP_Send'];
$cptSeed  = 0;
	
// Separate Servers
$totalIPs=0;
$ipList=array();
foreach($ipsSplit as $IP){
	$getIP 	= $bdd->query("select id_Server_IP from ip where IP_IP ='$IP'");
	$line  	= $getIP->fetch();
	$id 	= $line["id_Server_IP"];
	$ipList[$id][]= $IP;
	$totalIPs++;
}

// Separate first IP
$listIP=array();
$countIPs=array();
foreach($ipList as $serverID => $serverIP){
	$w=0;
	foreach($serverIP as $s){
		if($w==0){
			$listIP[$serverID][0]=$s;
			$listIP[$serverID][1]=$s." ";
		}else{
			$listIP[$serverID][1].=$s." ";
		}
		$w++;
	}
	$listIP[$serverID][1]=trim($listIP[$serverID][1]);
	$countIPs[$serverID]=$w;
}
	
// Send to each Server
$s=$startFrom;
$firstip="";
foreach($listIP as $idserver => $serv){
	$s++;
	$x=0;
	$http="";
	$f=($countIPs[$idserver]*$fraction)/$totalIPs;
	$f=round($f);
	foreach($serv as $ip){
		$ip=trim($ip);
		if($x==0){
			$socket = fsockopen($ip, 80);
			$firstip=$ip;
			if (!$socket) {
				echo "Can't connect to $ip \n";
				break;
			}
			$x++;
		}else{
			if(empty($ip)){
				$ip=$firstip;
			}
			$vars = array(
				'id_Send'    => urlencode($id_Send),
				'start_From' => urlencode($s),
				'fraction'   => urlencode($f),
				'ips' 		 => urlencode($ip),
				'tableName'  => urlencode($tableName),
				'cptSeed'    => urlencode($cptSeed),
				'xDelay'    =>  urlencode($xDelay),
				'seed'       => urlencode($seed),
				'user'		=>	urlencode($mailerLastName)
			);
			$content = http_build_query($vars);
			$http.="POST /exactarget/Send/RealSend_Auto.php HTTP/1.1\r\n";
			$http.="Host: $host\r\n";
			$http.="Content-Type: application/x-www-form-urlencoded\r\n";
			$http.="Content-Length: ".strlen($content)."\r\n";
			$http.="Connection: close\r\n\r\n";
			$http.=$content."\r\n\r\n";
			fputs($socket, $http);
			//header('Content-type: text/plain');
			/*while (!feof($socket)) {
				echo fgets($socket,4096);
			}*/
			$elapsed=($fraction+$seed)*($xDelay/1000000);
			if($seed!=0){
				$testSeed=round($fraction/$seed);
			}else{
				$testSeed=$seed;
			}
			echo "Send started for $f + $testSeed data at server $firstip\n";
			echo "Estimated time : ".$elapsed." seconds\n\n";
			fclose($socket);
			$socket=null;
			$http=null;
			$x=0;
			$s=$s+$f;
		}
	}
	
}
// ------------------- END IP  -------------------	

if($idISP != 3)
{
$startFrom+=$fraction;
}


$requeteCount = $bdd->query("select count(*) from $tableName");
$rowCount	  = $requeteCount->fetch();
if($idISP != 3)
{

if($startFrom >= $rowCount[0])
{
$startFrom = $rowCount[0];
echo 'List Empty';
}
else
	echo 'Send Finished';
}
else
{
	echo 'Warm up finished';
}	

$requete = $bdd->prepare('update send set startFrom_Send = ? where id_Send = ?');
$requete->execute(array($startFrom,$id_Send));

//$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
//$requete->execute(array($host,$id_Send));
//echo 'End Of Send'; 

$countList = $rowCount[0]-$startFrom;
echo '/'.$countList;
?>