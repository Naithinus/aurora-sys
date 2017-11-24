<?php
	
	ini_set('display_errors', '1');
	header("Access-Control-Allow-Origin: *"); 
	
	include('../Includes/bdd.php');
	
	$host			= $_SERVER['HTTP_HOST'];
    $pid 			= $_POST['pid'];
	
    shell_exec("sudo kill -9 $pid");
    
	$requete = $bdd->prepare('update sendprocess set pid=0 where pid=? and host = ?');
	$requete->execute(array($pid,$host));
	echo $host.':'.'Processe '.$pid.' Killed';
?>