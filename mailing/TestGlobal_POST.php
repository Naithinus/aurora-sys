<?php



$ip            = $_POST['ip'];
$domain        = $_POST['domain'];
$returnPath    = $_POST['returnPath'];
$to            = $_POST['to'];
$headerTelNet  = $_POST['header'];

$telnet        = array();
$telnet[0]     = "telnet $ip\r\n";
$telnet[1]     = "HELO $domain\r\n";
$telnet[2]     = "MAIL FROM:$returnPath\r\n";
$telnet[3]     = "RCPT TO:$to\r\n";
$telnet[4]     = "DATA\r\n";
$telnet[5]     = $headerTelNet;

@$fp           = fsockopen($ip, 25);

$count         = 0;

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
			echo 'connection 2 fail';
			return false; 
		}
	    
	}

    fclose($fp);

}


?>