<?php
	 date_default_timezone_set('UTC');
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
	
   
	$alias_Server			       		       = $_POST['txtAlias'];
	$id_Server_Provider_Server			       = $_POST['cmbServerProvider'];
	$username_Server			       		   = $_POST['txtUserName'];
	$password_Server			       		   = $_POST['txtPassword'];
	$saleDate_Server			       		   = $_POST['txtDateSale'];
	$expirationDate_Server			       	   = $_POST['txtDateExpiration'];
	$id_OS_Server			       		       = $_POST['cmbOS'];
	$isActive_Server			       		   = 0;
	$isp_Server			       		   		   = NULL;
	$mailerYes_Server			       		   = NULL;
	$mailerNo_Server			       		   = NULL;
	
	$ispStr									   = NULL;
	$mailerYesStr							   = NULL;
	$mailerNoStr							   = NULL;
	
	$date 					           = new DateTime($saleDate_Server);
	$saleDate_Server			       = $date->format('Y-m-d');
	
	$date 					           = new DateTime($expirationDate_Server);
	$expirationDate_Server 		       = $date->format('Y-m-d');
    
	$id_Server = '';
	
	if(isset($_POST['chkIsActive']))
		$isActive_Server=1;
	
   /*if(isset($_POST['chkIsActive']))
   {
      $isActive_Server=1;
	  
	  $isps = $_POST['chkISPS'];
	  foreach($isps as $isp)
	  {
		  
		 $requete = $bdd->prepare('insert into serverisp Values(NULL,?,?)');
		 $requete->execute(array());
	  }
	  
	  
	  
	  $isps = $_POST['chkISPS'];
	  $ispStr = '';
	  foreach($isps as $isp)
	   $ispStr.=$isp.',';
	  
	  $ispStr = rtrim($ispStr,',');
	  
	  if(isset($_POST['cmbMailersYes']))
	  {
		   $mailerYes_Server = $_POST['cmbMailersYes'];
		   $mailerYesStr = '';
		  foreach($mailerYes_Server as $mailerYes)
		   $mailerYesStr.=$mailerYes.',';
		  
		  $mailerYesStr = rtrim($mailerYesStr,',');
	  }
	  
	  
	  if(isset($_POST['cmbMailersNo']))
	  {
		  $mailerNo_Server = $_POST['cmbMailersNo'];
		   $mailerNoStr = '';
		  foreach($mailerNo_Server as $mailerNo)
		   $mailerNoStr.=$mailerNo.',';
		  
		  $mailerNoStr = rtrim($mailerNoStr,',');
	  }
	  
   }*/
   
   // UPDATE
   if(isset($_POST['id_Server']))
   {
      
       $id_Server			   = $_POST['id_Server'];
	   $requete = $bdd->prepare('update server set alias_Server = ? , id_Server_Provider_Server =? , username_Server = ? , password_Server = ? , saleDate_Server = ? , expirationDate_Server = ? , id_OS_Server = ? , isActive_Server = ? , isp_Server = ? , mailerYes_Server = ? , mailerNo_Server = ? where id_Server = ?');
	   $requete->execute(array($alias_Server,$id_Server_Provider_Server,$username_Server,$password_Server,$saleDate_Server,$expirationDate_Server,$id_OS_Server,$isActive_Server,$ispStr,$mailerYesStr,$mailerNoStr,$id_Server));
      
       
	   
	  if($isActive_Server == 1)
	  {
		  
		 $requete = $bdd->prepare('delete from serverisp where id_Server = ?');
	     $requete->execute(array($id_Server));
	  
	     $requete = $bdd->prepare('delete from servermailer where id_Server = ?');
	     $requete->execute(array($id_Server));
	   
		 $isps = $_POST['chkISPS'];
		 foreach($isps as $isp)
	     {
		  
		    $requete = $bdd->prepare('insert into serverisp Values(?,?,?)');
		    $requete->execute(array(NULL,$id_Server,$isp));
	     }  
		 
		 if(isset($_POST['cmbMailersYes']))
	     {
		   $mailerYes_Server = $_POST['cmbMailersYes'];
		   
			  foreach($mailerYes_Server as $mailerYes)
			  {
				  
				  $requete = $bdd->prepare('insert into servermailer Values(?,?,?,?)');
				  $requete->execute(array(NULL,$id_Server,$mailerYes,1));
			  }

	     }
	  
	  
	     if(isset($_POST['cmbMailersNo']))
	     {
			  $mailerNo_Server = $_POST['cmbMailersNo'];
			   
			  foreach($mailerNo_Server as $mailerNo)
			  {
				  $requete = $bdd->prepare('insert into servermailer Values(?,?,?,?)');
				  $requete->execute(array(NULL,$id_Server,$mailerNo,0));
			  }
	     }

	  }
	  
	  
	   header('location:IU_IP.php?id_Server='.$id_Server.'&update=yes');
   }
   
   
   // INSERT
   else
   {
	  $requete = $bdd->prepare('insert into server Values(?,?,?,?,?,?,?,?,?,?,?,?,?)');
	  $requete->execute(array(NULL,$alias_Server,$id_Server_Provider_Server,$username_Server,$password_Server,$saleDate_Server,$expirationDate_Server,$id_OS_Server,NULL,$isActive_Server,$ispStr,$mailerYesStr,$mailerNoStr));
      $id_Server = $bdd->lastInsertId();
	  if($isActive_Server == 1)
	  {
		  
		 $isps = $_POST['chkISPS'];
		 foreach($isps as $isp)
	     {
		  
		    $requete = $bdd->prepare('insert into serverisp Values(?,?,?)');
		    $requete->execute(array(NULL,$id_Server,$isp));
	     }  
		 
		 if(isset($_POST['cmbMailersYes']))
	     {
		   $mailerYes_Server = $_POST['cmbMailersYes'];
		   
			  foreach($mailerYes_Server as $mailerYes)
			  {
				  
				  $requete = $bdd->prepare('insert into servermailer Values(?,?,?,?)');
				  $requete->execute(array(NULL,$id_Server,$mailerYes,1));
			  }

	     }
	  
	  
	     if(isset($_POST['cmbMailersNo']))
	     {
			  $mailerNo_Server = $_POST['cmbMailersNo'];
			   
			  foreach($mailerNo_Server as $mailerNo)
			  {
				  $requete = $bdd->prepare('insert into servermailer Values(?,?,?,?)');
				  $requete->execute(array(NULL,$id_Server,$mailerNo,0));
			  }
	     }

	  }
	  
	$IPS =$_POST['txtIP'];
	$ips=explode("\n",$IPS);
	$Domains = $_POST['cmbDomain'];
	$domains= explode("\n",$Domains);
	$number= count($ips) / count($domains);
	$words = preg_split('//', 'abcdefghijklmnopqrstuvwxyz', -1);
	$x=0;
	foreach($domains as $d)
	{
		for($i=0;$i<$number;$i++){
			$main=true;
			shuffle($words);
			$rand="";
			for($z=0;$z<=10;$z++){
				$rand.=$words[$z];
			}
			$dom=$rand.".".$d;
			$newDomain=$bdd->exec("insert into domain values(NULL,'$dom',now(),now(),'3')") or die($bdd->errorInfo());
			$idDomain = $bdd->lastInsertId();
			$requete = $bdd->prepare('insert into ip Values(?,?,?,?)');
			$requete->execute(array(NULL,trim($ips[$x]),$idDomain,$id_Server));
			
			if($main==true)
			{
				$idIpMain = $bdd->lastInsertId();
				$requete = $bdd->prepare('update server set id_IP_Server = ? where id_Server = ?');
				$requete->execute(array($idIpMain,$id_Server));
				$main=false;
			}
			$x++;
			if(empty($ips[$x])){
				break;
			}
		}
	}
   }
   	  header('location:ShowServers.php');
   
?>