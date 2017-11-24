<?php
 
 $command = $_POST['command'];
 $queue   = $_POST['queue'];
 
 switch($command)
 {
    case 'delete':
	
	$result = exec('sudo pmta delete --queue='.$queue);
	echo $result;
	 
	break;
	
	case 'pause':
	
	$result = exec('sudo pmta pause queue '.$queue);
	echo $result;
	 
	break;
	
	case 'resume':
	
	$result = exec('sudo pmta resume queue '.$queue);
	echo $result;
	 
	break;
	
	case 'reset':
	
	$result = exec('sudo pmta reset counters');
	echo $result;
	 
	break;
 }
?>