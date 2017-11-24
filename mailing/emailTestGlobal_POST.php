<?php

 include_once('../Includes/sessionVerificationMailer.php'); 
 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 verify($monUrl);

 header("Access-Control-Allow-Origin: *");
 date_default_timezone_set('UTC');
 include('../Includes/bdd.php');
 
 $emailTestPost     	 = $_POST['txtEmailTest'];
 $returnPathOriginal     = strtolower(trim($_POST['txtReturnPath']));
 $headerOriginal 		 = $_POST['txtHeader'];
 $bodyOriginal			 = $_POST['txtBody'];
 $tab_ips				 = $_POST['txtIPS'];
 $file					 = $_POST['txtFILE'];
 $idMailer 				 = $_SESSION['id_Employer'];
 $delay = $_POST["delay"];
 $fraction = $_POST["fraction"];
 $rotation=$_POST["rotation"];
 if($fraction>count($tab_ips)){
	 echo "Error: Fraction bigger than the number of IPS!";
	 die();
 }
 if(empty($delay)){
	 $delay=0;
 }else{
	 if($delay<0){
	 echo "Error: Delay is negative!";
	 die();
	}
 }

 if(strstr($emailTestPost,";")){
	 $explodeEmailTest = explode(";",$emailTestPost);
 }else
 {
	  $explodeEmailTest = explode(PHP_EOL,$emailTestPost);
 }
$emailTests=array();
foreach($explodeEmailTest as $emailTest)
{
	$emailTests[]=trim($emailTest);
}

 if(empty($fraction)){
	 $fraction=count($emailTests);
 }
 $loop=count($tab_ips)/$fraction;
 if(is_float($loop)){
	 $loop=ceil($loop);
 }
 $d=0;
 $z=0;
 $x=0;
 $testarray=0;
 $loop=array();

$id_ips=array();
foreach($tab_ips as $id_ip)
{
	$id_ips[]=$id_ip;
}
if($rotation==true)
{
	for($y=0;$y<count($tab_ips);$y++)
	{
		if(empty($emailTests[$x])){
			$x=0;
		}
		$id=$id_ips[$y];
		$z++;
		
		$requete = $bdd->query("select name_Domain,s.alias_Server, i.IP_IP from domain d , ip i , server s where i.id_IP = '$id' and i.id_Domain_IP = d.id_Domain and i.id_Server_IP = s.id_Server") or die(print_r($bdd->errorInfo()));
		$row = $requete->fetch();
		$ip=$row["IP_IP"];
		$domain = $row['name_Domain'];
		$aliasServer = $row['alias_Server'];
		$header =    $headerOriginal;
		$body =      $bodyOriginal;
		$returnPath = $returnPathOriginal;
		preg_match_all('#\[[a-zA-Z0-9/]+\]#',$header,$out);
		
		foreach($out[0] as $tag)
		{
			$header =    str_replace($tag,strtolower($tag),$header);
		}
		
		preg_match_all('#\[[a-zA-Z0-9/],+\]#',$body,$out);
		
		foreach($out[0] as $tag)
		{
			$body =    str_replace($tag,strtolower($tag),$body);
		}
		
		// Random In ReturnPath
		$r=explode("@",$returnPath);
		$random=$r[0];
		$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
		$chaineDigitals ='0123456789';
		$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
		$splitRandom = explode('/',$random);
			 
		$typeRandom = str_replace("[","",$splitRandom[0]);
		$numberRandom = str_replace("]","",$splitRandom[1]);
		$concat      = '';
		switch($typeRandom)
		{
			case 'randomc':
				
				for($i=0;$i<$numberRandom;$i++)
				{
						 $rand = rand(0,strlen($chaineChars)-1);
						 $concat.=$chaineChars[$rand];
				}
						
				$returnPath = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$returnPath,1);
			break;
					 
					 
			case 'randomd':
			
				for($i=0;$i<$numberRandom;$i++)
				{
						 $rand = rand(0,strlen($chaineDigitals)-1);
						 $concat.=$chaineDigitals[$rand];
				}
				
				$returnPath = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$returnPath,1);
				
			break;
					 
					 
			case 'randomcd':
					for($i=0;$i<$numberRandom;$i++)
					{
						 $rand = rand(0,strlen($chaineCD)-1);
						 $concat.=$chaineCD[$rand];
					}
					
					$returnPath = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$returnPath,1);
			break;
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
		preg_match_all('[random[cdm]+/[0-9]+]',$body,$out);
				   
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
				case 'randomcdm':
					$aux=0;
					for($i=0;$i<$numberRandom;$i++)
					{
						$rand = rand(0,strlen($chaineCD)-1);
						$char=$chaineCD[$rand];
						if(is_string($char)){
							if($aux%2==0)
							{
								$concat.=strtoupper($char);
							}else{
								$concat.=$char;
							}
							$aux++;
						}else{
							$concat.=$char;
						}
						
					}
					
					$body = preg_replace("#\[randomcdm/$numberRandom+\]#",$concat,$body,1);
				break;
					 
			}
			
		} 
		$to         = $emailTests[$x];
		$requeteSend = $bdd->query("select email_list_warmup.id_Email,email_list_warmup.password_email from email_list_warmup,email where email.email_Email=\"$to\" and email.id_Email=email_list_warmup.id_email");
		while($rowSend = $requeteSend->fetch())
		{
			$idEmail = $rowSend['id_Email'];
			$XID=trim($rowSend['password_email']);
		}
		$returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
		$returnPath = preg_replace('#\[file\]#',$val,$returnPath);
		$date = date(DATE_RFC2822);
		   
		$header =    preg_replace('#\[file\]#',$val,$header);
		$header =    preg_replace('#\[sr\]#',$aliasServer,$header);
		$header =    preg_replace('#\[ip\]#',$ip,$header);
		$header =    preg_replace('#\[date\]#',$date,$header);
		$header =    preg_replace('#\[to\]#',$to,$header);
		$header =    preg_replace('#\[xid\]#',$XID,$header);
		$header =    preg_replace('#\[domain\]#',$domain,$header);
		   

		
		$body =      preg_replace('#\[domain\]#',$domain,$body);
		$body =      preg_replace('#\[file\]#',$val,$body); 
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
		$headerTelNet.="x-job:0-0-$idMailer-0\nx-virtual-mta: mta-$ip\n$body\n.\n";
		$fields=$ip."[SPLIT]".$domain."[SPLIT]".$returnPath."[SPLIT]".$to."[SPLIT]".$headerTelNet;
		$loop[$d][].=$fields;
		$x++;
		if($z==$fraction){
			$z=0;
			$d++;
		}
	}
}else{
	for($y=0;$y<count($tab_ips);$y++)
	{
		foreach($emailTests as $em)
		{
			$id=$id_ips[$y];
			$z++;
			
			$requete = $bdd->query("select name_Domain,s.alias_Server, i.IP_IP from domain d , ip i , server s where i.id_IP = '$id' and i.id_Domain_IP = d.id_Domain and i.id_Server_IP = s.id_Server") or die(print_r($bdd->errorInfo()));
			$row = $requete->fetch();
			$ip=$row["IP_IP"];
			$domain = $row['name_Domain'];
			$aliasServer = $row['alias_Server'];
			$header =    $headerOriginal;
			$body =      $bodyOriginal;
			$returnPath = $returnPathOriginal;
			preg_match_all('#\[[a-zA-Z0-9/]+\]#',$header,$out);
			
			foreach($out[0] as $tag)
			{
				$header =    str_replace($tag,strtolower($tag),$header);
			}
			
			preg_match_all('#\[[a-zA-Z0-9/],+\]#',$body,$out);
			
			foreach($out[0] as $tag)
			{
				$body =    str_replace($tag,strtolower($tag),$body);
			}
			
			// Random In ReturnPath
			$r=explode("@",$returnPath);
			$random=$r[0];
			$chaineChars    ='azertyuiopqsdfghjklmwxcvbn';
			$chaineDigitals ='0123456789';
			$chaineCD       ='a0z1e2r3t4y5u6i7o8p9q0s1d2f3g4h5j6k7l8m9wx0c1v2bn';
			$splitRandom = explode('/',$random);
				 
			$typeRandom = str_replace("[","",$splitRandom[0]);
			$numberRandom = str_replace("]","",$splitRandom[1]);
			$concat      = '';
			switch($typeRandom)
			{
				case 'randomc':
					
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineChars)-1);
							 $concat.=$chaineChars[$rand];
					}
							
					$returnPath = preg_replace("#\[randomc/$numberRandom+\]#",$concat,$returnPath,1);
				break;
						 
						 
				case 'randomd':
				
					for($i=0;$i<$numberRandom;$i++)
					{
							 $rand = rand(0,strlen($chaineDigitals)-1);
							 $concat.=$chaineDigitals[$rand];
					}
					
					$returnPath = preg_replace("#\[randomd/$numberRandom+\]#",$concat,$returnPath,1);
					
				break;
						 
						 
				case 'randomcd':
						for($i=0;$i<$numberRandom;$i++)
						{
							 $rand = rand(0,strlen($chaineCD)-1);
							 $concat.=$chaineCD[$rand];
						}
						
						$returnPath = preg_replace("#\[randomcd/$numberRandom+\]#",$concat,$returnPath,1);
				break;
			}
			$returnPath=trim($returnPath);
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
			preg_match_all('[RANDOM[CDM]+/[0-9]+]',$body,$out);
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
			$to         = $em;
			$requeteSend = $bdd->query("select email_list_warmup.id_Email,email_list_warmup.password_email from email_list_warmup,email where email.email_Email=\"$to\" and email.id_Email=email_list_warmup.id_email");
			while($rowSend = $requeteSend->fetch())
			{
				$idEmail = $rowSend['id_Email'];
				$XID=trim($rowSend['password_email']);
			}
			$returnPath = preg_replace('#\[domain\]#',trim($domain),$returnPath);
			$returnPath = preg_replace('#\[file\]#',$val,$returnPath);
			$date = date(DATE_RFC2822);
			   
			$header =    preg_replace('#\[file\]#',$val,$header);
			$header =    preg_replace('#\[sr\]#',$aliasServer,$header);
			$header =    preg_replace('#\[ip\]#',$ip,$header);
			$header =    preg_replace('#\[date\]#',$date,$header);
			$header =    preg_replace('#\[to\]#',$to,$header);
			$header =    preg_replace('#\[xid\]#',$XID,$header);
			$header =    preg_replace('#\[domain\]#',$domain,$header);
			   

			
			$body =      preg_replace('#\[domain\]#',$domain,$body);
			$body =      preg_replace('#\[file\]#',$val,$body); 
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
			$headerTelNet.="x-job:0-0-$idMailer-0\nx-virtual-mta: mta-$ip\n$body\n.\n";
			$fields=$ip."[SPLIT]".$domain."[SPLIT]".$returnPath."[SPLIT]".$to."[SPLIT]".$headerTelNet;
			$loop[$d][].=$fields;
			$x++;
			if($z==$fraction){
				$z=0;
				$d++;
			}
		}
	}
}

file_put_contents("mail.txt",serialize($loop));
foreach($loop as $l=>$s){
	$channels=array();
	$multi = curl_multi_init();
	foreach ($s as $email){
		$split=explode("[SPLIT]",$email);
		$ip=$split[0];
		$domain=$split[1];
		$returnPath=$split[2];
		$to=$split[3];
		$headerTelNet=$split[4];
		$url="http://$ip/exactarget/Send/TestGlobal_POST.php";
		$fields = array(
		'ip' 		 => urlencode($ip),
		'domain'     => urlencode($domain),
		'returnPath' => urlencode($returnPath),
		'to'         => urlencode($to),
		'header'     => urlencode($headerTelNet)
		);
		foreach($fields as $key=>$value) 
		{
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_multi_add_handle($multi, $ch);
		$channels[$url] = $ch;
	}
	$start=0;
	$start = microtime(true);
	// While we're still active, execute curl
	$active = null;
	do {
		$mrc = curl_multi_exec($multi, $active);
	} while ($mrc == CURLM_CALL_MULTI_PERFORM);
	 
	while ($active && $mrc == CURLM_OK) {
		// Wait for activity on any curl-connection
		if (curl_multi_select($multi) == -1) {
			continue;
		}
	 
		// Continue to exec until curl is ready to
		// give us more data
		do {
			$mrc = curl_multi_exec($multi, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
	}
	 
	// Loop through the channels and retrieve the received
	// content, then remove the handle from the multi-handle
	foreach ($channels as $channel) {
		$result=curl_multi_getcontent($channel);
		curl_multi_remove_handle($multi, $channel);
	}
	 $time_elapsed = (microtime(true) - $start);
	// Close the multi-handle and return our results
	curl_close ($ch);
	curl_multi_close($multi);
	unset($channels);
	sleep($delay);
	echo "email sent to $to\n";
}
echo "Done";
?>
