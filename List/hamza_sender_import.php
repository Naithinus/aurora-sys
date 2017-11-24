<?php
		
	include('../Includes/bdd.php');	
	ini_set("memory_limit","10000000000M");
	ini_set('display_errors', 1); 
	error_reporting(E_ALL);
	set_time_limit(0);
	
	
	//insert_sender(2171680,'h.benhaj@benit.ma',5);
	//exit;	
	
	
	$hdl 		= fopen('/home/data_sender.txt','r');
	while(!feof($hdl))
	{
		$line		=	fgets($hdl);
		$line		=	trim($line);
		$array_line	=	explode(',',$line);
		if(count($array_line>0))
		{
			$email			=	$array_line[0];
			$from_email		=	$array_line[1];	
			if(  (is_valid_email($email))  and  (is_valid_email($from_email))  )
			{
				$id_email	=	get_id_email_by_text_email($email);
				if($id_email>0)
				{
					$id_isp		=	5;
					$sender		=	$from_email;
					insert_sender($id_email,$sender,$id_isp);
				}
			}
		}
	}
	fclose($hdl);
	
	 
	function	get_id_email_by_text_email($p_text_email)
	{
		$result	=	null;
		if(!empty($p_text_email))
		{
			
			$sqlGetIdEmail  =
			"
				select id_Email from email where email_Email = ?
			";
			$cmdGetIdEmail  =   $bdd->prepare($sqlGetIdEmail);
			$cmdGetIdEmail->execute(array($p_text_email));
			$email			=	$cmdGetIdEmail->fetch();
			if($email)
			{
				$result		=	$email['id_Email'];
			}
			$cmdGetIdEmail->closeCursor();
			return $result;
		}
	}
	
	
	function	insert_sender($p_id_email,$p_sender,$p_id_isp)
	{
		
		$sqlInsertSender  	=	"insert into sender values (?,?,?)";
		$cmdInsertSender  	=   $bdd->prepare($sqlInsertSender);
		if(!$cmdInsertSender)
		{
			//print_r($bdd->errorInfo());
			return false;
		}
		else
		{
			if(!($cmdInsertSender->execute(array($p_id_email,$p_sender,$p_id_isp))))
			{
				//print_r($bdd->errorInfo());
				return false;
			}
			else
			{
				return true;
			}
			$cmdInsertSender->closeCursor();
		}
	}
	
	
	
	function 	is_valid_email($p_email)
	{
		if(!empty($p_email))
		{
			if (!filter_var($p_email, FILTER_VALIDATE_EMAIL)) 
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
?>