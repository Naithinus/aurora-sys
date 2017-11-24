
<?php
include_once('../Includes/sessionVerification.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
include('../Includes/bdd.php');



require_once "namecheap/namecheap_api.php";
$user = "leadsvoice380"; // Username required to access the API 
$key = "0dafea37acb84e7a895b45c583968055"; // Password required used to access the API 
$sandbox = false; // true for testing, false for live
$username = null; // The Username on which a command is executed.Generally, the values of ApiUser and UserName parameters are the same. 

$api = new NamecheapApi($user, $key, $sandbox, $username);

$domains = new NamecheapDomainsDns($api);
$selectIP=$bdd->query("select * from ip where id_Server_IP='$_POST[id_Server]'");
$z=1;
$y=0;
while($sip=$selectIP->fetch()){
	$selectDomain=$bdd->query("select name_Domain from domain where id_Domain='$sip[id_Domain_IP]'");
	$sdom=$selectDomain->fetch();
	$d=explode(".",$sdom[0]);
	if(count($d)>2){
	  $dom=array_slice($d,-2,2);
	  $sld=$dom[0];
	  $tld=$dom[1];
	  $host=$d[0];
	}else{
	   $sld=$d[0];
	   $tld=$d[1];
	   $host=$d[0];
	}
	$record["$sld.$tld"]["SLD"]=$sld;
	$record["$sld.$tld"]["TLD"]=$tld;
	if($z==1){
		//$record["$sld.$tld"]["apiuser"]=$user;
		//$record["$sld.$tld"]["apikey"]=$key;
		//$record["$sld.$tld"]["username"]=$user;
		//$record["$sld.$tld"]["Command"]="namecheap.domains.dns.setHosts";
		//$record["$sld.$tld"]["ClientIP"]="79.143.189.72";
		$record["$sld.$tld"]["HostName$z"]="@";
		$record["$sld.$tld"]["RecordType$z"]="URL";
		$record["$sld.$tld"]["Address$z"]="http://www.$sld.$tld/?from=@";
		$record["$sld.$tld"]["MXPref$z"]="10";
		$record["$sld.$tld"]["EmailType$z"]="MX";
		$record["$sld.$tld"]["TTL$z"]="1799";
		$z++;
		$y++;
	}
	if($z==2){
		$record["$sld.$tld"]["HostName$z"]="www";
		$record["$sld.$tld"]["RecordType$z"]="CNAME";
		$record["$sld.$tld"]["Address$z"]="parkingpage.namecheap.com.";
		$record["$sld.$tld"]["MXPref$z"]="10";
		$record["$sld.$tld"]["EmailType$z"]="MX";
		$record["$sld.$tld"]["TTL$z"]="1799";
		$z++;
		$y++;
	}
	if($z!=1 && $z!=2){
		$record["$sld.$tld"]["HostName$z"]=$host;
		$record["$sld.$tld"]["RecordType$z"]="TXT";
		$record["$sld.$tld"]["Address$z"]="v=spf1 ip4:".trim($sip[IP_IP])." ~all";
		$record["$sld.$tld"]["MXPref$z"]="10";
		//$record["$sld.$tld"]["EmailType$z"]="MX";
		$record["$sld.$tld"]["TTL$z"]="1799";
		$z++;
	}
}

foreach($record as $r=>$t){
		/*$varsGET = array('SLD' => "$t[SLD]",'TLD' => "$t[TLD]");
		$dnsGet=$domains->getHosts($varsGET)->response()->DomainDNSGetHostsResult->host;
		//print_r($domains->getHosts($varsGET)->response()->DomainDNSGetHostsResult->host);
		$i=1;
		$count=count($domains->getHosts($varsGET)->response()->DomainDNSGetHostsResult->host);

		if($count>1)
		{
			foreach($dnsGet as $key){
				foreach($key as $v)
				{
					foreach($v as $k=>$val){
						if($k=="Name" or $k=="Type" or $k=="Address" or $k=="MXPref" or $k=="TTL" or $k=="EmailType")
						{
							if($k=="Name"){
								$k="HostName$i";
							}
							if($k=="Type"){
								$k="RecordType$i";
							}
							if($k=="Address"){
								$k="Address$i";
							}
							if($k=="MXPref"){
								$k="MXPref$i";
							}
							if($k=="TTL"){
								$k="TTL$i";
							}
							if($k=="EmailType"){
								$k="EmailType";
							}
							$record["$t[SLD].$t[TLD]"][$k]=$val;
						}
					}
				}
				$i++;
			}
		}else{
			foreach($dnsGet as $key){
					foreach($key as $k=>$val){
						if($k=="Name" or $k=="Type" or $k=="Address" or $k=="MXPref" or $k=="TTL" or $k=="EmailType")
						{
							if($k=="Name"){
								$k="HostName$i";
							}
							if($k=="Type"){
								$k="RecordType$i";
							}
							if($k=="Address"){
								$k="Address$i";
							}
							if($k=="MXPref"){
								$k="MXPref$i";
							}
							if($k=="TTL"){
								$k="TTL$i";
							}
							if($k=="EmailType"){
								$k="EmailType";
							}
							$record["$t[SLD].$t[TLD]"][$k]=$val;
						}
					}
				$i++;
			}
		}*/
		$user = "leadsvoice380"; // Username required to access the API 
		$key = "0dafea37acb84e7a895b45c583968055"; // Password required used to access the API 
		$url = "http://api.namecheap.com/xml.response";
		$records=$record["$t[SLD].$t[TLD]"];
		if($domains->setHosts($records)->response()->DomainDNSSetHostsResult->{"@attributes"}->IsSuccess == "true"){
			echo $z-$y-1 ." records added";
		}else{
			echo "Error adding $z records";
		}
		
		/*
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($records)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { echo "Error connecting to the API"; }

		var_dump($result);
		
				
		
		/*foreach($records as $key => $value) { $fields_string .= $key.'='.$value.'&'; }
		$fields_string = rtrim($fields_string,'&');/*

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch,CURLOPT_POST,count($records));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

		//execute post
		$result = curl_exec($ch);
		print_r($result);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($records));

		// in real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		echo $server_output;
		curl_close ($ch);

		// further processing ....
		/*print_r($domains->setHosts($record["$t[SLD].$t[TLD]"]));
		if($domains->setHosts($record["$t[SLD].$t[TLD]"])->response()->DomainDNSSetHostsResult->{"@attributes"}->IsSuccess == true){
		echo "DNS Records added successfully";
		}else{
			echo "Error";
		}*/
	}
?>