<?php

 include('../Includes/bdd.php');
 
 $emailTest     		 = $_POST['txtEmailTest'];
 $returnPathOriginal     = $_POST['txtReturnPath'];
 $ips					 = $_POST['cmbIPS'];
 $headerOriginal 		 = $_POST['txtHeader'];
 $bodyOriginal			 = $_POST['txtBody'];
 $file					 = $_POST['txtFILE'];
 
 foreach($ips as $idIP)
 {
       $requete = $bdd->prepare('select IP_IP from ip where id_IP = ?');
	   $requete->execute(array($idIP));
	   $row = $requete->fetch();
	   $ip = $row['IP_IP'];
	   
       $requete = $bdd->prepare('select d.name_Domain,s.alias_Server from domain d , ip i , server s where i.id_IP = ? and i.id_Domain_IP = d.id_Domain and i.id_Server_IP = s.id_Server');
	   $requete->execute(array($idIP));
	   $row = $requete->fetch();
	   $domain = $row['name_Domain'];
	   $aliasServer = $row['alias_Server'];
	   
	   $fileExplode = explode(PHP_EOL,$file);
	    if(count($fileExplode)==0)
	      $fileExplode[]=0;
		  
		foreach($fileExplode as $val)
	    {	   
		
			   $header =    $headerOriginal;
			   
			   preg_match_all('[Random[CD]+/[0-9]+]',$header,$out);
			   
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
							 case 'RandomC':
							   for($i=0;$i<$numberRandom;$i++)
							   {
								 $rand = rand(0,strlen($chaineChars)-1);
								 $concat.=$chaineChars[$rand];
							   }
								$header = preg_replace("#\[RandomC/$numberRandom+\]#",$concat,$header,1);
							 break;
							 
							 case 'RandomD':
							   for($i=0;$i<$numberRandom;$i++)
							   {
								 $rand = rand(0,strlen($chaineDigitals)-1);
								 $concat.=$chaineDigitals[$rand];
							   }
								$header = preg_replace("#\[RandomD/$numberRandom+\]#",$concat,$header,1);
							 break;
							 
							 
							 case 'RandomCD':
							   for($i=0;$i<$numberRandom;$i++)
							   {
								 $rand = rand(0,strlen($chaineCD)-1);
								 $concat.=$chaineCD[$rand];
							   }
								$header = preg_replace("#\[RandomCD/$numberRandom+\]#",$concat,$header,1);
							 break;
						 
						 
						}
			   }
			   
			   $returnPath = $returnPathOriginal;
			   $to         = $emailTest;
			   $returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
			   
			   
			    $fnamea = explode('@',$to);
				   $fname = $fnamea[0];
			   
			   $date = date(DATE_RFC2822);
			   
			   $header =    preg_replace('#\[file\]#',$val,$header);
			   $header =    preg_replace('#\[sr\]#',$aliasServer,$header);
			   $header =    preg_replace('#\[ip\]#',$ip,$header);
			   $header =    preg_replace('#\[date\]#',$date,$header);
			   
			   $header =    preg_replace('#\[to\]#',$to,$header);
			   $header =    preg_replace('#\[domain\]#',$domain,$header);
			   
			   $header =  preg_replace('#\[X-smfbl\]#',base64_encode($to),$header);
			   $header =      preg_replace('#\[fname\]#',$fname,$header);
		
			   
			   $body =      $bodyOriginal;
			   $body =      preg_replace('#\[domain\]#',$domain,$body);
	           $body =      preg_replace('#\[fname\]#',$fname,$body);
				   
			   
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
			   
			   $headerTelNet.="x-job:0-0-0-0\nx-virtual-mta: mta-$ip\n$body\n.\n";
			   
			   //echo $headerTelNet;
			   @$fp = fsockopen($ip, 25);
			   
			   $telnet    = array();
			   $telnet[0] = "telnet $ip\r\n";
			   $telnet[1] = "HELO matara.com\r\n";
			   $telnet[2] = "MAIL FROM:$returnPath\r\n";
			   $telnet[3] = "RCPT TO:$to\r\n";
			   $telnet[4] = "DATA\r\n";
			   $telnet[5] = $headerTelNet;
			   
			   
			   
			   foreach ($telnet as $current) 
			   {         
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
			   }
	    }
		
	   fclose($fp);
 }
?>