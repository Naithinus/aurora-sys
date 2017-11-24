<?php

    set_time_limit(0);
	ini_set('display_errors', 0);
	 
	$email    = $_POST['txtEmail'];
	$password = $_POST['txtPassword'];
	$folder   = $_POST['cmbFolder'];
	

	
	
		  
	echo'<textarea class="form-control" rows="10">';
		$imap 				= 	imap_open("{imap-mail.outlook.com:993/imap/ssl}$folder", $email, $password);
		$emails 			= 	imap_search($imap,'ALL');
		if($emails)
		{
			rsort($emails);
			foreach($emails as $current_email) 
			{
				$overview	= 	imap_fetch_overview($imap,$current_email,0);
				$message	=	imap_fetchbody($imap,$current_email,1);
				$message 	= 	preg_replace('/\s+/', ' ', trim($message));
				echo trim($message)."\n";
			}
		}
		else
		{
			echo 'Cannot connect to MAILBOX: ' . imap_last_error();
		}
		imap_close($imap);
	echo'</textarea>';
     
	 

?>