<?php
	Include('../Includes/bdd.php');
	$requete = $bdd->query('select id_offer,suppressionFile_Offer,TypeSuppressionFile_Offer from offer');
	while($row = $requete->fetch())
	{
	     $idOffer         = $row['id_offer'];
	     $suppressionFile = $row['suppressionFile_Offer'];
		 $typeSuppression = $row['TypeSuppressionFile_Offer'];

		 if($typeSuppression =='text')
		 {
				$subRequete = $bdd->query('select id_Email , email_Email from email');
				while($subRow = $subRequete->fetch())
				{
					$file 	 = fopen('Suppression/'.$suppressionFile,'a+');
					$idEmail = $subRow['id_Email'];
					$email   = $subRow['email_Email'];
					while($emailSuppression = fgets($file))
					{
						if(trim($email) == trim($emailSuppression))
						{
						   $requeteSuppression = $bdd->prepare('insert into unsuboffer values(?,?)');
						   $requeteSuppression->execute(array($idEmail,$idOffer));
						   echo $email.'<br/>';
						   break;
						}
					}
					fclose($file);
				}
			
		 }
		 else
		 {
			$subRequete = $bdd->query('select id_Email , md5(email_Email) from email');
				while($subRow = $subRequete->fetch())
				{
					$file 	 = fopen('Suppression/'.$suppressionFile,'a+');
					$idEmail = $subRow['id_Email'];
					$email   = $subRow[1];
					while($emailSuppression = fgets($file))
					{
						if(trim($email) == trim($emailSuppression))
						{
						   $requeteSuppression = $bdd->prepare('insert into unsuboffer values(?,?)');
						   $requeteSuppression->execute(array($idEmail,$idOffer));
						   echo $email.'<br/>';
						   break;
						}
					}
					fclose($file);
				}
		 }
	}
?>