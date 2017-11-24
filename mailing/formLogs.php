<?php
	
	
	include_once('../Includes/sessionVerificationMailer.php'); 
    $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    verify($monUrl);
	

	
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 include('../Includes/bdd.php');
	 
	 
	 
	 $link       = $_POST['link'];
	 $idSend     = $_POST['idSend'];
	 $idMailer   = $_POST['idMailer'];
	 
	 $ch = curl_init();
	 
	 
	 $fields = array(
		'idSend' 		    =>      urlencode($idSend),
		'idMailer'          =>      urlencode($idMailer)
	 );
	
     foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	 rtrim($fields_string, '&');

	 
	 curl_setopt($ch,CURLOPT_URL, $link);
	 curl_setopt($ch,CURLOPT_POST, count($fields));
	 curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	 $result = curl_exec($ch);
?>