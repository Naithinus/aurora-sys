<?php

	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
 
     set_time_limit(0);
	 
	ini_set('display_errors', 0);
	 
	 $isp      = $_POST['cmbISP'];
	 $email    = $_POST['txtEmail'];
	 $password = $_POST['txtPassword'];
	 $folder   = $_POST['cmbFolder'];
	

	 switch($isp)
	 {
	    case 'aol':
		  
					  echo'
						 <table class="table datatable-basic">
														<thead>
															<tr>
																<th>From Name</th>
																<th>From Email</th>
																<th>Subject</th>
																<th>Return Path</th>
																<th>x-aol-override-pik-reason</th>
															</tr>
														</thead>
														
														<tbody>
								';

				  $imap = imap_open("{imap.aol.com:143}$folder", $email, $password);
				  $n_msgs = imap_num_msg($imap);
				  
					for ($i=$n_msgs; $i>0; $i--)
					{
					  $headerText = strtolower(imap_fetchheader($imap,$i));
					  
					  //GET RETURN PATH
					  preg_match_all('[return-path: <.*>]',$headerText,$out);
					  
					  $returnPathFULL = $out[0][0];
					  $spl = explode('return-path: <',$returnPathFULL);
					  $returnPath = rtrim($spl[1],'>');
					  
					  
					  //GET x-aol-override-pik-reason
					  preg_match_all('[x-aol-override-pik-reason: y]',$headerText,$out);
					  $xAOL = count($out[0]);
					  
					  $header     = imap_header($imap,$i);
					  $fromName   = '';

					  $fromName   = isset($header->from[0]->personal) ? $header->from[0]->personal : '';
					  $fromEmail  = $header->from[0]->mailbox.'@'.$header->from[0]->host;
					  $subject    = $header->subject;
					  echo
					  '
						<tr>
						 <td>'.$fromName.'</td>
						 <td>'.$fromEmail.'</td>
						 <td>'.$subject.'</td>
						 <td>'.$returnPath.'</td>
						 <td>'.$xAOL.'</td>
						</tr>
					  ';
					   
				  }
						
				break;
		
		
		case 'yahoo':
		  
		  	  echo'
					 <table class="table datatable-basic">
						<thead>
							<tr>
								<th>From Name</th>
								<th>From Email</th>
								<th>Subject</th>
								<th>Return Path</th>
							</tr>
						</thead>
						
						<tbody>
						';

							  $imap = imap_open("{imap.mail.yahoo.com:993/imap/ssl}$folder", $email, $password);
							  $n_msgs = imap_num_msg($imap);
							  
								for ($i=$n_msgs; $i>0; $i--)
								{
								  $headerText = strtolower(imap_fetchheader($imap,$i));
								  
								  //GET RETURN PATH
								  preg_match_all('[return-path: <.*>]',$headerText,$out);
								  
								  $returnPathFULL = $out[0][0];
								  $spl = explode('return-path: <',$returnPathFULL);
								  $returnPath = rtrim($spl[1],'>');
								  
								  
								  
								  $header     = imap_header($imap,$i);
								  $fromName   = '';

								  $fromName   = isset($header->from[0]->personal) ? $header->from[0]->personal : '';
								  $fromEmail  = $header->from[0]->mailbox.'@'.$header->from[0]->host;
								  $subject    = $header->subject;
								  echo
								  '
									<tr>
									 <td>'.$fromName.'</td>
									 <td>'.$fromEmail.'</td>
									 <td>'.$subject.'</td>
									 <td>'.$returnPath.'</td>
									</tr>
								  ';
								   
							  }
									
							break;
		
	case 'hotmail':
		
		if($folder=='SPAM')
			$folder='Junk';
		
		
		$chaine = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789';
		$exist = false;
		$fileNega = NULL;
		
		do
		{
			$concat = '';
			for($index = 0 ;$index<10; $index++ )
			{
				$rand = rand(0,strlen($chaine));
				$concat.=$chaine[$rand];
			}				
			if(file_exists('/var/www/exactarget/negaIMAP/'.$concat.'.txt'))
				$exist = true;
			else
			{
				$exist = false;
				$fileNega = fopen('/var/www/exactarget/negaIMAP/'.$concat.'.txt','w+');
			}
			
		}while($exist);
		
		$pathNega = '../negaIMAP/'.$concat.'.txt';
		
		echo '<center><h1><a href="'.$pathNega.'" download><span class="label bg-danger"><h5>DOWNLOAD</h5></span></a></h1></center>';
		
		echo
		'
			<table class="table datatable-basic">
				<thead>
					<tr>
						<th>From Name</th>
						<th>Subject</th>
						<th>x-store-info</th>
						<th>Spam/Inbox</th>
					</tr>
				</thead>
				
				<tbody>
		';
		
		
		
		$mailbox				= 	imap_open("{imap-mail.outlook.com:993/imap/ssl}$folder", $email, $password);
		#$n_msgs 			= 	imap_num_msg($imap);
		
		
					
				
				print_r(imap_errors());
				if ($mailbox) {
					$check = imap_check($mailbox);
					
	
					$nbmail = imap_num_msg($mailbox);
					
					if($nbmail != 0){
					$emails = imap_search( $mailbox, 'TEXT "D00"');
					if( $emails ) {
					foreach( $emails as $email_uid ) {
						
						
						
						$message = imap_fetchbody($mailbox,$email_uid,1);
						
						echo $message;
					fputs($fileNega,$message);
				
						if($result) {
							echo 'Operation succes !!<br/>';
						}
						if($status) {
							echo 'status succes !!<br/>';
						}
					
					}
					}
				}
				}
					
			
			
			
		     imap_close($mailbox);	
		}
		
		
													

	
	
     
	
echo
'
</tbody>
</table>
';
     


?>

<script type="text/javascript" src="datatables3_basic.js"></script>