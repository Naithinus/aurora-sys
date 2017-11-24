<?php
	if(isset($_GET))
	{
		if( isset($_GET['ip']) and isset($_GET['user']) and isset($_GET['password']))
		{
			$main_ip					=	$_GET['ip'];
			$user						=	$_GET['user'];
			$password					=	$_GET['password'];
			
			//PMTA rpm installation file : 
			$pmta_source_folder			=	'/home/files_to_upload/pmta_3.5__x86_64.rpm';
			$pmta_destination_folder	=	'/home/pmta_3.5__x86_64.rpm';
			echo uploadFile($pmta_source_folder,$pmta_destination_folder,$main_ip,$user,$password,0777);
			
			//PMTA license file :
			$license_source_folder			='/home/files_to_upload/license';
			$license_destination_folder	=	'/home/license';
			echo uploadFile($license_source_folder,$license_destination_folder,$main_ip,$user,$password,0777);
		
			//SSH pass :
			$sshpass_source_folder			=	'/home/files_to_upload/sshpass__x86_64.rpm';
			$sshpass_destination_folder		=	'/home/sshpass__x86_64.rpm';
			echo uploadFile($sshpass_source_folder,$sshpass_destination_folder,$main_ip,$user,$password,0777);
			
			
			//php.ini :
			$phpini_source_folder			=	'/home/files_to_upload/php.ini';
			$phpini_destination_folder		=	'/etc/php.ini';
			echo uploadFile($phpini_source_folder,$phpini_destination_folder,$main_ip,$user,$password,0644);
		
			
			//sudoers :
			$sudoers_source_folder			=	'/home/files_to_upload/sudoers';
			$sudoers_destination_folder		=	'/etc/sudoers';
			echo uploadFile($sudoers_source_folder,$sudoers_destination_folder,$main_ip,$user,$password,0440);
			
			
			//recievedOriginal.csv
			$recieved_source_folder			=	'/home/files_to_upload/recievedOriginal.csv';
			$recieved_destination_folder	=	'/var/log/pmta/recievedOriginal.csv';
			echo uploadFile($recieved_source_folder,$recieved_destination_folder,$main_ip,$user,$password,0644);
			
			
			//bouncedOriginal.csv
			$bounced_source_folder			=	'/home/files_to_upload/bouncedOriginal.csv';
			$bounced_destination_folder		=	'/var/log/pmta/bouncedOriginal.csv';
			echo uploadFile($bounced_source_folder,$bounced_destination_folder,$main_ip,$user,$password,0644);
			
			
			//deliveredOriginal.csv
			$delivered_source_folder		=	'/home/files_to_upload/deliveredOriginal.csv';
			$delivered_destination_folder	=	'/var/log/pmta/deliveredOriginal.csv';
			echo uploadFile($delivered_source_folder,$delivered_destination_folder,$main_ip,$user,$password,0644);
			
			
			
			
			
			
			//html template :
			//$html_template_source_folder			=	'/home/files_to_upload/html_template.tar.gz';
			//$html_template_destination_folder		=	'/var/www/html_template.tar.gz';
			//echo uploadFile($html_template_source_folder,$html_template_destination_folder,$main_ip,$user,$password,0777);
		
		
			//extractor.php :
			//$tar_extractor_source_folder			=	'/home/files_to_upload/extractor.php';
			//$tar_extractor_destination_folder		=	'/var/www/extractor.php';
			//echo uploadFile($tar_extractor_source_folder,$tar_extractor_destination_folder,$main_ip,$user,$password,0777);
			
			
			
			//Extract the tar archive :
			//$extractorLink	=	'http://'.$main_ip.'/extractor.php';
			//file_get_contents($extractorLink);
		}
		else
		{
			echo '$_GET["ip"] or $_GET["user"] or $_GET["password"] are not set';
		}
	}
	else
	{
		echo 'isset($_GET) : is not set';
	}
		


		
function uploadFile($p_source_file,$p_destination_file,$p_main_ip,$user,$p_password,$p_permissions)
{
	$result	=	null;
	if(function_exists("ssh2_connect")) 
	{
		$connection = ssh2_connect($p_main_ip, 22);
		if($connection) 
		{
			if(ssh2_auth_password($connection, $user, $p_password))
			{
				if(ssh2_scp_send($connection,$p_source_file,$p_destination_file,$p_permissions)=== TRUE)
				{
					$result.="Transfer successful for <b>[$p_main_ip]</b> !----$p_source_file<br />";
				}
				else
				{
					$result.="Transfer failed for <b>[$p_main_ip]</b> !----$p_source_file<br /> <br />"; 
				}
			}
			else
			{
				$result	=	'Failed to authenticate !';
			}
		}
		else
		{
			$result =	"SSH validation failed for Server <b>[$p_main_ip]</b>";
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




?>