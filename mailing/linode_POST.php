<?php
set_time_limit(0);
session_start();
$mailer = $_SESSION['id_Employer'] ;
$mailerName = $_SESSION['lastName_Employer'];
//ini_set('error_reporting',E_ALL ^ E_WARNING);
$password="NpdFw8gbf@@!2@19@";
if(isset($_POST["name"]))
	{
		$quantity=$_POST["quantity"];
		$name=$mailerName."-".$_POST["name"];
		$region=$_POST["region"];
		$type=$_POST["type"];
		$distribution=$_POST["distribution"];
		for($i=1;$i<=$quantity;$i++){
			$fields=array(
			"type"=> $type,
			"region"=>$region,
			"distribution"=>$distribution,
			"root_pass"=>$password,
			"label"=>"$name$i"
			);
			$fields_string = json_encode($fields);
			// Create Linode
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://api.linode.com/v4/linode/instances");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: token d49320f62bd07096dcb85605d9ff6e20b2ba9360629e48bd481d7d4b8bdf5129'));


			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);
			$output = json_decode($server_output);
			$linode_id=$output->id;
			$linode_ip=$output->ipv4[0];
			$linode_ip=trim($linode_ip);
			$linode_name=$output->label;
			if(!empty($linode_id)){
				include('../Includes/bdd.php');
				$newServer=$bdd->exec("insert into server values(NULL,'$linode_name',30,'root','$password',now(),now(),1,NULL,1,NULL,NULL,NULL)") or die(print_r($bdd->errorInfo()));;
				$ServerID = $bdd->lastInsertId();
				$newIP=$bdd->exec("insert into ip values (NULL,'$linode_ip','1','$ServerID')");
				$ipID=$bdd->lastInsertId();
				$UpdateServer=$bdd->exec("update server set id_IP_Server='$ipID' where id_Server='$ServerID'");
				$ActivateMailer=$bdd->exec("insert into servermailer values(NULL,'$ServerID','$mailer',1)");
				
			}
			/*echo "<pre>";
			print_r($output);
			echo "</pre>";*/
			curl_close ($ch);
			
			// Boot Linode

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://api.linode.com/v4/linode/instances/$linode_id/boot");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: token d49320f62bd07096dcb85605d9ff6e20b2ba9360629e48bd481d7d4b8bdf5129'));

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);
			$output = json_decode($server_output);
			/*echo "<pre>";
			print_r($output);
			echo "</pre>";*/

			curl_close ($ch);
			usleep(1000);
		}
		
		header("location:./");
	}
	if(isset($_POST["delete_selected"]))
	{
		$linodes_delete=$_POST["selected_linodes"];
		foreach($linodes_delete as $l){
			$l=explode("____",$l);
			$linode_name=$l[0];
			$linode_id=$l[1];
			echo $linode_name." ".$linode_id."<br>";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://api.linode.com/v4/linode/instances/$linode_id");
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: token d49320f62bd07096dcb85605d9ff6e20b2ba9360629e48bd481d7d4b8bdf5129'));

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);
			$output = json_decode($server_output);
			/*echo "<pre>";
			print_r($output);
			echo "</pre>";*/
			curl_close ($ch);
			include('../Includes/bdd.php');
			$deleteServer=$bdd->exec("delete from server where alias_Server='$linode_name'");
			$selectID=$bdd->query("select id_Server FROM server WHERE alias_Server='$linode_name'");
			$ID=$selectID->fetch();
			$id=$ID[0];
			$deleteMailer=$bdd->exec("delete from servermailer where id_Server='$id'");
			usleep(1000);
			
		}
		header("location:./");
		
	}
	if(isset($_POST["config_selected"]))
	{
		$config_selected=$_POST["selected_linodes"];
		foreach($config_selected as $l){
			$l=explode("____",$l);
			$ip=$l[2];
			include('../Includes/bdd.php');
			$username="root";
			$port=22;
			$commands=array(
			"echo 'export VISUAL=\"nano\"' >> ~/.bash_profile;echo 'export EDITOR=\"nano\"' >> ~/.bash_profile;echo 'ZONE=\"Africa/Casablanca\"' > /etc/sysconfig/clock;echo 'UTC=true' >> /etc/sysconfig/clock;ln -snf /usr/share/zoneinfo/Africa/Casablanca /etc/localtime;",
			"yum -y install ntp.x86_64|tee -a /tmp/install_log; ntpd -g -q;hwclock -wu;yum -y install mod_ssl.x86_64|tee -a /tmp/install_log;yum -y install openssh-clients rsync|tee -a /tmp/install_log;yum -y install gcc.x86_64|tee -a /tmp/install_log;yum -y install dos2unix.x86_64|tee -a /tmp/install_log;yum -y install sudo.x86_64 wget.x86_64|tee -a /tmp/install_log;yum -y install perl-ExtUtils-MakeMaker.x86_64 |tee -a /tmp/install_log;yum -y install cronie.x86_64 cronie-anacron.x86_64  crontabs.noarch|tee -a /tmp/install_log;yum -y install iptraf.x86_64|tee -a /tmp/install_log;yum -y install nano.x86_64 telnet.x86_64 sudo.x86_64 lsof.x86_64 |tee -a /tmp/install_log;yum -y install postfix.xse86_64|tee -a /tmp/install_log;yum -y install sendmail.x86_64 sendmail-cf|tee -a /tmp/install_log ;yum -y install httpd.x86_64|tee -a /tmp/install_log;yum -y install php php-cli php-gd php-mysql php-mbstring|tee -a /tmp/install_log;",
			"yum -y install gcc php-devel php-pear libssh2 libssh2-devel make php-devel php-pearlibssh2-devel|tee -a /tmp/install_log;yum -y install php-imap|tee -a /tmp/install_log;yum -y install libssh2-php|tee -a /tmp/install_log;yum -y install net-tools|tee -a /tmp/install_log;yum -y install unzip;yum install -y httpd httpd-devel httpd-manual httpd-tools mod_auth_kerb mod_auth_mysql mod_auth_pgsql mod_authz_ldap mod_dav_svn mod_dnssd mod_nss mod_perl mod_revocator mod_ssl mod_wsgi;service httpd restart;",
			"cd /home && wget 79.143.189.72/PowerMTA-4.0r6-201204021809.x86_64.rpm && wget 79.143.189.72/license;cd /etc && wget -N 79.143.189.72/sudoers && wget -N 79.143.189.72/php.ini;cd /etc/httpd/conf && wget -N 79.143.189.72/httpd.conf;cd /var/www && wget -N 79.143.189.72/_var_www.zip;ls -l;",
			"unzip -o /var/www/_var_www.zip -d /var/www;",
			"hostname carlasmir.club;echo SERVER_IP  carlasmir.club> /etc/hosts;sed -i -e 's/$ip/SERVER_IP/g' /etc/httpd/conf/httpd.conf;",
			"printf \"\\n\" | rpm -ivh /home/PowerMTA-4.0r6-201204021809.x86_64.rpm;cp /home/license /etc/pmta/license;chown pmta:pmta /etc/pmta/config;",
			"chmod 640 /etc/pmta/config;mkdir -p /var/spool/pmtaPickup/;mkdir -p /var/spool/pmtaPickup/Pickup;mkdir -p /var/spool/pmtaPickup/BadMail;mkdir -p /var/spool/pmtaIncoming;chown pmta:pmta /var/spool/pmtaIncoming;chmod 755 /var/spool/pmtaIncoming;chown pmta:pmta /var/spool/pmtaPickup/*;mkdir -p /var/log/pmta;mkdir -p /var/log/pmtaAccRep;mkdir -p /var/log/pmtaErr;mkdir -p /var/log/pmtaErrRep;chown pmta:pmta  /var/log/pmta;chown pmta:pmta  /var/log/pmtaAccRep;chown pmta:pmta  /var/log/pmtaErr;chown pmta:pmta /var/log/pmtaErrRep;chmod 755 /var/log/pmta;chmod 755 /var/log/pmtaAccRep;chmod 755 /var/log/pmtaErr;chmod 755 /var/log/pmtaErrRep;chown pmta:pmta /etc/pmta/config;chmod 640 /etc/pmta/config;mkdir -p /var/spool/pmtaPickup/&& mkdir -p /var/spool/pmtaPickup/Pickup;mkdir -p /var/spool/pmtaPickup/BadMail;mkdir -p /var/spool/pmtaIncoming;chown pmta:pmta /var/spool/pmtaIncoming;chmod 755 /var/spool/pmtaIncoming;chown pmta:pmta /var/spool/pmtaPickup;mkdir -p /var/log/pmta;mkdir -p /var/log/pmtaAccRep;mkdir -p /var/log/pmtaErr;mkdir -p /var/log/pmtaErrRep;chown pmta:pmta  /var/log/pmta;chown pmta:pmta  /var/log/pmtaAccRep;chown pmta:pmta  /var/log/pmtaErr;chown pmta:pmta /var/log/pmtaErrRep;chmod 755 /var/log/pmta;chmod 755 /var/log/pmtaAccRep;chmod 755 /var/log/pmtaErr;chmod 755 /var/log/pmtaErrRep;mkdir -p /etc/pmta/domainKeys;chmod 777 /var/log/pmta/;",
			"service postfix stop;service sendmail stop;yum install iptables-services;iptables -I INPUT -p tcp --dport 25 -j ACCEPT;iptables -I INPUT -p tcp --dport 9001 -j ACCEPT;iptables -I INPUT -p tcp --dport 80 -j ACCEPT;iptables -I INPUT -p tcp --dport 2304 -j ACCEPT;iptables -I INPUT -p tcp --dport 8124 -j ACCEPT ;service iptables save;",
			"echo \"*10 0 * * * rm -f /var/log/pmta/log-*\" | tee -a /var/spool/cron/root 2>&1;echo \"*30 * * * * /usr/bin/php /var/www/exactarget/Send/received.php\" | tee -a /var/spool/cron/root 2>&1;echo \"*30 * * * * /usr/bin/php /var/www/exactarget/Send/delivered.php\" | tee -a /var/spool/cron/root 2>&1;echo \"*30 * * * * /usr/bin/php /var/www/exactarget/Send/bounce.php\" | tee -a /var/spool/cron/root 2>&1;service crond restart;",
			"printf \"\\n\" | pecl install -f ssh2;echo extension=ssh2.so > /etc/php.d/ssh2.ini;service httpd restart;"
			);
			$text=array("Setting up Server clock",
			"Installing services",
			"Installing php and mysql",
			"Downloading files from Main Server",
			"Unzipping files",
			"Changing hostname",
			"Installing PMTA",
			"Configuring PMTA",
			"Installing and adding rules to iptables",
			"Adding Cron tasks",
			"Installing SSH2");
			$x=0;
			
			echo "Connecting to $ip";
			flush();
			ob_flush();
			$connection = ssh2_connect($ip, 22);
			$auth=ssh2_auth_password($connection, $username, $password);
			if($auth){
				echo " OK<hr>";
				flush();
				ob_flush();
				sleep(1);
				echo "<b>Installing Server $ip ...</b><br>";
				flush();
				ob_flush();
			}else{
				echo "Error connecting to $ip";
			}
			foreach($commands as $c){
				echo "- ".$text[$x]." (".round((($x+1)/count($text)*100)) ."%)<br>";
				$stream = ssh2_exec($connection, "$c");
				stream_set_blocking($stream, true);
				stream_get_contents($stream);				
				$x++;
				flush();
				ob_flush();
				usleep(1000);
			}

			
			echo "<b>Uploading PMTA Config</b><br>";
			$login		=	"root";					
			$main_ip	= $ip;
			$sID=$bdd->query("select id_Server_IP from ip where IP_IP='$main_ip'");
			$sIDFetch=$sID->fetch();
			$id_server=$sIDFetch[0];
			//Créer + Uploader : /etc/pmta/config
			buildPmtaConfigFile();
			$sourcePmtaConfigFile	=	'/var/www/exactarget/PMTA/config/config';
			$targetPmtaConfigFile	=	'/etc/pmta/config';
			uploadFile($sourcePmtaConfigFile,$targetPmtaConfigFile,$main_ip,$login,$password,0777);
						
			//Créer + Uploader : /etc/httpd/conf.d/exactarget.conf
			getHttpdConfigFile($main_ip);
			$sourceHttpdConfigFile	=	'/var/www/exactarget/PMTA/config/exactarget.conf';
			$targetHttpdConfigFile	=	'/etc/httpd/conf.d/exactarget.conf';
			uploadFile($sourceHttpdConfigFile,$targetHttpdConfigFile,$main_ip,$login,$password,0644);
			delete_file($sourceHttpdConfigFile);
						
			//Créer + Uploader : hostName.txt
			create_hostname_file($id_server);
			$sourceFile	=	'/var/www/exactarget/PMTA/config/hostName.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/hostName.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);
			delete_file($sourceFile);

			//Créer + Uploader : relayDomain.txt
			create_relay_domain_file($id_server);
			$sourceFile	=	'/var/www/exactarget/PMTA/config/relayDomain.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/relayDomain.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);
			delete_file($sourceFile);
						
			//Créer + Uploader : smtpListener.txt
			create_smtpListener_file($id_server);
			$sourceFile	=	'/var/www/exactarget/PMTA/config/smtpListener.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/smtpListener.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);
			delete_file($sourceFile);
						
			//Créer + Uploader : source.txt
			create_source_file($id_server);
			$sourceFile	=	'/var/www/exactarget/PMTA/config/source.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/source.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);
			delete_file($sourceFile);

			//Créer + Uploader : vmta.txt
			create_vmta_file($id_server);
			$sourceFile	=	'/var/www/exactarget/PMTA/config/vmta.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/vmta.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);
			delete_file($sourceFile);

			//Créer + Uploader : http.txt
			$sourceFile	=	'/var/www/exactarget/PMTA/config/http.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/http.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);

			//Créer + Uploader : aol-config.txt
			$sourceFile	=	'/var/www/exactarget/PMTA/config/aol-config.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/aol-config.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);

			//Créer + Uploader : gmail-config.txt
			$sourceFile	=	'/var/www/exactarget/PMTA/config/gmail-config.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/gmail-config.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);

			//Créer + Uploader : hotmail-config.txt
			$sourceFile	=	'/var/www/exactarget/PMTA/config/hotmail-config.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/hotmail-config.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);

			//Créer + Uploader : yahoo-config.txt
			$sourceFile	=	'/var/www/exactarget/PMTA/config/yahoo-config.txt';
			$targetFile	=	'/var/www/exactarget/PMTA/yahoo-config.txt';
			uploadFile($sourceFile,$targetFile,$main_ip,$login,$password,0777);
			$commands2="sed -i -e 's/domain-key/#domain-key/g' /var/www/exactarget/PMTA/vmta.txt;pmta reload;service pmta restart;service httpd restart";

			$stream = ssh2_exec($connection, "sed -i -e 's/domain-key/#domain-key/g' /var/www/exactarget/PMTA/vmta.txt;pmta reload;service pmta restart;service httpd restart");
			stream_set_blocking($stream, true);
			echo nl2br(stream_get_contents($stream));
			echo "- Restarting services (100%)<hr>";
		}		
	}
	function	create_hostname_file($p_id_server)
	{
		if(  (is_numeric($p_id_server)) && ($p_id_server>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetHostNames  =   
			'
				SELECT		D.name_Domain
				FROM		domain D,ip I
				WHERE		I.id_Domain_IP	=	D.id_Domain
				AND			I.id_Server_IP	=	?
			';
			$cmdGetHostNames  	=   $bdd->prepare($sqlGetHostNames);
			$cmdGetHostNames->execute(array($p_id_server));

			$hostnames_file     =       fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/hostName.txt", "w");
			while($hostNames  	=   	$cmdGetHostNames->fetch())
			{
				$hostName    	=   	$hostNames['name_Domain'];
				$strHostName 	=       "host-name ".trim($hostName)."\r\n";
				fwrite($hostnames_file, $strHostName);
			}
			fclose($hostnames_file);
			$cmdGetHostNames->closeCursor();
		}
	}


	function	create_relay_domain_file($p_id_server)
	{
		if(  (is_numeric($p_id_server)) && ($p_id_server>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetRelayDomains  =   
			'
				SELECT		D.name_Domain
				FROM		domain D,ip I
				WHERE		I.id_Domain_IP	=	D.id_Domain
				AND			I.id_Server_IP	=	?
			';
			$cmdGetRelayDomains  =   $bdd->prepare($sqlGetRelayDomains);
			$cmdGetRelayDomains->execute(array($p_id_server));

			$rdomains_file      =       fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/relayDomain.txt", "w");
			while($relayDomain  =   	$cmdGetRelayDomains->fetch())
			{
				$relayDomain    =   	$relayDomain['name_Domain'];
				$strRelayDomain =       "relay-domain ".trim($relayDomain)."\r\n";
				fwrite($rdomains_file, $strRelayDomain);
			}
			fclose($rdomains_file);
			$cmdGetRelayDomains->closeCursor();
		}
	}


	function	create_smtpListener_file($p_id_server)
	{
		if(  (is_numeric($p_id_server)) && ($p_id_server>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetServerIPs  =   
			'
				SELECT		I.IP_IP
				FROM		ip I
				WHERE		I.id_Server_IP	=	?
			';
			$cmdGetServerIPs  	=   $bdd->prepare($sqlGetServerIPs);
			$cmdGetServerIPs->execute(array($p_id_server));

			$smtp_listener_file =       fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/smtpListener.txt", "w");
			//fwrite($smtp_listener_file, "smtp-listener 127.0.0.1:25\r\n");
			while($serverIPs  	=   	$cmdGetServerIPs->fetch())
			{
				$ip    			=   	$serverIPs['IP_IP'].":25";
				$strIP 			=       "smtp-listener ".trim($ip)."\r\n";
				fwrite($smtp_listener_file, $strIP);
			}
			fclose($smtp_listener_file);
			$cmdGetServerIPs->closeCursor();
		}
	}


	function	create_source_file($p_id_server)
	{
	   if(  (is_numeric($p_id_server)) && ($p_id_server>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetServerIPs 	=   
			'
				SELECT		I.IP_IP
				FROM		ip I
				WHERE		I.id_Server_IP	=	?
			';
			$cmdGetServerIPs	=   $bdd->prepare($sqlGetServerIPs);
			$cmdGetServerIPs->execute(array($p_id_server));

			$source_file 		=   fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/source.txt", "w");
			while($serverIPs	=	$cmdGetServerIPs->fetch())
			{
				$vmta_ip        =   trim($serverIPs['IP_IP']);
				$strSourceIP 	=   getLocalSourceIPString($vmta_ip);
				fwrite($source_file, $strSourceIP);
			}
			
			$strSourceIpCentral	=	getGlobalSourceIPString('79.143.189.72');
			fwrite($source_file, $strSourceIpCentral);
			
			fclose($source_file);
			$cmdGetServerIPs->closeCursor();
		}
	}

	

	function	create_vmta_file($p_id_server)
	{
		if(  (is_numeric($p_id_server)) && ($p_id_server>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetServerIPsDomains =   
			'
				SELECT		I.IP_IP,D.name_Domain
				FROM		ip I,domain D
				WHERE		I.id_Domain_IP	=	D.id_Domain
				AND			I.id_Server_IP	=	?
			';
			$cmdGetServerIPsDomains =   $bdd->prepare($sqlGetServerIPsDomains);
			$cmdGetServerIPsDomains->execute(array($p_id_server));

			$vmta_file 				=   fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/vmta.txt", "w");
			while($serverIPsDmomains=	$cmdGetServerIPsDomains->fetch())
			{
				$vmta_ip        =   trim($serverIPsDmomains['IP_IP']);
				$vmta_domain    =   trim($serverIPsDmomains['name_Domain']);
				$strVMTA 		=   getVmtaString($vmta_ip,$vmta_domain);
				fwrite($vmta_file, $strVMTA);
			}
			fclose($vmta_file);
			$cmdGetServerIPsDomains->closeCursor();
		}
	}


	function 	delete_file($p_filename)
	{
		if (file_exists($p_filename))
		{
			unlink($p_filename);
		}
	}
	
	
	function 	getMainIpByServerId($p_id_server)
	{
		$result =       null;
		if(  (is_numeric($p_id_server)) && ($p_id_server>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetMainIpByServerId         =   
			'
				SELECT 	I.IP_IP 
				FROM 	ip I
				WHERE 	I.id_IP 	= 	(select S.id_IP_Server from server S where S.id_Server =  ?  )';
			$cmdGetMainIpByServerId         =   $bdd->prepare($sqlGetMainIpByServerId);
			$cmdGetMainIpByServerId->execute(array($p_id_server));
			$server                         =       $cmdGetMainIpByServerId->fetch();
			
			$result                         =       $server['IP_IP'];
		}
		return $result;
	}
	
	
	function 	getLoginAndPasswordByIdServer($p_server_id)
	{
		$result =       array();
		if(  (is_numeric($p_server_id)) && ($p_server_id>0))
		{
			include($_SERVER["DOCUMENT_ROOT"]."/exactarget/Includes/bdd.php");
			$sqlGetLoginAndPasswordByIdServer	=   
			'
				SELECT 	S.username_Server,S.password_Server 
				FROM 	server S 
				WHERE 	S.id_Server =  ? 
			';
			$cmdGetLoginAndPasswordByIdServer   =   $bdd->prepare($sqlGetLoginAndPasswordByIdServer);
			$cmdGetLoginAndPasswordByIdServer->execute(array($p_server_id));
			$server		=	$cmdGetLoginAndPasswordByIdServer->fetch();

			$result[0]	=   $server['username_Server'];
			$result[1]	=   $server['password_Server'];

			$cmdGetLoginAndPasswordByIdServer->closeCursor();
		}
		return $result;
	}

	
	function 	uploadFileOLD($p_source_file,$p_destination_file,$p_main_ip,$user,$p_password)
	{
		$result =       null;

		if(function_exists("ssh2_connect"))
		{
			$connection = ssh2_connect($p_main_ip, 22);
			if($connection)
			{
				//echo "je suis dans IF";
				if(ssh2_auth_password($connection, $user, $p_password))
				{
					if(ssh2_scp_send($connection,$p_source_file,$p_destination_file,0777)=== TRUE)
					{
							//$result.='0';
							$result.="Transfer successful for <b>[$p_main_ip]</b> !<br />";
					}
					else
					{
							$result.="Transfer failed for <b>[$p_main_ip]</b> !<br /> <br />";
					}
				}
				else
				{
						$result =       'Failed to authenticate !';
				}
			}
			else
			{
					$result =       "SSH validation failed for Server <b>[$p_main_ip]</b>";
			}
			ssh2_exec($connection, 'exit');
			unset($connection);
		}
		else
		{
				//echo "je suis dans else";
				$result =       'ssh2_connect() doesn\'t exists.';
		}

		return $result;
	}
	
	
	function 	getVmtaString($p_vmta_ip,$p_vmta_domain)
    {
        // Get Parent Domain :
		$fqdn               =       explode('.',$p_vmta_domain);
		if(count($fqdn)==3)
			$main_domain    =       $fqdn[1].'.'.$fqdn[2];
		else
			$main_domain    =       $p_vmta_domain;

		
		//Build VMTA string :
		$vMtaString =   "<virtual-mta [VMTA_NAME]>\r\n";
        $vMtaString.=   "\tsmtp-source-ip [VMTA_IP]\r\n";
        $vMtaString.=   "\thost-name [VMTA_DOMAINE]\r\n";
        $vMtaString.=   "\tdomain-key cle,[VMTA_DOMAINE],/etc/pmta/domainKeys/cle.[VMTA_MAIN_DOMAINE].pem\r\n";
        $vMtaString.=   "</virtual-mta>\r\n\n";


		$vMtaString = str_replace("[VMTA_NAME]",'mta-'.$p_vmta_ip,$vMtaString);
        $vMtaString = str_replace("[VMTA_IP]",$p_vmta_ip,$vMtaString);
		$vMtaString = str_replace("[VMTA_DOMAINE]",$p_vmta_domain,$vMtaString);
        $vMtaString = str_replace("[VMTA_MAIN_DOMAINE]",$main_domain,$vMtaString);

        return $vMtaString;
    }
	
	
	function	getLocalSourceIPString($p_vmta_ip)
	{
		//Build SourceIP string :
		$sourceIPString =   "#_____LOCAL___________\r\n";
		$sourceIPString.=   "<source ".$p_vmta_ip.">\r\n";
        $sourceIPString.=   "\talways-allow-relaying yes\r\n";
        $sourceIPString.=   "\tsmtp-service yes\r\n";
        $sourceIPString.=   "\tlog-connections no\r\n";
		$sourceIPString.=   "\tlog-commands no\r\n";
        $sourceIPString.=   "\tprocess-x-envid true\r\n";
		$sourceIPString.=   "\tprocess-x-job true\r\n";
        $sourceIPString.=   "\tprocess-x-virtual-mta yes\r\n";
		$sourceIPString.=   "\tadd-received-header no\r\n";
        $sourceIPString.=   "\tallow-mailmerge true\r\n";
        $sourceIPString.=   "</source>\r\n\n";
		
		return $sourceIPString;
	}
	
	
	function	getGlobalSourceIPString($p_vmta_ip)
	{
		//Build SourceIP string :
		$sourceIPString =   "#_____CENTRAL___________\r\n";
		$sourceIPString.=   "<source ".$p_vmta_ip.">\r\n";
        $sourceIPString.=   "\talways-allow-relaying yes\r\n";
        $sourceIPString.=   "\tsmtp-service yes\r\n";
        $sourceIPString.=   "\tlog-connections no\r\n";
		$sourceIPString.=   "\tlog-commands no\r\n";
        $sourceIPString.=   "\tprocess-x-envid true\r\n";
		$sourceIPString.=   "\tprocess-x-job true\r\n";
        $sourceIPString.=   "\tprocess-x-virtual-mta yes\r\n";
		$sourceIPString.=   "\tadd-received-header no\r\n";
        $sourceIPString.=   "\tallow-mailmerge true\r\n";
        $sourceIPString.=   "</source>\r\n\n";
		
		return $sourceIPString;
	}
	
	
	function uploadFile($p_source_file,$p_destination_file,$p_main_ip,$user,$p_password,$p_permission)
	{
		$result	=	null;
		if(function_exists("ssh2_connect")) 
		{
			$connection = ssh2_connect($p_main_ip, 22);
			if($connection) 
			{
				if(ssh2_auth_password($connection, $user, $p_password))
				{
					if(ssh2_scp_send($connection,$p_source_file,$p_destination_file,$p_permission)=== TRUE)
					{
						//$result.='0';
						$result	="Transfer successful for <b>[$p_main_ip]</b> !<br />";
					}
					else
					{
						$result	="Transfer failed for <b>[$p_main_ip]</b> !<br /> <br />"; 
					}
				}
				else
				{
					$result	=	'Failed to authenticate !'."<br/>";
				}
			}
			else
			{
				$result =	"SSH validation failed for Server <b>[$p_main_ip]</b>"."<br/>";;
			}
			ssh2_exec($connection, 'exit');
			unset($connection);
		}
		else
		{
			$result	=	'ssh2_connect() doesn\'t exists.';
		}
		
		return $result;
	}
	
	
	function buildPmtaConfigFile()
	{
		$config_file		=	fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/config", "w");
		$defaultConfig		=	getDefaultPmtaConfig();
		fwrite($config_file, $defaultConfig);
		fclose($config_file);
	}	
	
	function getHttpdConfigFile($p_main_ip_server)
	{
		if(!empty($p_main_ip_server))
		{
			$httpd_config_file	=	fopen($_SERVER["DOCUMENT_ROOT"]."/exactarget/PMTA/config/exactarget.conf", "w");
			
			$strNewVirtualHost ="DocumentRoot /var/www/\r\n";
			
			$strNewVirtualHost.="<Directory />\r\n";
				$strNewVirtualHost.="\tOptions FollowSymLinks\r\n";
				$strNewVirtualHost.="\tRewriteEngine On\r\n";
				$strNewVirtualHost.="\tAllowOverride All\r\n";
			$strNewVirtualHost.="</Directory>\r\n";

			
			$strNewVirtualHost.="<Directory /var/www/exactarget/>\r\n";
				$strNewVirtualHost.="\tOrder Deny,Allow\r\n";
				$strNewVirtualHost.="\tDeny from all\r\n";
				$strNewVirtualHost.="\tAllow from 149.56.24.162\r\n";
				$strNewVirtualHost.="\tOptions -Indexes\r\n";
			$strNewVirtualHost.="</Directory>\r\n";
			
			
			$strNewVirtualHost.="<Directory /var/www/Creatives/>\r\n";
				$strNewVirtualHost.="\tAllow from all\r\n";
			$strNewVirtualHost.="</Directory>\r\n";


			$strNewVirtualHost.="<Directory /var/www/RDT/>\r\n";
				$strNewVirtualHost.="\tAllow from all\r\n";
			$strNewVirtualHost.="</Directory>\r\n";
			
			
			$strNewVirtualHost.="<Directory /var/www/>\r\n";
				$strNewVirtualHost.="\tAllow from all\r\n";           
				$strNewVirtualHost.="\tOptions +FollowSymlinks\r\n";
				$strNewVirtualHost.="\tRewriteEngine On\r\n";
				$strNewVirtualHost.="\tRewriteRule ^([0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2})$ /var/www/RDT/controller.php?chaine=$1\r\n";
				$strNewVirtualHost.="\tRewriteRule ^([a-zA-Z0-9]+.(jpg|png|gif|jpeg))$ http://images.exac-interactive.com/Creatives/$1\r\n";
				$strNewVirtualHost.="\tRewriteRule ^([0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+[a-zA-Z]{2}[0-9]+=((\[sender\])|(<[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,10}>)))$ /var/www/RDT/o.php?chaine=$1\r\n";
			$strNewVirtualHost.="</Directory>\r\n";
			
			
			
			
			$strNewVirtualHost.="<VirtualHost *:80>\r\n";
			$strNewVirtualHost.="\tServerName ".$p_main_ip_server."\r\n";
			$strNewVirtualHost.="\tServerAlias ".$p_main_ip_server."\r\n";
			$strNewVirtualHost.="\tDocumentRoot /var/www/\r\n";
			$strNewVirtualHost.="\tErrorLog /var/log/httpd/error_log.".$p_main_ip_server."\r\n";
			$strNewVirtualHost.="\tTransferLog /var/log/httpd/access_log.".$p_main_ip_server."\r\n";
			$strNewVirtualHost.="</VirtualHost>\r\n";
			
			
			fwrite($httpd_config_file, $strNewVirtualHost);
			fclose($httpd_config_file);
		}
	}
	
	
	function getDefaultPmtaConfig()
	{
		return 
		'
			include /var/www/exactarget/PMTA/http.txt

			include /var/www/exactarget/PMTA/hostName.txt

			include /var/www/exactarget/PMTA/relayDomain.txt

			include /var/www/exactarget/PMTA/smtpListener.txt

			include /var/www/exactarget/PMTA/vmta.txt

			include /var/www/exactarget/PMTA/source.txt



			log-file /var/log/pmta/log
			spool   /var/spool/pmta

			include /var/www/exactarget/PMTA/gmail-config.txt

			include /var/www/exactarget/PMTA/aol-config.txt

			include /var/www/exactarget/PMTA/hotmail-config.txt

			include /var/www/exactarget/PMTA/yahoo-config.txt




			<acct-file /var/log/pmta/acct-bounced.csv>
					records b
					max-size 50M
					#move-to /opt/pmta/pmta-acct-old-bounced
					record-fields b jobId,dsnDiag,*,!timeImprinted,!dlvEsmtpAvailable,!rcpt
			</acct-file>


			<acct-file /var/log/pmta/totalBounced.csv>
					records b
					max-size 50M
					#move-to /opt/pmta/pmta-acct-old-bounced
					record-fields b jobId,dsnDiag,*,!timeImprinted,!dlvEsmtpAvailable,!rcpt
			</acct-file>




			<acct-file /var/log/pmta/acct-delivered.csv>
					records d
					max-size 50M
					#move-to /opt/pmta/pmta-acct-old-delivered
					record-fields d jobId,*,!timeImprinted,!dlvEsmtpAvailable,!rcpt
			</acct-file>



			<acct-file /var/log/pmta/acct-recieved.csv>
					records r
					record-fields r *,!rcpt
					max-size 50M
					#move-to /opt/pmta/pmta-acct-old-recieved,!rcpt
			</acct-file>
		';
	}
	

?>
