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
$xDelay		 = $_POST['xDelay'];

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
	$isSender		 = $row['is_Sender'];
	$idFrom         = $row['id_From_Send'];
	$subject        = $row['id_Subject_Send'];
	$creative 		 = $row['id_Creative_Send'];
	$isAR   		 = $row['isAR'];
	$ARList  		 = $row['ARList'];
	$id_negative  	 = $row['id_negative'];
	$contentNegative = '';

	$emailsTests      = array();

$emailTestPost	   = $row['emailTest_Send'];
$explodeEmailTest = explode(PHP_EOL,$emailTestPost);
 
 // EMAIL TESTS //
foreach($explodeEmailTest as $emt)	
{
 $emailsTests[]   = $emt;
} 

if($idISP != 3)
{

  $subrequete = $bdd->prepare('select email from seedadmin where id_isp = ?');
  $subrequete->execute(array($idISP));
  $subRow     = $subrequete->fetch();
  
	if($subRow)
	{  
		$seedsAdmin = $subRow['email'];
		$explodeSeedAdmin = explode(';',$seedsAdmin);
		foreach($explodeSeedAdmin as $seedAdmin)
		{
			$emailsTests[] = $seedAdmin;
		}
	}
  
}

if($id_negative != 0)
{
	$requeteNegative = $bdd->prepare('select file_negative from negative where id_negative = ?');
	$requeteNegative->execute(array($id_negative));
	$rowNegative     =  $requeteNegative->fetch();
	$fileName 		 =  $rowNegative['file_negative'];
	$contentNegative = file_get_contents('http://79.143.189.72/negatives/'.$fileName);
}
 
$returnPathOriginal = $row['returnPath_Send'];
$headerOriginal = $row['header_Send'];
$bodyOriginal = $row['body_Send'];

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
// $ch = curl_init();

// Repeat loop
for ($i=1;$i<=$repeat;$i++){
	// For each IP
    foreach ($ipsSplit as $ips)
    {
		$ip = trim($ips); // trim IP from any space
		
		$requeteIP = $bdd->prepare('select i.id_IP,s.alias_Server,d.name_Domain from domain d,ip i,server s 
								where i.id_Domain_IP = d.id_Domain and i.IP_IP = ? and i.id_Server_IP = s.id_Server');
		$requeteIP->execute(array($ip));
		
		$rowIP     = $requeteIP->fetch();	
		$domain    = $rowIP['name_Domain'];
		$idIP      = $rowIP['id_IP'];
		$aliasServer = $rowIP['alias_Server']; 
	 
		/* ------------------- BEGIN IP  -------------------

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
		
		// ------------------- END IP  -------------------	*/
		if($isAR == 0)
	{
		@$fp = fsockopen($ip, 25, $errno, $errstr);
		$requeteSend = $bdd->query("select * from $tableName limit $startFrom,$fraction");
		while($rowSend = $requeteSend->fetch())
		{
		   $idList     = $rowSend['id_List_Email'];
		   $idTypeList = $rowSend['id_Type_List'];
		   $header     =    $headerOriginal;
		   $body =      $bodyOriginal;
		 
			// Replace Random IN Header
			preg_match_all('#\[[a-zA-Z0-9/]+\]#',$header,$out);
		
			foreach($out[0] as $tag)
			{
				$header =    str_replace($tag,strtolower($tag),$header);
			}
		
			preg_match_all('#\[[a-zA-Z0-9/]+\]#',$body,$out);
		
			foreach($out[0] as $tag)
			{
				$body =    str_replace($tag,strtolower($tag),$body);
			}
		
			// Random In Header
			preg_match_all('[random[cd]+/[0-9]+]',$header,$out);
					   
			$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
			$chaineDigitals ='0123456789';
			$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
			   
			foreach($out[0] as $random)
			{
				$splitRandom = explode('/',$random);
					 
				$typeRandom = $splitRandom[0];
				$numberRandom = $splitRandom[1];
				$concat      = '';
					 
				switch($typeRandom)
				{
					case 'randomc':
						
						for($i=0;$i<$numberRandom;$i++)
						{
								 $rand = rand(0,strlen($chaineChars)-1);
								 $concat.=$chaineChars[$rand];
						}
								
						$header = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$header,1);
					break;		 
					case 'randomd':
					
						for($i=0;$i<$numberRandom;$i++)
						{
								 $rand = rand(0,strlen($chaineDigitals)-1);
								 $concat.=$chaineDigitals[$rand];
						}
						
						$header = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$header,1);
						
					break;		 
					case 'randomcd':
					
							for($i=0;$i<$numberRandom;$i++)
							{
								 $rand = rand(0,strlen($chaineCD)-1);
								 $concat.=$chaineCD[$rand];
							}
							
							$header = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$header,1);
							
					break;
				}
			}
			// Random In Body
			preg_match_all('[random[cd]+/[0-9]+]',$body,$out);
					   
			$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
			$chaineDigitals ='0123456789';
			$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
		   
			foreach($out[0] as $random)
			{
				$splitRandom = explode('/',$random);
					 
				$typeRandom = $splitRandom[0];
				$numberRandom = $splitRandom[1];
				$concat      = '';
				 
				switch($typeRandom)
				{
					case 'randomc':
						
						for($i=0;$i<$numberRandom;$i++)
						{
								 $rand = rand(0,strlen($chaineChars)-1);
								 $concat.=$chaineChars[$rand];
						}
								
						$body = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$body,1);
					break;	 
					case 'randomd':
					
						for($i=0;$i<$numberRandom;$i++)
						{
								 $rand = rand(0,strlen($chaineDigitals)-1);
								 $concat.=$chaineDigitals[$rand];
						}
						
						$body = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$body,1);
						
					break; 
					case 'randomcd':
					
							for($i=0;$i<$numberRandom;$i++)
							{
								 $rand = rand(0,strlen($chaineCD)-1);
								 $concat.=$chaineCD[$rand];
							}
							
							$body = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$body,1);
							
					break;
				}
			}

			if($id_negative != 0)
			{
				$body =      preg_replace('#\[nega\]#',$contentNegative,$body);
			}

			$to         = trim($rowSend['email_Email']);

			$returnPath = $returnPathOriginal;
			$returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
			$idEmail = $rowSend['id_Email'];


			$date   =    date(DATE_RFC2822);
			$header =    preg_replace('#\[date\]#',$date,$header);

			$header =    preg_replace('#\[sr\]#',$aliasServer,$header);
			$header =    preg_replace('#\[ip\]#',$ip,$header);
			$header =    preg_replace('#\[to\]#',$to,$header);
			$header =    preg_replace('#\[domain\]#',$domain,$header);


			$body =      preg_replace('#\[domain\]#',$domain,$body);
			$body =      preg_replace('#\[idsend\]#',$id_Send,$body);
			$body =      preg_replace('#\[idemail\]#',$idEmail,$body);
			$body =      preg_replace('#\[idfrom\]#',$idFrom,$body);
			$body =      preg_replace('#\[idsubject\]#',$subject,$body);
			$body =      preg_replace('#\[idcreative\]#',$creative,$body);
			$body =      preg_replace('#\[idip\]#',$idIP,$body);
			$body =      preg_replace('#\[ip\]#',$ip,$body);

			$split = explode(PHP_EOL,$header);
			$from = '';
			   
			$fromName  = '';
			$fromEmail = '';
		  
			foreach($split as $line)
			{
				$params = explode(':',$line);
				if(strtolower($params[0]) == 'fromname')
					$fromName = trim($params[1]);

				if(strtolower($params[0]) == 'fromemail')
				{
				 if($isSender==0)
				  $fromEmail = $params[1];
				 else
				  $fromEmail = trim($rowSend['sender']);
				  $body =      preg_replace('#\[sender\]#',trim($fromEmail),$body);
				}
	   }
	   
	   $from  = $fromName.$fromEmail;
	   $from  = str_replace("\n", '', $from);
	   
	   $headerTelNet = '';
	   
	   foreach($split as $line)
	   {
	      $params = explode(':',$line,2);
		  
	      if(strtolower($params[0]) == 'fromname')
		   $headerTelNet.="from:$from\n";
		  else
		  {
		    if(strtolower($params[0]) != 'fromemail')
		      $headerTelNet.=$params[0].':'.trim($params[1])."\n";
		  }
		  
	   }
	   
	   $headerTelNet.="x-job:$id_Send-$idEmail-$idList-$idTypeList\nx-virtual-mta: mta-$ip\n$body\n.\n";
	   
	   //echo $headerTelNet.PHP_EOL.'------------'.PHP_EOL;
	   
$telnet    = array();
$telnet[0] = "telnet $ip\r\n";
$telnet[1] = "HELO $domain\r\n";
$telnet[2] = "MAIL FROM:$returnPath\r\n";
$telnet[3] = "RCPT TO:$to\r\n";
$telnet[4] = "DATA\r\n";
$telnet[5] = $headerTelNet;



$count=0;

if (!$fp)
{
	echo 'connection fail'."$errstr ($errno)";
	return false;   
}

else
{


	
			    foreach ($telnet as $current) 
			    {       
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
					$g=substr($smtpOutput, 0, 3);


					if (!(($g == "220") || ($g == "250") || ($g == "354")|| ($g == "500"))) 
					{
						//echo 'connection 2 fail';
						 //return false; 
					}
				}

				usleep($xDelay);
	
	

}





$cptSeed++;
	   if($cptSeed==$seed)
	   {
				
				   $cptSeed = 0;
				   
				   
				   
			
			foreach($emailsTests as $emailTest)
			{
					$emailTest = trim($emailTest);
					
				   $header =    $headerOriginal;
				   $body   =      $bodyOriginal;
       preg_match_all('#\[[a-zA-Z0-9/]+\]#',$header,$out);
		
		foreach($out[0] as $tag)
		{
			$header =    str_replace($tag,strtolower($tag),$header);
		}
		
		preg_match_all('#\[[a-zA-Z0-9/]+\]#',$body,$out);
		
		foreach($out[0] as $tag)
		{
			$body =    str_replace($tag,strtolower($tag),$body);
		}
		
		// Random In Header
		preg_match_all('[random[cd]+/[0-9]+]',$header,$out);
				   
		$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
		$chaineDigitals ='0123456789';
		$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
		   
		foreach($out[0] as $random)
		{
			$splitRandom = explode('/',$random);
				 
			$typeRandom = $splitRandom[0];
			$numberRandom = $splitRandom[1];
			$concat      = '';
				 
			switch($typeRandom)
			{
				
				case 'randomc':
					
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineChars)-1);
							 $concat.=$chaineChars[$rand];
					}
							
					$header = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$header,1);
					
				break;
						 
						 
				case 'randomd':
				
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineDigitals)-1);
							 $concat.=$chaineDigitals[$rand];
					}
					
					$header = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$header,1);
					
				break;
						 
						 
				case 'randomcd':
				
						for($i=0;$i<$numberRandom;$i++)
						{
							 $rand = rand(0,strlen($chaineCD)-1);
							 $concat.=$chaineCD[$rand];
						}
						
						$header = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$header,1);
						
				break;
					 
					 
			}
			
		}
		 

		
		
		// Random In Body
		preg_match_all('[random[cd]+/[0-9]+]',$body,$out);
				   
		$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
		$chaineDigitals ='0123456789';
		$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
		   
		foreach($out[0] as $random)
		{
			$splitRandom = explode('/',$random);
				 
			$typeRandom = $splitRandom[0];
			$numberRandom = $splitRandom[1];
			$concat      = '';
				 
			switch($typeRandom)
			{
				
				case 'randomc':
					
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineChars)-1);
							 $concat.=$chaineChars[$rand];
					}
							
					$body = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$body,1);
					
				break;
						 
						 
				case 'randomd':
				
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineDigitals)-1);
							 $concat.=$chaineDigitals[$rand];
					}
					
					$body = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$body,1);
					
				break;
						 
						 
				case 'randomcd':
				
						for($i=0;$i<$numberRandom;$i++)
						{
							 $rand = rand(0,strlen($chaineCD)-1);
							 $concat.=$chaineCD[$rand];
						}
						
						$body = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$body,1);
						
				break;
					 
					 
			}
			
		}
		
		
		
		if($id_negative != 0)
		{
			$body =      preg_replace('#\[nega\]#',$contentNegative,$body);
		}
		
	   
				   $to = $emailTest;
				   
				   $returnPath = $returnPathOriginal;
			       $returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
				   
				   $date = date(DATE_RFC2822);
				   $header =    preg_replace('#\[date\]#',$date,$header);
				   
				   $header =    preg_replace('#\[sr\]#',$aliasServer,$header);
				   $header =    preg_replace('#\[ip\]#',$ip,$header);
				   $header =    preg_replace('#\[to\]#',$to,$header);
				   $header =    preg_replace('#\[domain\]#',$domain,$header);
				   
				   
				   $body =      preg_replace('#\[domain\]#',$domain,$body);
				   $body =      preg_replace('#\[idsend\]#',$id_Send,$body);
				   $body =      preg_replace('#\[idemail\]#',0,$body);
				   $body =      preg_replace('#\[idfrom\]#',$idFrom,$body);
				   $body =      preg_replace('#\[idsubject\]#',$subject,$body);
				   $body =      preg_replace('#\[idcreative\]#',$creative,$body);
				   $body =      preg_replace('#\[idip\]#',$idIP,$body);
				   $body =      preg_replace('#\[ip\]#',$ip,$body);
				   
				   $split = explode(PHP_EOL,$header);
				   $from = '';
				   
					$fromName  = '';
					$fromEmail = '';
					  
				   foreach($split as $line)
				   {
					  $params = explode(':',$line);
					  
					 
					  if(strtolower($params[0]) == 'fromname')
					   $fromName = trim($params[1]);
					  
					  if(strtolower($params[0]) == 'fromemail')
					  {
							 if($isSender==0)
							  $fromEmail = $params[1];
							 else
							  $fromEmail = trim($rowSend['sender']);
							
							$body =      preg_replace('#\[sender\]#',$fromEmail,$body);
					  }
					  
				   }
				   
				   $from=$fromName.$fromEmail;
				   $from  = str_replace("\n", '', $from);
				   
				   $headerTelNet = '';
				   
				   foreach($split as $line)
				   {
					  $params = explode(':',$line,2);
					  
					  if(strtolower($params[0]) == 'fromname')
					   $headerTelNet.="from:$from\n";
					  else
					  {
						if(strtolower($params[0]) != 'fromemail')
						  $headerTelNet.=$params[0].':'.trim($params[1])."\n";
					  }
					  
				   }
				   
				   $headerTelNet.="x-job:$id_Send-0-0-0\nx-virtual-mta: mta-$ip\n$body\n.\n";
				   
				  //echo $header.PHP_EOL.'------------'.PHP_EOL; 
				   
				   $telnet    = array();
				   $telnet[0] = "telnet $ip\r\n";
				   $telnet[1] = "HELO $domain\r\n";
				   $telnet[2] = "MAIL FROM:$returnPath\r\n";
				   $telnet[3] = "RCPT TO:$to\r\n";
				   $telnet[4] = "DATA\r\n";
				   $telnet[5] = $headerTelNet;
				   
				   
				   
				  $count=0;

if (!$fp)
{
	
	return false;   
}

else
{


	
			    foreach ($telnet as $current) 
			    {       
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
					$g=substr($smtpOutput, 0, 3);


					if (!(($g == "220") || ($g == "250") || ($g == "354")|| ($g == "500"))) 
					{
						//echo 'connection 2 fail';
						 //return false; 
					}
				}

				usleep($xDelay);
	
	

}
		   
	   }
	   
	   }
	   
	   
	   
	  
		
	
}
fclose($fp);
}















// AUTO RESPONSE













else
{

@$fp = fsockopen($ip, 25);
 
 $explodeAR = explode(PHP_EOL,$ARList);
 $indexAR   = 0;
 
 $requeteSend = $bdd->query("select * from $tableName limit $startFrom,$fraction");
	while($rowSend = $requeteSend->fetch())
	{
	   
	   $idList     = $rowSend['id_List_Email'];
	   $idTypeList = $rowSend['id_Type_List'];
	   $header     = $headerOriginal;
	   $body 	   = $bodyOriginal;
	   
	   
	    preg_match_all('#\[[a-zA-Z0-9/]+\]#',$header,$out);
		
		foreach($out[0] as $tag)
		{
			$header =    str_replace($tag,strtolower($tag),$header);
		}
		
		preg_match_all('#\[[a-zA-Z0-9/]+\]#',$body,$out);
		
		foreach($out[0] as $tag)
		{
			$body =    str_replace($tag,strtolower($tag),$body);
		}
	   
		// Replace Random IN Header	   
	   preg_match_all('[random[cd]+/[0-9]+]',$header,$out);
	   
	   $chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
	   $chaineDigitals ='0123456789';
	   $chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
	   foreach($out[0] as $random)
	   {
			 $splitRandom = explode('/',$random);
			 
			 $typeRandom = $splitRandom[0];
			 $numberRandom = $splitRandom[1];
			 $concat      = '';
			 
			 switch($typeRandom)
			 {
				 case 'randomc':
				   for($i=0;$i<$numberRandom;$i++)
				   {
					 $rand = rand(0,strlen($chaineChars)-1);
					 $concat.=$chaineChars[$rand];
				   }
					$header = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$header,1);
				 break;
				 
				 case 'randomd':
				   for($i=0;$i<$numberRandom;$i++)
				   {
					 $rand = rand(0,strlen($chaineDigitals)-1);
					 $concat.=$chaineDigitals[$rand];
				   }
					$header = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$header,1);
				 break;
				 
				 
				 case 'randomcd':
				   for($i=0;$i<$numberRandom;$i++)
				   {
					 $rand = rand(0,strlen($chaineCD)-1);
					 $concat.=$chaineCD[$rand];
				   }
					$header = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$header,1);
				 break;
				 
				 
			 }
		 
	   }
	   
	   
	   
	   // Random In Body
		preg_match_all('[random[cd]+/[0-9]+]',$body,$out);
				   
		$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
		$chaineDigitals ='0123456789';
		$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
		   
		foreach($out[0] as $random)
		{
			$splitRandom = explode('/',$random);
				 
			$typeRandom = $splitRandom[0];
			$numberRandom = $splitRandom[1];
			$concat      = '';
				 
			switch($typeRandom)
			{
				
				case 'randomc':
					
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineChars)-1);
							 $concat.=$chaineChars[$rand];
					}
							
					$body = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$body,1);
					
				break;
						 
						 
				case 'randomd':
				
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineDigitals)-1);
							 $concat.=$chaineDigitals[$rand];
					}
					
					$body = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$body,1);
					
				break;
						 
						 
				case 'randomcd':
				
						for($i=0;$i<$numberRandom;$i++)
						{
							 $rand = rand(0,strlen($chaineCD)-1);
							 $concat.=$chaineCD[$rand];
						}
						
						$body = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$body,1);
						
				break;
					 
					 
			}
			
		}
		
		
	    
	   $to         = trim($rowSend['email_Email']);
	   $ar	       = trim($explodeAR[$indexAR]);
	   
	   //Random TO
	   $explodeTo  = explode('@',$to);
	   $lengthTo   = strlen($explodeTo[0]);
	   $midLength  = intval($lengthTo/2);
	   
	   for($cptToRand = 0 ; $cptToRand<$midLength ; $cptToRand++)
	   {
		   $randTo      = rand(0,$lengthTo-1);
		   $to[$randTo] = strtoupper($to[$randTo]);
	   }
	   
	   
	   
	   
	   $returnPath = $returnPathOriginal;
	   $returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
	   // AUTO RESPONSE IN RETURN MOFDI
	   $returnPath = preg_replace('#\[ar\]#',$to,$returnPath);
	   $idEmail = $rowSend['id_Email'];
	   
	   
	   $header =    preg_replace('#\[sr\]#',$aliasServer,$header);
	   $header =    preg_replace('#\[ip\]#',$ip,$header);
	   $header =    preg_replace('#\[to\]#',$ar,$header);
	   $header =    preg_replace('#\[domain\]#',$domain,$header);
	   $header =    preg_replace('#\[ar\]#',$to,$header);
	   
	   
	   $body =      preg_replace('#\[domain\]#',$domain,$body);
	   $body =      preg_replace('#\[idsend\]#',$id_Send,$body);
	   $body =      preg_replace('#\[idemail\]#',$idEmail,$body);
	   $body =      preg_replace('#\[idfrom\]#',$idFrom,$body);
	   $body =      preg_replace('#\[idsubject\]#',$subject,$body);
	   $body =      preg_replace('#\[idcreative\]#',$creative,$body);
	   $body =      preg_replace('#\[idip\]#',$idIP,$body);
	   $body =      preg_replace('#\[ip\]#',$ip,$body);
	   
	   $split = explode(PHP_EOL,$header);
	   $from = '';
	   
	    $fromName  = '';
		$fromEmail = '';
		  
	   foreach($split as $line)
	   {
	      $params = explode(':',$line);
		  
	     
	      if(strtolower($params[0]) == 'fromname')
		   $fromName = $params[1];
		  
		  if(strtolower($params[0]) == 'fromemail')
		      $fromEmail = $params[1];
	
	   }
	   
	   $from=$fromName.$fromEmail;
	   
	   $headerTelNet = '';
	   
	   foreach($split as $line)
	   {
	      $params = explode(':',$line,2);
		  
	      if(strtolower($params[0]) == 'fromname')
		   $headerTelNet.="from:$from\n";
		  else
		  {
		    if(strtolower($params[0]) != 'fromemail')
		      $headerTelNet.=$params[0].':'.trim($params[1])."\n";
		  }
		  
	   }
	   
	   $headerTelNet.="x-job:$id_Send-$idEmail-$idList-$idTypeList\nx-virtual-mta: mta-$ip\n$body\n.\n";
	   
	   //echo $header.PHP_EOL.'------------'.PHP_EOL;
	   
$telnet    = array();
$telnet[0] = "telnet $ip\r\n";
$telnet[1] = "HELO $domain\r\n";
$telnet[2] = "MAIL FROM:$returnPath\r\n";
$telnet[3] = "RCPT TO:$ar\r\n";
$telnet[4] = "DATA\r\n";
$telnet[5] = $headerTelNet;



$count=0;

if (!$fp)
{
	echo 'connection fail 2';
	return false;   
}

else
{


	
			    foreach ($telnet as $current) 
			    {       
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
					$g=substr($smtpOutput, 0, 3);


					if (!(($g == "220") || ($g == "250") || ($g == "354")|| ($g == "500"))) 
					{
						echo 'connection 2 fail';
						 return false; 
					}
				}

				$indexAR++;
				if($indexAR == count($explodeAR))
				   $indexAR = 0;
				   
				usleep($xDelay);
	

}





$cptSeed++;
	   if($cptSeed==$seed)
	   {
				
				   $cptSeed = 0;
				   

			
			foreach($emailsTests as $emailTest)
			{
					$emailTest = trim($emailTest);
					
				   $header =    $headerOriginal;
				   $body   =      $bodyOriginal;
				   
				   preg_match_all('#\[[a-zA-Z0-9/]+\]#',$header,$out);
		
		foreach($out[0] as $tag)
		{
			$header =    str_replace($tag,strtolower($tag),$header);
		}
		
		preg_match_all('#\[[a-zA-Z0-9/]+\]#',$body,$out);
		
		foreach($out[0] as $tag)
		{
			$body =    str_replace($tag,strtolower($tag),$body);
		}
		
		
	   preg_match_all('[random[cd]+/[0-9]+]',$header,$out);
	   
	   $chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
	   $chaineDigitals ='0123456789';
	   $chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
	   foreach($out[0] as $random)
	   {
			 $splitRandom = explode('/',$random);
			 
			 $typeRandom = $splitRandom[0];
			 $numberRandom = $splitRandom[1];
			 $concat      = '';
			 
			 switch($typeRandom)
			 {
					 case 'randomc':
					   for($i=0;$i<$numberRandom;$i++)
					   {
						 $rand = rand(0,strlen($chaineChars)-1);
						 $concat.=$chaineChars[$rand];
					   }
						$header = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$header,1);
					 break;
					 
					 case 'randomd':
					   for($i=0;$i<$numberRandom;$i++)
					   {
						 $rand = rand(0,strlen($chaineDigitals)-1);
						 $concat.=$chaineDigitals[$rand];
					   }
						$header = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$header,1);
					 break;
					 
					 
					 case 'randomcd':
					   for($i=0;$i<$numberRandom;$i++)
					   {
						 $rand = rand(0,strlen($chaineCD)-1);
						 $concat.=$chaineCD[$rand];
					   }
						$header = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$header,1);
					 break;
				 
				 
			    }
	   }
	   
	   
	   // Random In Body
		preg_match_all('[random[cd]+/[0-9]+]',$body,$out);
				   
		$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
		$chaineDigitals ='0123456789';
		$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
		   
		foreach($out[0] as $random)
		{
			$splitRandom = explode('/',$random);
				 
			$typeRandom = $splitRandom[0];
			$numberRandom = $splitRandom[1];
			$concat      = '';
				 
			switch($typeRandom)
			{
				
				case 'randomc':
					
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineChars)-1);
							 $concat.=$chaineChars[$rand];
					}
							
					$body = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$body,1);
					
				break;
						 
						 
				case 'randomd':
				
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineDigitals)-1);
							 $concat.=$chaineDigitals[$rand];
					}
					
					$body = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$body,1);
					
				break;
						 
						 
				case 'randomcd':
				
						for($i=0;$i<$numberRandom;$i++)
						{
							 $rand = rand(0,strlen($chaineCD)-1);
							 $concat.=$chaineCD[$rand];
						}
						
						$body = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$body,1);
						
				break;
					 
					 
			}
			
		}
		
		
				   $to = $row['emailTest_Send'];
				   $ar	       = trim($explodeAR[$indexAR]);
				   
				   //Random TO
				   $explodeTo  = explode('@',$to);
				   $lengthTo   = strlen($explodeTo[0]);
				   $midLength  = intval($lengthTo/2);
				   
				   for($cptToRand = 0 ; $cptToRand<$midLength ; $cptToRand++)
				   {
					   $randTo      = rand(0,$lengthTo-1);
					   $to[$randTo] = strtoupper($to[$randTo]);
				   }
	   
				   $returnPath = $returnPathOriginal;
			       $returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
				   
				   $date = date(DATE_RFC2822);
				   $header =    preg_replace('#\[date\]#',$date,$header);
				   
				   $header =    preg_replace('#\[sr\]#',$aliasServer,$header);
				   $header =    preg_replace('#\[ip\]#',$ip,$header);
				   $header =    preg_replace('#\[to\]#',$ar,$header);
				   $header =    preg_replace('#\[domain\]#',$domain,$header);
				   $header =    preg_replace('#\[ar\]#',$to,$header);
				   
				   
				   $body =      preg_replace('#\[domain\]#',$domain,$body);
				   $body =      preg_replace('#\[idsend\]#',$id_Send,$body);
				   $body =      preg_replace('#\[idemail\]#',0,$body);
				   $body =      preg_replace('#\[idfrom\]#',$idFrom,$body);
				   $body =      preg_replace('#\[idsubject\]#',$subject,$body);
				   $body =      preg_replace('#\[idcreative\]#',$creative,$body);
				   $body =      preg_replace('#\[idip\]#',$idIP,$body);
				   $body =      preg_replace('#\[ip\]#',$ip,$body);
				   
				   $split = explode(PHP_EOL,$header);
				   $from = '';
				   
					$fromName  = '';
					$fromEmail = '';
					  
				   foreach($split as $line)
				   {
					  $params = explode(':',$line);
					  
					 
					  if(strtolower($params[0]) == 'fromname')
					   $fromName = $params[1];
					  
					  if(strtolower($params[0]) == 'fromemail')
						$fromEmail = $params[1];
	
					  
				   }
				   
				   $from=$fromName.$fromEmail;
				   
				   $headerTelNet = '';
				   
				   foreach($split as $line)
				   {
					  $params = explode(':',$line,2);
					  
					  if(strtolower($params[0]) == 'fromname')
					   $headerTelNet.="from:$from\n";
					  else
					  {
						if(strtolower($params[0]) != 'fromemail')
						  $headerTelNet.=$params[0].':'.trim($params[1])."\n";
					  }
					  
				   }
				   
				   $headerTelNet.="x-job:$id_Send-0-0-0\nx-virtual-mta: mta-$ip\n$body\n.\n";
				   
				  //echo $header.PHP_EOL.'------------'.PHP_EOL; 
				   
				   $telnet    = array();
				   $telnet[0] = "telnet $ip\r\n";
				   $telnet[1] = "HELO $domain\r\n";
				   $telnet[2] = "MAIL FROM:$returnPath\r\n";
				   $telnet[3] = "RCPT TO:$ar\r\n";
				   $telnet[4] = "DATA\r\n";
				   $telnet[5] = $headerTelNet;
				   
				   
				   
				  $count=0;

if (!$fp)
{
	
	return false;   
}

else
{


	
			    foreach ($telnet as $current) 
			    {       
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
					$g=substr($smtpOutput, 0, 3);


					if (!(($g == "220") || ($g == "250") || ($g == "354")|| ($g == "500"))) 
					{
						echo 'connection 2 fail';
						 return false; 
					}
				}

	
	
				usleep($xDelay);

}

	   }
		   
	  }
	   
	   
	   
	  

	
}
fclose($fp);
}
		$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
$requete->execute(array($host,$id_Send));
		
		
		

		if($idISP != 3)
		{
			$startFrom+=$fraction;
		}
		echo "$ip => $fraction emails\n";
	}  
}	

//Close CURL
//curl_close ($ch); 

$requeteCount = $bdd->query("select count(*) from $tableName");
$rowCount	  = $requeteCount->fetch();
$countList = $rowCount[0]-$startFrom;

if($idISP != 3)
{
	if($startFrom >= $rowCount[0])
	{
	  $startFrom = $rowCount[0];
	  echo "\nEmpty email list";
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