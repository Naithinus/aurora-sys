<?php
date_default_timezone_set('UTC');
try {
	$bdd= new PDO('mysql:host=localhost;dbname=aurora','root','');
}catch (Exception $e)
{
	echo "<pre>",print_r($e),"</pre>";
}
?>
