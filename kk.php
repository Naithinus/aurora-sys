<?php

ini_set('display_errors', '1');
$imap 				= 	imap_open("{imap-mail.outlook.com:993/imap/ssl}inbox", "cloheboyer@hotmail.com", "MALIKA44");
if($imap)
	echo "dkhal";	
else
	echo "walo";

$n_msgs 			= 	imap_num_msg($imap);

echo $n_msgs;

?>