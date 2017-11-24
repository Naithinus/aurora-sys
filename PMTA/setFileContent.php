<?php



$fileName = $_POST['file'];
$data 	  = $_POST['data'];

$result = file_put_contents($fileName,$data);

shell_exec('sudo service pmta reload');

echo 'Edit Done';

?>