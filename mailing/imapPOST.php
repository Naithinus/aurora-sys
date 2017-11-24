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
						 <table>
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
					 <table>
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
			<table>
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
		
		
		
		$imap 				= 	imap_open("{imap-mail.outlook.com:993/imap/ssl}$folder", $email, $password);
		$n_msgs 			= 	imap_num_msg($imap);
		for ($i=$n_msgs; $i>0; $i--)
		{
			$header     	= imap_header($imap,$i);
			$fromName   	= isset($header->from[0]->personal) ? $header->from[0]->personal : '';
			$subject    	= $header->subject;
			
			//x-store-info :
			$headerText 	= strtolower(imap_fetchheader($imap,$i));
			preg_match_all('[x-store-info: .*]',$headerText,$res);
			$x_store_info 	= $res[0][0];
			$x_store_info 	= substr(trim($x_store_info),13,5);
			echo
			'
				<tr>
					<td>'.$fromName.'</td>
					<td>'.$subject.'</td>
					<td><b>'.$x_store_info.'</b></td>
					<td>'.get_ip_status($x_store_info).'</td>
				</tr>
			';
			if($i!=1)
		      fputs($fileNega,$subject.PHP_EOL);
		    else
			  fputs($fileNega,$subject);
		}
		imap_close($imap);
		break;
	}
     
	
echo
'
</tbody>
</table>
';
     
function	get_ip_status($p_xstore_info)
{
	$result	=	null;
	if(!empty($p_xstore_info))
	{
		if(  (strpos($p_xstore_info,'J++') !== false) or  (strpos($p_xstore_info,'sbev') !== false) )
		{
			$result	=	'<span class="label bg-success-400">Inbox</span>';
		}
		else
		{
			$result	=	'<span class="label bg-danger-400">Spam</span>';
		}
	}
	return $result;
}	 

?>

<script type="text/javascript" src="datatables3_basic.js"></script>