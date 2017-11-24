<?php

set_time_limit(0);
ini_set('display_errors', '1');

include_once('../Includes/sessionVerificationMailer.php'); 

include('../Includes/bdd.php');

if(isset($_POST['id_negative']))
{
	
	$id_negative = $_POST['id_negative'];
	$name_Negative = $_POST['txtNameNegative'];
	
	if(strlen($_FILES['fileNegative']['name'])!=0)
	{
		$requete = $bdd->prepare('select file_negative from negative where id_negative = ?');
		$requete->execute(array($id_negative));
		$row = $requete->fetch();
		$fileName = $row['file_negative'];
		
		$newNegative = file_get_contents($_FILES['fileNegative']['tmp_name']);
		file_put_contents('../../negatives/'.$fileName,$newNegative);

	}
	
	$requete = $bdd->prepare('update negative set name_negative = ? where id_negative = ?');
    $requete->execute(array($name_Negative,$id_negative));
	
	
}

else
{
	
	  $name_Negative = $_POST['txtNameNegative'];
	  $file = $_FILES['fileNegative']['name'];
	
	  $extension       = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	  $validExtensions = array('txt');
	  
	  $validName = false;
	  $fileName  = '';
	  
	  while(!$validName)
	  {
		  
		 $fileName = generateName().'.txt'; 
		 
		 $requete = $bdd->prepare('select file_negative from negative where file_negative = ?');
		 $requete->execute(array($fileName));
		 $row = $requete->fetch();
		 
		 if(!$row)
			 $validName = true;
	  }
	  
	  
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES['fileNegative']['tmp_name'],'../../negatives/'.$fileName);
	
	$idMailer = $_SESSION['id_Employer'];
	
	$requete = $bdd->prepare('insert into negative Values(?,?,?,?)');
	$requete->execute(array(NULL,$name_Negative,$fileName,$idMailer));
	
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

header('location:ShowNegatives.php');

?>