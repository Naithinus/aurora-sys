<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

set_time_limit(0);
ini_set('display_errors', '1');
date_default_timezone_set('UTC');

include('../Includes/bdd.php');
$id_send=$_POST["id_Send"];
$deleteSend=$bdd->exec('Delete from send where id_send="'.$id_send.'"');

if ($deleteSend){
	echo "$id_send deleted";
} else {
	echo "Error while deleting $id_send";
}
?>