<?php
  
	 date_default_timezone_set('UTC');
	 
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

  include('../Includes/bdd.php');
  
  $id_Offer_Send				= $_POST['cmbOffers'];
  $id_ISP_Send 					= $_POST['cmbIsps'];
  $id_Employer_Send 			= $_SESSION['id_Employer'];
  $header_Send 				    = $_POST['txtHeader'];
  $body_Send 				    = $_POST['txtBody'];
  $emailTest_Send 			    = $_POST['txtEmailTest'];
  $returnPath_Send 				= $_POST['txtReturnPath'];
  $IPS_Send 					= NULL;
  $id_From_Send					= $_POST['cmbFroms'];
  $id_Subject_Send 				= $_POST['cmbSubjects'];
  $id_Creative_Send 			= $_POST['idCreative'];
  $isAR							= 0;
  $ARList						= '';
  $chkTrackSender				= isset($_POST['chkTrackSender']) ? 1 : 0;
  $chkSenderData				= isset($_POST['chkSenderData']) ? 1 : 0;
  $id_negative                  = $_POST['cmbNegative'];
  
  if(  ($_POST['cmbTarget'] == "1") || ($_POST['cmbTarget'] == "2")  )
  {
	  $extra = ($_POST['cmbTarget'] == "1") ?  "Vl-Openers" : "Vl-Clickers";
  }
				
  if(isset($_POST['chkAR']))
  {
    $isAR = 1;
	$ARList = $_POST['txtARList'];
  }
  
  $requete = $bdd->prepare('select name_Creative from creatives where id_Creative = ?');
  $requete->execute(array($id_Creative_Send));
  $row = $requete->fetch();
  $nameCreative = $row['name_Creative'];
  
  
  $requete = $bdd->prepare('select name_Creative from creatives where id_Offer_Creative = ? and isUnsub_Creativ = 1');
  $requete->execute(array($id_Offer_Send));
  $row = $requete->fetch();
  $nameCreativeUnsub = $row['name_Creative'];
  
  $body_Send =      preg_replace('#\[nameCreative\]#',$nameCreative,$body_Send);
  $body_Send =      preg_replace('#\[nameCreativeUnsub\]#',$nameCreativeUnsub,$body_Send);
  
  
  $requete = $bdd->prepare('insert into send Values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
  $requete->execute(array(NULL,$id_Offer_Send,$id_ISP_Send,$id_Employer_Send,$header_Send,$body_Send,$emailTest_Send,$returnPath_Send,$IPS_Send,$id_From_Send,$id_Subject_Send,$id_Creative_Send,0,0,0,0,0,$isAR,$ARList,$chkSenderData,$chkTrackSender,$id_negative,$extra,date("Y-m-d H:i:s")));
  $idSend = $bdd->lastInsertId();
	
  $requete = $bdd->prepare('insert into send_history Values(?,?,?,?,?)');
  $requete->execute(array($idSend,$header_Send,$body_Send,NULL,date("Y-m-d H:i:s")));
	  
$mailerLastName = $_SESSION['lastName_Employer']; 
$tableName = $mailerLastName.$idSend; 
  
    if(isset($_POST['chkSenderData']))
	{
			$sql = "CREATE TABLE $tableName (
			id_Email int,
			email_Email VARCHAR(250),
			id_List_Email int,
			id_Type_List int,
			sender varchar(250)
			)";

			$bdd->query($sql);
			
			 $requete = $bdd->prepare('select s.*,e.email_Email from email e, sender s where s.id_ISP = ? and s.id_Email = e.id_Email and e.id_Email not in(select id_Email from unsuboffer where id_Offer = ?)');
		     $requete->execute(array($id_ISP_Send,$id_Offer_Send));
			 
			while($row = $requete->fetch())
			{
					$subRequete = $bdd->prepare("insert into $tableName Values(?,?,?,?,?)");
					$subRequete->execute(array($row['id_Email'],$row['email_Email'],0,0,$row['sender']));
			}
				 
			
	}
	else
	{
			//VERTICAL DATA	
			
			if(  ($_POST['cmbTarget'] == "1") || ($_POST['cmbTarget'] == "2")  )
			{
					$idVerticalTarget = $_POST['cmbVerticalsTargets'];
					$typeVertical     = $_POST['cmbTarget'];
					
					$sql = "CREATE TABLE $tableName (
					id_Email int,
					email_Email VARCHAR(250),
					id_List_Email int,
					id_Type_List int,
					sender varchar(250)
					)";

					$bdd->query($sql);
					
					if($typeVertical == "1")
					{
						 $requete = $bdd->prepare('select e.id_Email,e.email_Email,e.id_List_Email,l.id_Type_List from email e,list l , vl_openers vlo where vlo.id_Email = e.id_Email and e.id_List_Email = l.id_List and vlo.id_Vertical = ? and vlo.id_Isp = ? and e.id_Email not in(select id_Email from unsuboffer where id_Offer = ?)');
						 $requete->execute(array($idVerticalTarget,$id_ISP_Send,$id_Offer_Send));
						 while($row = $requete->fetch())
						 {
							$subRequete = $bdd->prepare("insert into $tableName Values(?,?,?,?,?)");
							$subRequete->execute(array($row['id_Email'],$row['email_Email'],$row['id_List_Email'],$row['id_Type_List'],NULL));
						 }
					}
					
					if($typeVertical == "2")
					{
						 $requete = $bdd->prepare('select e.id_Email,e.email_Email,e.id_List_Email,l.id_Type_List from email e,list l , vl_clickers vlo where vlo.id_Email = e.id_Email and e.id_List_Email = l.id_List and vlo.id_Vertical = ? and vlo.id_Isp = ? and e.id_Email not in(select id_Email from unsuboffer where id_Offer = ?)');
						 $requete->execute(array($idVerticalTarget,$id_ISP_Send,$id_Offer_Send));
						 while($row = $requete->fetch())
						 {
							$subRequete = $bdd->prepare("insert into $tableName Values(?,?,?,?,?)");
							$subRequete->execute(array($row['id_Email'],$row['email_Email'],$row['id_List_Email'],$row['id_Type_List'],NULL));
						 }
					}
			}
				
				
			else
			{
					
						
						
						//WARM UP
						if(!isset($_POST['chkList']))
						{
							$requete = $bdd->prepare('select id_List from list where name_List = ?');
							$requete->execute(array($mailerLastName.'WU')); 
							$row = $requete->fetch();
							$idListWU =  $row['id_List'];
							$_POST['chkList'] = array($idListWU);
						}

						  foreach($_POST['chkList'] as $idList)
						  {
							 $requete = $bdd->prepare('insert into sendlist Values(?,?,?)');
							 $requete->execute(array(NULL,$idSend,$idList));
						  }


					$sql = "CREATE TABLE $tableName (
					id_Email int,
					email_Email VARCHAR(250),
					id_List_Email int,
					id_Type_List int,
					sender varchar(250)
					)";

					$bdd->query($sql);

					foreach($_POST['chkList'] as $idList)
					{
						 $requete = $bdd->prepare('select e.id_Email,e.email_Email,e.id_List_Email,l.id_Type_List from email e,list l where e.id_List_Email = ? and e.id_List_Email = l.id_List and id_Email not in(select id_Email from unsuboffer where id_Offer = ?)');
						 $requete->execute(array($idList,$id_Offer_Send));
						 while($row = $requete->fetch())
						 {
							$subRequete = $bdd->prepare("insert into $tableName Values(?,?,?,?,?)");
							$subRequete->execute(array($row['id_Email'],$row['email_Email'],$row['id_List_Email'],$row['id_Type_List'],NULL));
						 }
					}
			
			
			}

    }
  
header('location:ShowSends.php'); 

?>