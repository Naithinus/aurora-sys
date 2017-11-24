<?php
    
	 set_time_limit(60);
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
	
	  $name_Offer			       = $_POST['txtNameOffer'];
	  $sid_Offer				   = $_POST['txtSIDoffer'];	
	  $id_Sponsor_Offer            = $_POST['cmbSponsors'];
	  $id_Country_Offer            = $_POST['chkCountrys'];
	  $isActive_Offer              = isset($_POST['chkIsActive']) ? 1 : 0;
	  $isSensitive_Offer           = isset($_POST['chkIsSensitive']) ? 1 : 0;
	  $link_Offer                  = $_POST['txtLink'];
	  $unsub_Offer                 = $_POST['txtUnsub'];
	  $notWorkingDays_Offer        = isset($_POST['chkNotWorkingDays']) ? $_POST['chkNotWorkingDays'] : '';
	  $froms                       = $_POST['txtFroms'];
	  $subjects                    = $_POST['txtSubjects'];
	  $id_Vertical_Offer           = $_POST['cmbVerticals'];
	  $typeSuppression			   = $_POST['radioTypeSuppression'];
	  ;
			
   if(isset($_POST['idOffer']))
   {
      $id_Offer = $_POST['idOffer'];
	  $countrys = '';
	  $notWorkingDays = '';
	  
	  foreach($id_Country_Offer as $idCountry)
	    $countrys.=$idCountry.',';
		
	  $countrys = rtrim($countrys,',');
	  
	  if($notWorkingDays_Offer!='')
	  {
		  foreach($notWorkingDays_Offer as $notWorkingDay)
			$notWorkingDays.=$notWorkingDay.',';
		  $notWorkingDays = rtrim($notWorkingDays,',');
	  }
	  
	  $requete = $bdd->prepare('update offer set name_Offer = ? , id_Sponsor_Offer = ? , id_Country_Offer = ? , isActive_Offer = ? , isSensitive_Offer = ? , link_Offer = ? , unsub_Offer = ? , notWorkingDays_Offer = ? , id_Vertical_Offer = ? , TypeSuppressionFile_Offer = ? , sid_Offer = ? where id_Offer = ?');
	  $requete->execute(array($name_Offer,$id_Sponsor_Offer,$countrys,$isActive_Offer,$isSensitive_Offer,$link_Offer,$unsub_Offer,$notWorkingDays,$id_Vertical_Offer,'md5',$sid_Offer,$id_Offer)) or die(print_r($bdd->errorInfo()));
		 
	  /*if(strlen($_FILES['suppression']['name'])!=0)
	  {
		 $suppression = $id_Offer.'-'.$_FILES['suppression']['name'];
		 
	     upload('suppression','Suppression',array('txt'),$suppression);
		 $requete = $bdd->prepare('update offer set name_Offer = ? , id_Sponsor_Offer = ? , id_Country_Offer = ? , isActive_Offer = ? , isSensitive_Offer = ? , link_Offer = ? , unsub_Offer = ? , suppressionFile_Offer = ? ,notWorkingDays_Offer = ? , id_Vertical_Offer = ? , TypeSuppressionFile_Offer = ? , sid_Offer = ? where id_Offer = ?');
	     $requete->execute(array($name_Offer,$id_Sponsor_Offer,$countrys,$isActive_Offer,$isSensitive_Offer,$link_Offer,$unsub_Offer,$suppression,$notWorkingDays,$id_Vertical_Offer,'md5',$sid_Offer,$id_Offer));
		 
		 
		 if(!isset($_POST['chkTreatSuppression']))
		 {
	  
				 if($typeSuppression =='text')
				 {
						$subRequete = $bdd->query('select id_Email , email_Email from email');
						while($subRow = $subRequete->fetch())
						{
							$fileSupp 	 = fopen('Suppression/'.$suppression,'a+');
							$idEmail = $subRow['id_Email'];
							$email   = $subRow['email_Email'];
							while($emailSuppression = fgets($fileSupp))
							{
								if(trim($email) == trim($emailSuppression))
								{
								   $requeteSuppression = $bdd->prepare('insert into unsuboffer values(?,?)');
								   $requeteSuppression->execute(array($idEmail,$id_Offer));
								   break;
								}
							}
							fclose($fileSupp);
						}
						
					
				 }
				 
				 else
				 {
					$subRequete = $bdd->query('select id_Email , md5(email_Email) from email');
						while($subRow = $subRequete->fetch())
						{
							$fileSupp 	 = fopen('Suppression/'.$suppression,'a+');
							$idEmail = $subRow['id_Email'];
							$email   = $subRow[1];
							while($emailSuppression = fgets($fileSupp))
							{
								if(trim($email) == trim($emailSuppression))
								{
								   $requeteSuppression = $bdd->prepare('insert into unsuboffer values(?,?)');
								   $requeteSuppression->execute(array($idEmail,$id_Offer));
								   break;
								}
							}
							
							fclose($fileSupp);
						}
						
						
				 }
		 }
		 
		 //unlink('Suppression/'.$suppression);
		 
		 if($typeSuppression =='text')
		 {
				$fileSupp 	 = fopen('Suppression/'.$suppression,'a+');
				$concat = '';
				while($line = fgets($fileSupp))
				{
					$concat.=md5(trim($line)).PHP_EOL;
				}
				
				file_put_contents('Suppression/'.$suppression,$concat);
		}	
			
		 
		 
	  }
	  
	  else
	  {
	     $requete = $bdd->prepare('update offer set name_Offer = ? , id_Sponsor_Offer = ? , id_Country_Offer = ? , isActive_Offer = ? , isSensitive_Offer = ? , link_Offer = ? , unsub_Offer = ? , notWorkingDays_Offer = ? , id_Vertical_Offer = ? , TypeSuppressionFile_Offer = ? , sid_Offer = ? where id_Offer = ?');
	     $requete->execute(array($name_Offer,$id_Sponsor_Offer,$countrys,$isActive_Offer,$isSensitive_Offer,$link_Offer,$unsub_Offer,$notWorkingDays,$id_Vertical_Offer,'md5',$sid_Offer,$id_Offer));
	  }*/
     
	 
	 //GESTION FROMS
	 
	 $requete = $bdd->prepare('select id_From , text_From from froms where id_Offer_From = ?')  or die(print_r($bdd->errorInfo()));
	 $requete->execute(array($id_Offer));
	 $fromsBdd = $requete->fetchAll();
	 $fromsNew = explode(PHP_EOL,$froms);
	 for($i=0;$i<count($fromsBdd);$i++)
	 {
	     if(!in_array($fromsBdd[$i]['text_From'],$fromsNew))
		 {
		    $idFrom = $fromsBdd[$i]['id_From'];
		    $Subrequete = $bdd->prepare('delete from froms where id_From = ?')  or die(print_r($bdd->errorInfo()));
			$Subrequete->execute(array($idFrom));
		 }
	 }
	 
	 foreach($fromsNew as $newFrom)
	 {
	    $requete = $bdd->prepare('select id_From from froms where text_From = ? and id_Offer_From = ?');
		$requete->execute(array($newFrom,$id_Offer))  or die(print_r($bdd->errorInfo()));
		if(!$requete->fetch())
		{
		  $requete = $bdd->prepare('insert into froms Values(?,?,?,?,?,?,?)');
		  $requete->execute(array(NULL,trim($newFrom),$id_Offer,0,0,0,0));
		}
	 }
	 
	 
	 
	 
	 
	 //GESTION Subjects
	 
	 $requete = $bdd->prepare('select id_Subject , text_Subject from subjects where id_Offer_Subject = ?') ;
	 $requete->execute(array($id_Offer))  or die(print_r($bdd->errorInfo()));
	 $subjectsBdd = $requete->fetchAll();
	 $subjectsNew = explode(PHP_EOL,$subjects);
	 for($i=0;$i<count($subjectsBdd);$i++)
	 {
	     if(!in_array($subjectsBdd[$i]['text_Subject'],$subjectsNew))
		 {
		    $idFrom = $subjectsBdd[$i]['id_Subject'];
		    $Subrequete = $bdd->prepare('delete from subjects where id_Subject = ?')  or die(print_r($bdd->errorInfo()));
			$Subrequete->execute(array($idFrom));
		 }
	 }
	 
	 foreach($subjectsNew as $newSubject)
	 {
	    $requete = $bdd->prepare('select id_Subject from subjects where text_Subject = ? and id_Offer_Subject = ?');
		$requete->execute(array($newSubject,$id_Offer)) or die(print_r($bdd->errorInfo()));
		if(!$requete->fetch())
		{
		  $requete = $bdd->prepare('insert into subjects Values(?,?,?,?,?,?,?)');
		  $requete->execute(array(NULL,trim($newSubject),$id_Offer,0,0,0,0)) or die(print_r($bdd->errorInfo()));
		}
	 }
	 
	 
	 
	  if(strlen($_FILES['creatives']['name'][0])!=0)
	  {
			$cpt = count($_FILES['creatives']['name']);
			for($i=0;$i<$cpt;$i++)
			{
				 $uploaded = false;
				 
				 while(!$uploaded)
				 {
				 
					 $creativeBdd = generateName();
					 $requete = $bdd->prepare('select name_Creative from creatives where name_Creative = ?') ;
					 $requete->execute(array($creativeBdd)) or die(print_r($bdd->errorInfo()));
					 $row = $requete->fetch();
					 if(!$row)
					 {
						 $uploaded = true;
						 $creative = $_FILES['creatives']['name'][$i];
						 $extension = strtolower(pathinfo($creative, PATHINFO_EXTENSION));
						 if(in_array($extension,array('jpg','jpeg','png','gif')))
							move_uploaded_file($_FILES['creatives']['tmp_name'][$i],'../../Creatives/'.$creativeBdd.'.'.$extension);
						
						$isUnsub = 0;
						if(isset($_POST['isUnsub']))
						{
							if(in_array($i+1,$_POST['isUnsub']))
							  $isUnsub = 1;
						}
						$requete = $bdd->prepare('insert into creatives Values(?,?,?,?,?,?,?)');
						$requete->execute(array(NULL,$creativeBdd.'.'.$extension,$id_Offer,$isUnsub,0,0,0));
					 }
				}
			}
	  }
	  
	 
	 
	 
	 
	 
	 
   }
   
   else
   {
	  //$suppression = $_FILES['suppression']['name']; 
	  $suppression = 'none.txt';
	  
      $countrys = '';
	  $notWorkingDays = '';
	  
	  foreach($id_Country_Offer as $idCountry)
	    $countrys.=$idCountry.',';
	  $countrys = rtrim($countrys,',');
	  
	  if($notWorkingDays_Offer!='')
	  {
		  foreach($notWorkingDays_Offer as $notWorkingDay)
			$notWorkingDays.=$notWorkingDay.',';
		  $notWorkingDays = rtrim($notWorkingDays,',');
	  }
	  
	    
			
	  $requete = $bdd->prepare('insert into offer Values(?,?,?,?,?,?,?,?,?,?,?,?,?)');
	  $requete->execute(array(NULL,$name_Offer,$id_Sponsor_Offer,$countrys,$isActive_Offer,$isSensitive_Offer,$link_Offer,$unsub_Offer,$suppression,$notWorkingDays,$id_Vertical_Offer,'md5',$sid_Offer)) or die(print_r($bdd->errorInfo()));
	  $idOffer = $bdd->lastInsertId();
	    
			
	  /*$requete = $bdd->prepare('update offer set suppressionFile_Offer = ? where id_offer = ?');
	  $requete->execute(array($idOffer.'-'.$suppression,$idOffer));
	  
	  upload('suppression','Suppression',array('txt'),$idOffer.'-'.$suppression);
	  
	  $suppression = $idOffer.'-'.$suppression;*/
	  
	  $fromsExplode = explode(PHP_EOL,$froms);
	  foreach($fromsExplode as $from)
	  {
	     $requete = $bdd->prepare('insert into froms Values(?,?,?,?,?,?,?)');
		 $requete->execute(array(NULL,trim($from),$idOffer,0,0,0,0));
	  }
	  
	  $subjectsExplode = explode(PHP_EOL,$subjects);
	  foreach($subjectsExplode as $subject)
	  {
	     $requete = $bdd->prepare('insert into subjects Values(?,?,?,?,?,?,?)');
		 $requete->execute(array(NULL,trim($subject),$idOffer,0,0,0,0));
	  }
	    
			
	  $cpt = count($_FILES['creatives']['name']);
	  for($i=0;$i<$cpt;$i++)
	  {
			 $uploaded = false;
			 
			 while(!$uploaded)
			 {
			 
				 $creativeBdd = generateName();
				 $requete = $bdd->prepare('select name_Creative from creatives where name_Creative = ?');
				 $requete->execute(array($creativeBdd));
				 $row = $requete->fetch();
				 if(!$row)
			     {
				     $uploaded = true;
					 $creative = $_FILES['creatives']['name'][$i];
					 $extension = strtolower(pathinfo($creative, PATHINFO_EXTENSION));
					 if(in_array($extension,array('jpg','jpeg','png','gif')))
						move_uploaded_file($_FILES['creatives']['tmp_name'][$i],'../../Creatives/'.$creativeBdd.'.'.$extension);
					
					$isUnsub = 0;
					if(isset($_POST['isUnsub']))
					{
						if(in_array($i+1,$_POST['isUnsub']))
						  $isUnsub = 1;
					}
					$requete = $bdd->prepare('insert into creatives Values(?,?,?,?,?,?,?)');
					$requete->execute(array(NULL,$creativeBdd.'.'.$extension,$idOffer,$isUnsub,0,0,0));
				 }
		    }
	  }
	  
	  
	  /*if(!isset($_POST['chkTreatSuppression']))
	  {
			  if($typeSuppression =='text')
				 {
						$subRequete = $bdd->query('select id_Email , email_Email from email');
						while($subRow = $subRequete->fetch())
						{
							$fileSupp 	 = fopen('Suppression/'.$suppression,'a+');
							$idEmail = $subRow['id_Email'];
							$email   = $subRow['email_Email'];
							while($emailSuppression = fgets($fileSupp))
							{
								if(trim($email) == trim($emailSuppression))
								{
								   $requeteSuppression = $bdd->prepare('insert into unsuboffer values(?,?)');
								   $requeteSuppression->execute(array($idEmail,$idOffer));
								   break;
								}
							}
							fclose($fileSupp);
						}
						
					
				 }
				 
				 else
				 {
					$subRequete = $bdd->query('select id_Email , md5(email_Email) from email');
						while($subRow = $subRequete->fetch())
						{
							$fileSupp 	 = fopen('Suppression/'.$suppression,'a+');
							$idEmail = $subRow['id_Email'];
							$email   = $subRow[1];
							while($emailSuppression = fgets($fileSupp))
							{
								if(trim($email) == trim($emailSuppression))
								{
								   $requeteSuppression = $bdd->prepare('insert into unsuboffer values(?,?)');
								   $requeteSuppression->execute(array($idEmail,$idOffer));
								   break;
								}
							}
							
							fclose($fileSupp);
						}
						
						
				 }
		 
		 }
		 
		 //unlink('Suppression/'.$suppression);
			if($typeSuppression =='text')
			{
				$fileSupp 	 = fopen('Suppression/'.$suppression,'a+');
				$concat = '';
				while($line = fgets($fileSupp))
				{
					$concat.=md5(trim($line)).PHP_EOL;
				}
				
				file_put_contents('Suppression/'.$suppression,$concat);
			}*/
		 
		 
   }
   
   function upload($name,$path,$validExtensions,$fileName)
   {
      //$file = $_FILES[$name]['name'];
	  $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES[$name]['tmp_name'],$path.'/'.$fileName);
   }
   
   function generateName()
   {
      $chaine = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890';
	  $concat = '';
	  for($j=0;$j<5;$j++)
	  {
		    $rand = rand(0,61);
			$concat.=$chaine[$rand];
	  }
	  return $concat;
   }
   
   header('location:ShowOffers.php');
   
   
?>