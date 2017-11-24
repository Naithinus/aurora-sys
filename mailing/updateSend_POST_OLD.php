<?php
	 
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

      include('../Includes/bdd.php');
	  $id_Send 			= $_POST['id_Send'];
	  $header_Send 		= $_POST['txtHeader'];
	  $body_Send 		= $_POST['txtBody'];
	  $emailTest_Send   = $_POST['txtEmailTest'];
	  $returnPath_Send  = $_POST['txtReturnPath'];
	  $IPS_Send 		= $_POST['txtIPS'];
	  $id_From_Send     = $_POST['cmbFroms'];
	  $id_Subject_Send  = $_POST['cmbSubjects'];
	  $startFrom_Send   = $_POST['txtStartFrom'];
	  $id_negative      = isset($_POST['cmbNegative']) ? $_POST['cmbNegative'] : 0;
	  
	  $mailerLastName = $_SESSION['lastName_Employer']; 
	  
      $tableName      = $mailerLastName.$id_Send;
	  $requeteCount   = $bdd->query("select count(*) from $tableName");
	  $rowCount	      = $requeteCount->fetch();
	  
	  if($rowCount[0] - $startFrom_Send <= 0)
	    $startFrom_Send = $rowCount[0];
		
	  if(isset($_POST['chkAR']))
	  {
	      $ARList  = $_POST['txtARList'];
		  $requete = $bdd->prepare('update send set header_Send = ? , body_Send = ? , emailTest_Send = ? , returnPath_Send = ? , IPS_Send = ? , id_From_Send = ? , id_Subject_Send = ? , startFrom_Send = ? , isAR = ? , ARList = ? where id_Send = ?');
		  $requete->execute(array($header_Send,$body_Send,$emailTest_Send,$returnPath_Send,$IPS_Send,$id_From_Send,$id_Subject_Send,$startFrom_Send,1,$ARList,$id_Send));
	  }
	  else
	  {
	      $requete = $bdd->prepare('update send set header_Send = ? , body_Send = ? , emailTest_Send = ? , returnPath_Send = ? , IPS_Send = ? , id_From_Send = ? , id_Subject_Send = ? , startFrom_Send = ? , isAR = ? , id_negative = ? where id_Send = ?');
		  $requete->execute(array($header_Send,$body_Send,$emailTest_Send,$returnPath_Send,$IPS_Send,$id_From_Send,$id_Subject_Send,$startFrom_Send,0,$id_negative,$id_Send));
	  }
	  
	  
	  $requete = $bdd->prepare('insert into send_history Values(?,?,?)');
	  $requete->execute(array($id_Send,$header_Send,$body_Send));
	  
	  
	  header('location:ShowSends.php'); 
?>	