<?php
  header("Access-Control-Allow-Origin: *"); 
  set_time_limit(0);
  date_default_timezone_set('UTC');
  
  $year  = date('Y');
  $month = date('m');
  $day   = date('d');
  
  $idSend = $_POST['idSend'];
  
  
  
  if($idSend>0)
  {
	 
	 $result = shell_exec("sudo grep -a '$idSend-[0-9]\+-[0-9]\+-[0-9]\+' /var/log/pmta/totalBounced-$year-$month-$day-0000.csv | cut -d , -f 6,2,3,5,9,10,11,12,13,14,15,16 | tail -n 50");
	 
     //$line = explode(PHP_EOL,$result);

	 echo '<pre>'.$result.'</pre>';
	 
  }
  
  else if($idSend==0)
  {
	  
		$idMailer 				 = $_POST['idMailer'];
		
		$result = shell_exec("sudo grep -a '0-0-$idMailer-0' /var/log/pmta/totalBounced-$year-$month-$day-0000.csv | cut -d , -f 6,2,3,5,9,10,11,12,13,14,15,16 | tail -n 50");

		echo '<pre>'.$result.'</pre>';
  }
  
?>