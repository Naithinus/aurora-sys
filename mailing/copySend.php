<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

set_time_limit(0);
ini_set('display_errors', '1');
date_default_timezone_set('UTC');

include('../Includes/bdd.php');


function suppFunction($suppFile,$idSend)
{
	$cmd='/home/suppression/suppfile /home/exactarget/offers/suppression/'.$suppFile.' /home/exactarget/send/sendTMP/'.$idSend.' /home/exactarget/send/sendREAL/'.$idSend;
	exec($cmd,$output,$report);
	if($report=='0'){
		/*foreach ($output as $value) {
			echo $value.'<br>';
		}*/
	}else{
		echo "<div class='text-error'>Unknown command (".$cmd.")</div>";
	}
}
	
	
$idSend = $_POST['idSendCopy'];
$idISP  = $_POST['cmbIsps'];

$requete = $bdd->prepare('select * from send where id_Send = ?');
$requete->execute(array($idSend));
$row  = $requete->fetch();

$id_Offer_Send 				= $row['id_Offer_Send'];
$id_ISP_Send   				= $idISP;
$id_Employer_Send   		= $row['id_Employer_Send'];
$header_Send   				= $row['header_Send'];
$body_Send   				= $row['body_Send'];
$emailTest_Send   			= $row['emailTest_Send'];
$returnPath_Send   			= $row['returnPath_Send'];
$IPS_Send   				= $row['IPS_Send'];
$id_From_Send   			= $row['id_From_Send'];
$id_Subject_Send   			= $row['id_Subject_Send'];
$id_Creative_Send   		= $row['id_Creative_Send'];
$startFrom_Send   			= $row['startFrom_Send'];
$cptReceived   				= $row['cptReceived'];
$cptDelivered   			= $row['cptDelivered'];
$cptHardBounce   			= $row['cptHardBounce'];
$cptSoftBounce   			= $row['cptSoftBounce'];
$isAR   					= $row['isAR'];
$ARList  					= $row['ARList'];
$is_Sender   				= $row['is_Sender'];
$track_Sender   			= $row['track_Sender'];
$id_negative   			    = $row['id_negative'];
  
$requete = $bdd->prepare('insert into send Values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
$requete->execute(array(NULL,$id_Offer_Send,$id_ISP_Send,$id_Employer_Send,$header_Send,$body_Send,$emailTest_Send,$returnPath_Send,$IPS_Send,$id_From_Send,$id_Subject_Send,$id_Creative_Send,0,0,0,0,0,$isAR,$ARList,$is_Sender,$track_Sender,$id_negative,NULL,date("Y-m-d H:i:s")));
$idSend = $bdd->lastInsertId();

$mailerLastName = $_SESSION['lastName_Employer']; 
$tableName = $mailerLastName.$idSend; 

$requeteSupp = $bdd->prepare('select suppressionFile_Offer,TypeSuppressionFile_Offer from offer where id_offer = ?');
$requeteSupp->execute(array($id_Offer_Send));
$rowSupp      = $requeteSupp->fetch();
$suppFile     = $rowSupp['suppressionFile_Offer'];
$typeSuppFile = $rowSupp['TypeSuppressionFile_Offer'];
$fileOutPut = ''; 
$file     = fopen('/home/exactarget/send/sendTMP/'.$idSend,'a+');
$lineFile = '';


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
	
		$requete = $bdd->prepare('insert into sendlist Values(?,?,?)');
		$requete->execute(array(NULL,$idSend,$idList));
					 
		$requete = $bdd->prepare('select e.id_Email,lower(e.email_Email) as email_Email,e.id_List_Email,l.id_Type_List from email e,list l where e.id_List_Email = ? and e.id_List_Email = l.id_List and id_Email not in(select id_Email from unsuboffer where id_offer = ?)');
		$requete->execute(array($idList,$id_Offer_Send));
		

			 while($row = $requete->fetch())
			 {
			 
				    if($typeSuppFile != "text")
					{
					   $lineFile = $row['id_Email'].','.$row['email_Email'].','.md5($row['email_Email']).','.$row['id_List_Email'].','.$row['id_Type_List'];
					}
				    else
					{
					   $lineFile = $row['id_Email'].','.$row['email_Email'].','.$row['email_Email'].','.$row['id_List_Email'].','.$row['id_Type_List'];
					}
			 
					fputs($file,$lineFile.PHP_EOL);
					
					/*$subRequete = $bdd->prepare("insert into $tableName Values(?,?,?,?,?)");
					$subRequete->execute(array($row['id_Email'],$row['email_Email'],$row['id_List_Email'],$row['id_Type_List'],NULL));*/
					
			}
			
			
			
			
					
		
}


suppFunction($suppFile,$idSend);
$fileOutPut = fopen('/home/exactarget/send/sendREAL/'.$idSend,'a+');
			
while($lineResult = fgets($fileOutPut))
{
			    if( strlen($lineResult) != 0 )
				{
						$split = explode(',',$lineResult);
						$idEmailResult     = $split[0];
						$emailResult       = $split[1];
						$idListEmailResult = $split[3];
						$idTypeListResult  = $split[4];
						
						$subRequete = $bdd->prepare("insert into $tableName Values(?,?,?,?,?)");
						$subRequete->execute(array($idEmailResult,$emailResult,$idListEmailResult,$idTypeListResult,NULL));
				}
}


unlink('/home/exactarget/send/sendREAL/'.$idSend);
unlink('/home/exactarget/send/sendTMP/'.$idSend);

header('location:ShowSends.php');			
			
?>