<?php



$fileName = $_POST['fileName'];

$result = file_get_contents($fileName);
echo $result;

?>