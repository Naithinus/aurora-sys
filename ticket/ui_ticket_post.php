<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 


    include('../Includes/bdd.php');
	
	//Ticket :
	$id_ticket				=	-1;
	$subject_ticket			=	(isset($_POST['subject_ticket'])?$_POST['subject_ticket']:null);
	$id_user_ticket			=	$_SESSION['id_Employer'];
	//Details :
	$message_ticket_details	=	$_POST['message_ticket_details'];
	$is_support_answer		=	($_SESSION['type_Employer'] == "IT")?true:false;
	
	
	
	
   
    // UPDATE
    if(isset($_POST['id_ticket']))
    {
		$id_ticket	=	$_POST['id_ticket'];
		if(   (is_numeric($id_ticket)) and ($id_ticket>0)  )
		{
			//Insert Ticket Details :
			$cmd 	= 	$bdd->prepare('insert into ticket_details VALUES(NULL,?,NULL,?,?)');
			$cmd->execute(array($id_ticket,$message_ticket_details,$is_support_answer));
			
			//Update Status :
			$id_ticket_status	=	($is_support_answer==true)?2:3; //2==>Answered   + //3==>Mailer Reply
			update_ticket_status($id_ticket,$id_ticket_status);
		}	
	}
   
    // INSERT
    else
    {
		$id_ticket_status	=	1; //Opened
		$requete 			= $bdd->prepare('insert into ticket VALUES(NULL,?,?,?)');
		$requete->execute(array($subject_ticket,$id_ticket_status,$id_user_ticket));
		$id_ticket		=	$bdd->lastInsertId(); 
		if($id_ticket>0)
		{
			$requete2 = $bdd->prepare('insert into ticket_details VALUES(NULL,?,NULL,?,?)');
			$requete2->execute(array($id_ticket,$message_ticket_details,$is_support_answer));
		}
		$requete->closeCursor();
		$requete2->closeCursor();
    }
	
	//Send Email to the webmaster :
	if($subject_ticket==null)
		$subject_ticket	=	get_ticket_subject_by_id($id_ticket);
	sendmail($subject_ticket,$message_ticket_details);
    
	//Redirection :
	header('location:list_ticket.php');
	
	
	
	function	sendmail($p_subject,$p_message)
	{
		$ip         = 'ip';
		$domain     = 'domain';
		$returnPath = 'contact@domain';		
		$to         = 'to';
		$subject    = 'New Opned Ticket : '.$p_subject;
		$message    = $p_message;
		$header     = "from:support@domain\nto:$to\nsubject:$subject\n$message\n.\n";
		
		@$fp = fsockopen($ip, 25);
		
		
		$telnet    = array();
		$telnet[0] = "telnet $ip\r\n";
		$telnet[1] = "HELO $domain\r\n";
		$telnet[2] = "MAIL FROM:$returnPath\r\n";
		$telnet[3] = "RCPT TO:$to\r\n";
		$telnet[4] = "DATA\r\n";
		$telnet[5] = $header;
			
			
		foreach ($telnet as $current) 
		{         
					fwrite($fp, $current);
					$smtpOutput=fgets($fp);
		}
	}
	
	function	update_ticket_status($p_id_ticket,$p_id_status)
	{
		include('../Includes/bdd.php');
		$cmdUpdateTicketStatus = $bdd->prepare('update ticket set id_ticket_status = ? where id_ticket = ?');
		$cmdUpdateTicketStatus->execute(array($p_id_status,$p_id_ticket));
		$cmdUpdateTicketStatus->closeCursor();
	}
	
	function	get_ticket_subject_by_id($p_id_ticket)
	{
		include('../Includes/bdd.php');
		$cmdGetTicketSubject = $bdd->prepare('select subject_ticket from ticket where id_ticket = ?');
		$cmdGetTicketSubject->execute(array($p_id_ticket));
		$row	=	$cmdGetTicketSubject->fetch();
		$cmdGetTicketSubject->closeCursor();
		
		return $row['subject_ticket'];
	}
?>