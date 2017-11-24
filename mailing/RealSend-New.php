<?php
	
	set_time_limit(0);
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

	
    include('../Includes/bdd.php');
	
	$id_Send    = $_POST['id_Send'];
	
	$pid        	= getmypid();
	$host			= $_SERVER['HTTP_HOST'];
	
	$requete = $bdd->prepare('insert into sendprocess Values(?,?,?,?)');
	$requete->execute(array(NULL,$id_Send,$host,$pid));
	
    
    $fraction   = $_POST['fraction'];
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
	
	//Initialize CURL
    $ch = curl_init();
 

    foreach($ipsSplit as $ip)
    {
      $ip 		   = trim($ip);
	  $vmta		   = $ip;
	  
	  $explodeVMTA = explode('-',$ip);
	  $ip          = $explodeVMTA[1];
	  
      $requeteIP = $bdd->prepare('select i.id_IP,s.alias_Server,d.name_Domain from domain d , ip i , server s where i.id_Domain_IP = d.id_Domain and i.IP_IP = ? and i.id_Server_IP = s.id_Server');
	  $requeteIP->execute(array($ip));
      $rowIP     = $requeteIP->fetch();	
      $domain    = $rowIP['name_Domain'];
      $idIP      = $rowIP['id_IP'];
	  $aliasServer = $rowIP['alias_Server']; 
 
      // ------------------- BEGIN IP  -------------------
	  
      $chaine = 'id_Send='.$id_Send.'&start_From='.$startFrom.'&fraction='.$fraction.'&ip='.$ip.'&domain='.$domain.'&server='.$aliasServer.'&tableName='.$tableName.'&idIP='.$idIP.'&cptSeed='.$cptSeed.'&seed='.$seed;
	
      /*curl_setopt($ch, CURLOPT_URL,'http://'.$ip.'/exactarget/Send/RealSend_POST.php');
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS,$chaine);			
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  $server_output = curl_exec ($ch);
	 
	  $cptSeed = trim($server_output);
	  echo $cptSeed; */
	  
	    $url = 'http://'.$ip.'/exactarget/Send/RealSend_POST-New.php';
			$fields = array(
				'id_Send'    => urlencode($id_Send),
				'start_From' => urlencode($startFrom),
				'fraction'   => urlencode($fraction),
				'ip' 		 => urlencode($ip),
				'vmta' 		 => urlencode($vmta),
				'domain'     => urlencode($domain),
				'server'     => urlencode($aliasServer),
				'tableName'  => urlencode($tableName),
				'idIP'       => urlencode($idIP),
				'cptSeed'    => urlencode($cptSeed),
				'xDelay'    =>  urlencode($xDelay),
				'seed'       => urlencode($seed)
		);


		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');


		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	    $result = curl_exec($ch);
		$cptSeed = trim($result);
		echo $cptSeed;
		
      // ------------------- END IP  -------------------	
	  
		  if($idISP != 3)
		  {
				$startFrom+=$fraction;
		  }
    }   
	
	//Close CURL
    curl_close ($ch);  
	   
	
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
 
	$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
	$requete->execute(array($host,$id_Send));
    echo 'End Of Send'; 
	
	$countList = $rowCount[0]-$startFrom;
	echo '/'.$countList;
    
?>