<html>
<head>
<style>
body{
	font-family:'Lucida Console';
}
</style>
<head>
<body>
<?php

date_default_timezone_set('UTC');
include_once('../Includes/sessionVerification.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

$username=$_POST["user"];
$password=$_POST["pass"];
$ip=$_POST["server"];
$port=$_POST["port"];
echo "Connecting to $ip:$port ";for($i=0;$i<=5;$i++){
	echo ".";flush();ob_flush();sleep(1);
}
echo "<br>";
$connection = ssh2_connect($ip, $port);
$auth=ssh2_auth_password($connection, $username, $password);

if($auth){
	echo "<br>Connection established!<br><br>";
	sleep(1);
}else{
	echo "<br>Connection error!";
	die();
}


$commands=array(
'ip addr | awk \'{print $2}\' | rev | cut -c 4- |rev |sed -r \'/^[a-z]/d\' | sed -r \'/...............*/d\' | sed -r \'/^127/d\' | sed -r \'/^0.0./d\' | sed -r \'/^::/d\' | sed -r \'/^$/d\' | sed -r \'/\.{2}/d\' | sed -r \'/^...$/d\' | while read z ; do
	echo $z>>"/tmp/ips_temp.txt"	
done;cat "/tmp/ips_temp.txt";','wc -l < /tmp/ips_temp.txt');
$text=array("Getting Ips...","Counting IPS..");
$i=0;
foreach ($commands as $c){
	$stream = ssh2_exec($connection, $c);
	echo "> <b>$text[$i]</b><br>";
	sleep(1);
	stream_set_blocking($stream, true);
	$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
	$response=stream_get_contents($stream_out);
	$response=nl2br($response);
	if($c=="wc -l < /tmp/ips_temp.txt"){
		$count=$response;
	}
	else{
		echo $response."<br>";
	}
	fclose($stream); 
	flush();
	ob_flush();
	sleep(1);
	$i++;
}
echo "<form method=post action=''>";
echo "$count IPS<br>";
echo "Domains: <br>";
echo "<textarea name=domains rows=8 cols=20></textarea>";
echo "<form>";
?>
</body>
