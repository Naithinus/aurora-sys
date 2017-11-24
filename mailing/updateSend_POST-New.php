<?php
	header("Access-Control-Allow-Origin: *"); 
	
	date_default_timezone_set('UTC');
	
	include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

      include('../Includes/bdd.php');
	  $id_Send 			= $_POST['id_Send'];
	  $header_Send 		= $_POST['txtHeader'];
	  $body_Send 		= $_POST['txtBody'];
	  $emailTest_Send   = $_POST['txtEmailTest'];
	  $returnPath_Send  = $_POST['txtReturnPath'];
	  //$IPS_Send 		= $_POST['txtIPS'];
	  
	  $id_From_Send     = $_POST['cmbFroms'];
	  $id_Subject_Send  = $_POST['cmbSubjects'];
	  $startFrom_Send   = $_POST['txtStartFrom'];
	  $id_negative      = isset($_POST['cmbNegative']) ? $_POST['cmbNegative'] : 0;
	  
	  $mailerLastName = $_SESSION['lastName_Employer']; 
	  
      $tableName      = $mailerLastName.$id_Send;
	  $requeteCount   = $bdd->query("select count(*) from $tableName");
	  $rowCount	      = $requeteCount->fetch();
	  
	  
	//IPs:
	$tab_ips_send 	= (isset($_POST['cmbIPs']))?$_POST['cmbIPs']:null;
	$strIPsSend		=	null;
	if(!is_null($tab_ips_send))
	{
		foreach($tab_ips_send as $address_ip):
			//$address_ip	=	get_address_ip_by_id($id_ip);
			$strIPsSend.=$address_ip.PHP_EOL;
		endforeach;
			$strIPsSend = trim($strIPsSend);
	}
	else
	{
		$strIPsSend		=	null;
	}
	  
	  
	  
	  if($rowCount[0] - $startFrom_Send <= 0)
	    $startFrom_Send = $rowCount[0];
		
	  if(isset($_POST['chkAR']))
	  {
	      $ARList  = $_POST['txtARList'];
		  $requete = $bdd->prepare('update send set header_Send = ? , body_Send = ? , emailTest_Send = ? , returnPath_Send = ? , IPS_Send = ? , id_From_Send = ? , id_Subject_Send = ? , startFrom_Send = ? , isAR = ? , ARList = ? where id_Send = ?');
		  $requete->execute(array($header_Send,$body_Send,$emailTest_Send,$returnPath_Send,$strIPsSend,$id_From_Send,$id_Subject_Send,$startFrom_Send,1,$ARList,$id_Send));
	  }
	  else
	  {
	      $requete = $bdd->prepare('update send set header_Send = ? , body_Send = ? , emailTest_Send = ? , returnPath_Send = ? , IPS_Send = ? , id_From_Send = ? , id_Subject_Send = ? , startFrom_Send = ? , isAR = ? , id_negative = ? where id_Send = ?');
		  $requete->execute(array($header_Send,$body_Send,$emailTest_Send,$returnPath_Send,$strIPsSend,$id_From_Send,$id_Subject_Send,$startFrom_Send,0,$id_negative,$id_Send));
	  }
	  
	  
	  $requete = $bdd->prepare('insert into send_history Values(?,?,?,?,?)');
	  $requete->execute(array($id_Send,$header_Send,$body_Send,NULL,date("Y-m-d H:i:s")));
	  
	  
	  header('location:ShowSends.php'); 
	  
	  
	  
	  
	  
	function	get_address_ip_by_id($p_id_ip)
	{
		$result	=	null;
		if(  (is_numeric($p_id_ip))  and  ($p_id_ip>0) )
		{
			include('../Includes/bdd.php');
			$requete = $bdd->prepare(
			'
				SELECT 	I.IP_IP
				FROM 	ip I
				WHERE 	I.id_IP		=		?
			');
			$requete->execute(array($p_id_ip));
			$row = $requete->fetch();
			
			if($row)
				$result	=	$row['IP_IP'];
		}
		return $result;
	}
?>	