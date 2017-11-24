<?php
  include('../Includes/bdd.php');
  
  $year  = date('Y');
  $month = date('m');
  $day   = date('d');
  

  $result = shell_exec('sudo rm -f /var/log/pmta/oldBounced.csv');
  $result = shell_exec('sudo cp /var/log/pmta/acct-bounced-'.$year.'-'.$month.'-'.$day.'-0000.csv /var/log/pmta/oldBounced.csv');
  $result = shell_exec('sudo truncate /var/log/pmta/acct-bounced-'.$year.'-'.$month.'-'.$day.'-0000.csv --size 0');
  $result = shell_exec("sudo grep -Ea '5.1.1' /var/log/pmta/oldBounced.csv | cut -d , -f 5 | uniq");
  $mails = explode(PHP_EOL,$result);
  foreach($mails as $mail)
  {
	  $requete = $bdd->prepare('delete from email where email_Email = ?');
	  $requete->execute(array($mail));
	   echo $mail;
  }
  
?>