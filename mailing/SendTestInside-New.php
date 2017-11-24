<?php

	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

 include('../Includes/bdd.php');
 
 $idSend     		 = $_POST['id_Send'];
 $idFrom	 		 = $_POST['cmbFroms'];
 $idSubject	 		 = $_POST['cmbSubjects'];
 $emailTestPost  	 = $_POST['txtEmailTest'];
 $returnPathOriginal = $_POST['txtReturnPath'];
 $headerOriginal	 = $_POST['txtHeader'];
 $bodyOriginal		 = $_POST['txtBody'];
 $ipsSplit           = $_POST['cmbIPs'];
  
 $contentNegative     = '';

 $id_negative = $_POST['idnegative'];
 
 if($id_negative != 0)
 {
	$requeteNegative = $bdd->prepare('select file_negative from negative where id_negative = ?');
	$requeteNegative->execute(array($id_negative));
	$rowNegative     =  $requeteNegative->fetch();
	$fileName 		 =  $rowNegative['file_negative'];
	
	
    $contentNegative = file_get_contents('http://79.143.189.72/exactarget/Negative/negatives/'.$fileName);	
	
 }
 
 
 $ch = curl_init();
 
 
 if($_POST['chkAR']==0)
 {
				 foreach($ipsSplit as $ip)
				 {
						$vmta        =  $ip;
					    $explodeVmta =  explode('-',$ip);
						$ip			 =	$explodeVmta[1];
						
						//@$fp = fsockopen($ip, 25);
						$requeteIP = $bdd->prepare('select i.id_IP,s.alias_Server,d.name_Domain from domain d , ip i , server s where i.id_Domain_IP = d.id_Domain and i.IP_IP = ? and i.id_Server_IP = s.id_Server');
						$requeteIP->execute(array($ip));
						$rowIP     = $requeteIP->fetch();	
						$domain    = $rowIP['name_Domain'];
						$idIP      = $rowIP['id_IP'];
						$aliasServer = $rowIP['alias_Server'];
						
						$explodeEmailTest = explode(PHP_EOL,$emailTestPost);
					
						foreach($explodeEmailTest as $emailTest)
						{
						
						$header      = $headerOriginal;
						$body        = $bodyOriginal;
						
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
						
						
							   $to         = $emailTest;
							   
							   $returnPath = $returnPathOriginal;
							   $returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
							   $idEmail = 0;
							   
							   
							   
							   $date = date(DATE_RFC2822);
							   $header =    preg_replace('#\[date\]#',$date,$header);
									
							   $header =    preg_replace('#\[sr\]#',$aliasServer,$header);
							   $header =    preg_replace('#\[ip\]#',$ip,$header);
							   $header =    preg_replace('#\[to\]#',$to,$header);
							   $header =    preg_replace('#\[domain\]#',$domain,$header);
							   
							   
							   $body =      preg_replace('#\[domain\]#',$domain,$body);
							   $body =      preg_replace('#\[idsend\]#',$idSend,$body);
							   $body =      preg_replace('#\[idemail\]#',$idEmail,$body);
							   $body =      preg_replace('#\[idfrom\]#',$idFrom,$body);
							   $body =      preg_replace('#\[idsubject\]#',$idSubject,$body);
							   $body =      preg_replace('#\[idcreative\]#',0,$body);
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
							   
							   $headerTelNet.="x-job:$idSend-0-0-0\nx-virtual-mta: $vmta\n$body\n.\n";
							   
							   
							   $url = 'http://'.$ip.'/exactarget/Send/TestGlobal_POST.php';
							   
								$fields = array(
								
								'ip' 		 => urlencode($ip),
								'domain'     => urlencode($domain),
								'returnPath' => urlencode($returnPath),
								'to'         => urlencode($to),
								'header'     => urlencode($headerTelNet)
								
							    );
							

							foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
							rtrim($fields_string, '&');

							
							curl_setopt($ch,CURLOPT_URL, $url);
							curl_setopt($ch,CURLOPT_POST, count($fields));
							curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

							$result = curl_exec($ch);
							
							
							   //echo $header.PHP_EOL.'------------'.PHP_EOL;
							   
							   /*$telnet    = array();
							   $telnet[0] = "telnet $ip\r\n";
							   $telnet[1] = "HELO $domain\r\n";
							   $telnet[2] = "MAIL FROM:$returnPath\r\n";
							   $telnet[3] = "RCPT TO:$to\r\n";
							   $telnet[4] = "DATA\r\n";
							   $telnet[5] = $headerTelNet;
							   
							   
							   
							   foreach ($telnet as $current) 
							   {         
									fwrite($fp, $current);
									$smtpOutput=fgets($fp);
							   }*/
								

							   
							   
							   
								//SEND EMAIL
								
						}
				 }
}

	























	
			else
			{
			    
			       foreach($ipsSplit as $ip)
				   {
					    $vmta        =  $ip;
					    $explodeVmta =  explode('-',$ip);
						$ip			 =	$explodeVmta[1];
					//@$fp = fsockopen($ip, 25);
					
					$requeteIP = $bdd->prepare('select i.id_IP,s.alias_Server,d.name_Domain from domain d , ip i , server s where i.id_Domain_IP = d.id_Domain and i.IP_IP = ? and i.id_Server_IP = s.id_Server');
					$requeteIP->execute(array($ip));
					$rowIP     = $requeteIP->fetch();	
					$domain    = $rowIP['name_Domain'];
					$idIP      = $rowIP['id_IP'];
					$aliasServer = $rowIP['alias_Server'];
					$ARList		= $_POST['txtARList'];
					$explodeAR  = explode(PHP_EOL,$ARList);
					
					foreach($explodeAR as $ar)
					{
					
						$explodeEmailTest = explode(PHP_EOL,$emailTestPost);
					
						foreach($explodeEmailTest as $emailTest)
						{
						$header      = $headerOriginal;
						$body        = $bodyOriginal;
						
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
						
						
							   $to         = $emailTest;
							   
							   $returnPath = $returnPathOriginal;
							   $returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
							   $idEmail = 0;
							   
							   
							   
							   $ar = trim($ar);
							   $date = date(DATE_RFC2822);
							   $header =    preg_replace('#\[date\]#',$date,$header);
									
							   $header =    preg_replace('#\[sr\]#',$aliasServer,$header);
							   $header =    preg_replace('#\[ip\]#',$ip,$header);
							   $header =    preg_replace('#\[to\]#',$ar,$header);
							   $header =    preg_replace('#\[domain\]#',$domain,$header);
							   $header =    preg_replace('#\[ar\]#',$to,$header);
							   
							   
							   $body =      preg_replace('#\[domain\]#',$domain,$body);
							   $body =      preg_replace('#\[idsend\]#',$idSend,$body);
							   $body =      preg_replace('#\[idemail\]#',$idEmail,$body);
							   $body =      preg_replace('#\[idfrom\]#',$idFrom,$body);
							   $body =      preg_replace('#\[idsubject\]#',$idSubject,$body);
							   $body =      preg_replace('#\[idcreative\]#',0,$body);
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
							   
							   $headerTelNet.="x-job:$idSend-0-0-0\nx-virtual-mta: $vmta\n$body\n.\n";
							   
							   
							   $url = 'http://'.$ip.'/exactarget/Send/TestGlobal_POST.php';
							   
								$fields = array(
								'ip' 		 => urlencode($ip),
								'domain'     => urlencode($domain),
								'returnPath' => urlencode($returnPath),
								'to'         => urlencode($ar),
								'header'     => urlencode($headerTelNet)
							    );
							

							foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
							rtrim($fields_string, '&');

							
							curl_setopt($ch,CURLOPT_URL, $url);
							curl_setopt($ch,CURLOPT_POST, count($fields));
							curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

							$result = curl_exec($ch);
							
							
							   //echo $header.PHP_EOL.'------------'.PHP_EOL;
							   
							   /*$telnet    = array();
							   $telnet[0] = "telnet $ip\r\n";
							   $telnet[1] = "HELO $domain\r\n";
							   $telnet[2] = "MAIL FROM:$returnPath\r\n";
							   $telnet[3] = "RCPT TO:$ar\r\n";
							   $telnet[4] = "DATA\r\n";
							   $telnet[5] = $headerTelNet;
							   
							   
							   
							   foreach ($telnet as $current) 
							   {         
									fwrite($fp, $current);
									$smtpOutput=fgets($fp);
							   }*/
					    }
			        }
			}
			
    }
	
	
	function	get_address_ip_by_id($p_id_ip)
	{
		$result	=	null;
		if(  (is_numeric($p_id_ip))  and  ($p_id_ip>0) )
		{
			include('../Includes/bdd.php');
			$requete = $bdd->prepare(
			'
				SELECT 	I.IP_IP
				FROM 	ip I
				WHERE 	I.id_IP		=		?
			');
			$requete->execute(array($p_id_ip));
			$row = $requete->fetch();
			
			if($row)
				$result	=	$row['IP_IP'];
		}
		return $result;
	}
 
//echo 'Test Done'; 
?>
