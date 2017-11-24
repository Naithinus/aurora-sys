<?php
	
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
   
    $id_Server = $_POST['id_Server'];
   
    // UPDATE
    if(isset($_POST['id_IPS']))
    {
	  
      $nbrIPS = sizeof($_POST['id_IPS']);
	  $ips = $_POST['txtIP'];
	  $domains = $_POST['cmbDomain'];
	  
		  for($i=0;$i<sizeof($_POST['id_IPS']);$i++)
		  {
			 $idIP = $_POST['id_IPS'][$i];
			 $ip = trim($ips[$i]);
			 $domain = $domains[$i];
			 
			 $requete = $bdd->prepare('update ip set IP_IP = ? , id_Domain_IP = ? where id_IP = ?');
			 $requete->execute(array($ip,$domain,$idIP));
		  } 
	  
		  $totalIPS = sizeof($_POST['txtIP']);
		  if($totalIPS > $nbrIPS)
		  {
			 for($i=$nbrIPS;$i<$totalIPS;$i++)
			 {
				 $ip = trim($ips[$i]);
				 $domain = $domains[$i];
				 
				 $requete = $bdd->prepare('insert into ip Values(?,?,?,?)');
				 $requete->execute(array(NULL,$ip,$domain,$id_Server));
			 }
		  }
   }
   
   //INSERT
   else
   {
	$ips =$_POST['txtIP'];
	$domains = $_POST['cmbDomain'];
	$words = preg_split('//', 'abcdefghijklmnopqrstuvwxyz', -1);
	for($i=0;$i<count($ips);$i++)
	{
		$x=trim($ips[$i]);
		$s=$bdd->query("select name_domain,id_Domain_Provider_Domain from domain where id_Domain='$domains[$i]'");
		$f=$s->fetch();
		$provider=$f[1];
		$d=explode(".",$f[0]);
		if(count($d)>2){
		  $dom=array_slice($d,-2,2);
		  $domain=$dom[0].".".$dom[1];
		}else{
		  $domain=$d[0].".".$d[1];
		}
		$t=trim($x);
		$IPS=explode("\n",$t);
		$main=true;
		foreach($IPS as $ip){
			shuffle($words);
			$rand="";
			for($z=0;$z<=10;$z++){
				$rand.=$words[$z];
			}
			$newDomain=$bdd->exec("insert into domain values(NULL,'$rand.$domain',now(),now(),$provider)") or dir($bdd->errorInfo());
			$requete = $bdd->prepare('insert into ip Values(?,?,?,?)');
			$idDomain = $bdd->lastInsertId();
			$requete->execute(array(NULL,$ip,$idDomain,$id_Server));
			if($main==true)
			{
				$idIpMain = $bdd->lastInsertId();
				$requete = $bdd->prepare('update server set id_IP_Server = ? where id_Server = ?');
				$requete->execute(array($idIpMain,$id_Server));
				$main=false;
			}
		}
	}
		
   }
   
   header('location:ShowServers.php');
   
   
	
   
	
?>