<?php
function Send($idISP,$host,$xDelay,$id_Send,$isSender,$idFrom,$subject,$creative,$id_negative,$ip,$idIP,$aliasServer,$domain,$emailsTests,$seed,$cptSeed,$tableName,$startFrom,$fraction,$headerOriginal,$bodyOriginal,$returnPathOriginal)
{
	
	include('../Includes/bdd.php');
	global $time;
	$time=0;
	@$fp = fsockopen($ip, 25, $errno, $errstr);
	$requeteSend = $bdd->query("select * from $tableName limit $startFrom,$fraction");
	while($rowSend = $requeteSend->fetch())
	{
		$start =microtime($get_as_float = true);
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
			$body = preg_replace('#\[nega\]#',$contentNegative,$body);
		}

		$to = trim($rowSend['email_Email']);

		$returnPath = $returnPathOriginal;
		$returnPath = preg_replace('#\[domain\]#',$domain,$returnPath);
		$idEmail = $rowSend['id_Email'];
		if ($idISP==3){
			$XID_query=$bdd->query("Select password_email from email_list_warmup,$tableName where $tableName.id_Email=\"$idEmail\" and $tableName.id_Email=email_list_warmup.id_email");
			while ($row=$XID_query->fetch()){
				$XID=trim($row[0]);
			}
		}
		date_default_timezone_set('America/Los_Angeles');
		$date   =    date(DATE_RFC2822);
		$header =    preg_replace('#\[date\]#',$date,$header);
		$header =    preg_replace('#\[sr\]#',$aliasServer,$header);
		$header =    preg_replace('#\[ip\]#',$ip,$header);
		$header =    preg_replace('#\[to\]#',$to,$header);
		if ($idISP==3){
			$header =    preg_replace('#\[xid\]#',$XID,$header);
		}
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
		
		$telnet    = array();
		$telnet[0] = "telnet $ip\r\n";
		$telnet[1] = "HELO $domain\r\n";
		$telnet[2] = "MAIL FROM:$returnPath\r\n";
		$telnet[3] = "RCPT TO:$to\r\n";
		$telnet[4] = "DATA\r\n";
		$telnet[5] = $headerTelNet;

		if (!$fp)
		{
			echo 'connection fail'."$errstr ($errno)";
			return false;   
		} else
		{
			foreach ($telnet as $current) 
			{       
				fwrite($fp, $current);
				stream_set_timeout($fp, 1);
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
			foreach($emailsTests as $emailTest)
			{
				$emailTest = trim($emailTest);
				foreach ($telnet as $current) 
				{    
					$telnet[2] = "MAIL FROM:$returnPath\r\n";
					$telnet[3] = "RCPT TO:$emailTest\r\n";
					fwrite($fp, $current);
					    stream_set_timeout($fp, 1);
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
			$cptSeed=$seed-$cptSeed;
		}

				/*$header =    $headerOriginal;
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
				if(strtolower($params[0]) == 'fromname'){
					$fromName = trim($params[1]);
				}
				
				if(strtolower($params[0]) == 'fromemail')
				{
					if($isSender==0){
						$fromEmail = $params[1];
					}else
						$fromEmail = trim($rowSend['sender']);
					}
					$body =      preg_replace('#\[sender\]#',$fromEmail,$body);
				}
			}

			$from=$fromName.$fromEmail;
			$from  = str_replace("\n", '', $from);

			$headerTelNet = '';

			foreach($split as $line)
			{
				$params = explode(':',$line,2);

				if(strtolower($params[0]) == 'fromname'){
					$headerTelNet.="from:$from\n";
				}else{
					if(strtolower($params[0]) != 'fromemail'){
						$headerTelNet.=$params[0].':'.trim($params[1])."\n";
					}
				}
			}

			$headerTelNet.="x-job:$id_Send-0-0-0\nx-virtual-mta: mta-$ip\n$body\n.\n";

			$telnet    = array();
			$telnet[0] = "telnet $ip\r\n";
			$telnet[1] = "HELO $domain\r\n";
			$telnet[2] = "MAIL FROM:$returnPath\r\n";
			$telnet[3] = "RCPT TO:$to\r\n";
			$telnet[4] = "DATA\r\n";
			$telnet[5] = $headerTelNet;

			if (!$fp){
				return false;   
			}else{
				foreach ($telnet as $current) 
				{       
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
					$g=substr($smtpOutput, 0, 3);
					if (!(($g == "220") || ($g == "250") || ($g == "354")|| ($g == "500"))) {
						//echo 'connection 2 fail';
						//return false; 
					}
				}
				usleep($xDelay);
			}

		} */// End of cptSeed
		$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
		$requete->execute(array($host,$id_Send));
		$end=microtime($get_as_float = true);
		$time+=$end-$start-($xDelay/1000000);
	} // End of WHILE
	fclose($fp);
}




/*function Send_Warmup($host,$xDelay,$id_Send,$isSender,$idFrom,$subject,$creative,$id_negative,$ip,$idIP,$aliasServer,$domain,$emailsTests,$seed,$cptSeed,$tableName,$startFrom,$fraction,$headerOriginal,$bodyOriginal,$returnPathOriginal)
{
	echo "\n2";
	include('../Includes/bdd.php');
	$start =microtime($get_as_float = true);
	global $time;
	$time=0;
	@$fp = fsockopen($ip, 25);
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
		//$XID_query=$bdd->query("Select password_email from email_list_warmup,$tableName where $tableName.id_Email=$idEmail and $tableName.id_Email=email_list_warmup.id_Email");
		//while ($row=$XID_query->fetch()){
		//	$XID=$row[0];
		//}


		$date   =    date(DATE_RFC2822);
		$header =    preg_replace('#\[date\]#',$date,$header);

		$header =    preg_replace('#\[sr\]#',$aliasServer,$header);
		$header =    preg_replace('#\[ip\]#',$ip,$header);
		$header =    preg_replace('#\[to\]#',$to,$header);
		$header =    preg_replace('#\[domain\]#',$domain,$header);
		//$header =      preg_replace('#\[xid\]#',$XID,$header);


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
			echo 'connection fail';
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
		$end=microtime($get_as_float = true);
		$time=($end-$start);
		$requete = $bdd->prepare('update sendprocess set pid=0 where host = ? and id_Send = ?');
		$requete->execute(array($host,$id_Send));
	}
fclose($fp);
}*/
