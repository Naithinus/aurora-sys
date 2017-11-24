<?php
	
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 date_default_timezone_set('UTC');

    include('../Includes/bdd.php');
	
   
	$firstName_Employer 	   = $_POST['txtFirstName'];
	$lastName_Employer 		   = $_POST['txtLastName'];
	
	$date 					   = new DateTime($_POST['txtDOB']);
	$dob_Employer			   = $date->format('Y-m-d');
	
	$date 					   = new DateTime($_POST['txtDateIn']);
	$dateIn_Employer 		   = $date->format('Y-m-d');
	
	$date 					   = new DateTime($_POST['txtDateOut']);
	$dateOut_Employer          = $date->format('Y-m-d');
	
	$id_Isp_Employer           = NULL;

	$salaire_Employer 		   = $_POST['txtSalaire'];
	$id_type_Employer_Employer = $_POST['cmbTypeEmployer'];
	$password_Employer         = $_POST['txtPassword'];

   $dob = new DateTime('2000-01-01');
   $dob->format('Y-m-d');
   
    $requete = $bdd->prepare('select name_type_Employer from type_employer where id_type_Employer = ?');
	$requete->execute(array($id_type_Employer_Employer));
	$row = $requete->fetch();
	
	if($row["name_type_Employer"] == "Mailer" || $row["name_type_Employer"]=="Team Leader")
	   $id_Isp_Employer = $_POST['cmbISP'];
   
   
   // UPDATE
   
   if(isset($_POST['idEmployer']))
   {
      
       $id_Employer			   = $_POST['idEmployer'];
	   $requete = $bdd->prepare('update employer set firstName_Employer = ? , lastName_Employer = ? , dob_Employer = ? , dateIn_Employer = ? , dateOut_Employer = ? , salaire_Employer = ? , id_type_Employer_Employer = ? , password_Employer = ? , id_Isp_Employer = ?  where  id_Employer = ?');
	   $requete->execute(array($firstName_Employer,$lastName_Employer,$dob_Employer,$dateIn_Employer,$dateOut_Employer,$salaire_Employer,$id_type_Employer_Employer,$password_Employer,$id_Isp_Employer,$id_Employer));
       
	   $requete = $bdd->prepare('select id_Experience , id_Type_Employer_Experience,id_Isp_Experience from experience where id_Experience = (select max(id_Experience) from experience where id_Employer_Experience = ?)');
	   $requete->execute(array($id_Employer));
	   $row = $requete->fetch();
	   
	   $idExperience = $row['id_Experience'];
	   $oldType = $row['id_Type_Employer_Experience'];
	   $oldIsp  = $row['id_Isp_Experience'];
	   
	   if($oldType != $id_type_Employer_Employer || $oldIsp != $id_Isp_Employer)
	   {
	      $requete = $bdd->prepare('update experience set dateEnd_Experience=? where id_Experience = ?');
		  $requete->execute(array(date('Y-m-d'),$idExperience));
		  
		  $requete = $bdd->prepare('insert into experience Values(?,?,?,?,?,?)');
	      $requete->execute(array(NULL,$id_Employer,date('Y-m-d'),NULL,$id_type_Employer_Employer,$id_Isp_Employer));
	  
		  
	   }
	   
   }
   
   
    // INSERT
   else
   {
     
	  $requete = $bdd->prepare('insert into employer Values(?,?,?,?,?,?,?,?,?,?)');
	  $requete->execute(array(NULL,$firstName_Employer,$lastName_Employer,$dob_Employer,$dateIn_Employer,$dateOut_Employer,$salaire_Employer,$id_type_Employer_Employer,$password_Employer,$id_Isp_Employer));
      
	  $id_Employer = $bdd->lastInsertId();
	  
	  $requete = $bdd->prepare('insert into experience Values(?,?,?,?,?,?)');
	  $requete->execute(array(NULL,$id_Employer,date('Y-m-d'),NULL,$id_type_Employer_Employer,$id_Isp_Employer));
	  
	  $requete = $bdd->prepare('select id_Isp from isp where name_isp = ?');
	  $requete->execute(array('warm up'));
	  $row = $requete->fetch();
	  $idISP = $row['id_Isp'];
	  
	  $nameList = $lastName_Employer.'WU';
	  $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
	  $requete->execute(array(NULL,$nameList,1,1,1,$idISP,0,0));
	  
   
	// Mailer Server :
	$tab_servers	=	array();
	$requete = $bdd->prepare('select id_Server from server where isActive_Server = ?');
	$requete->execute(array(1));
	while($row = $requete->fetch())
	{
		array_push($tab_servers,$row['id_Server']);
	}
	
	if(count($tab_servers)>0)
	{
		foreach($tab_servers as $current_id_server):
			$req2 = $bdd->prepare('insert into servermailer Values(?,?,?,?)');
			$req2->execute(array(NULL,$current_id_server,$id_Employer,0));
		endforeach;
	}
	
   
   }
   
   header('location:ShowEmployers.php');
?>