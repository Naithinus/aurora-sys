<?php
	
	error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 'On');
	ini_set("memory_limit","2048M");
	ini_set("upload_max_filesize","3000M");
	ini_set("post_max_size","3000M");
	ini_set("max_execution_time","0");
	ini_set("max_input_time","0");
	
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
	
	
	include('../Includes/bdd.php');
	$uploaded 			= false;
	$creativeBdd		=	null;
	$extension			=	null;
	while(!$uploaded)
	{
		$creativeBdd = generateName();
		$requete = $bdd->prepare('select name_Creative from creatives where name_Creative = ?');
		$requete->execute(array($creativeBdd));
		$row = $requete->fetch();
		if(!$row)
		{
			$uploaded = true;
			$creative = $_FILES['creatives']['name'];
			$extension = strtolower(pathinfo($creative, PATHINFO_EXTENSION));
			if(in_array($extension,array('jpg','jpeg','png','gif')))
				move_uploaded_file($_FILES['creatives']['tmp_name'],'../../Creatives/'.$creativeBdd.'.'.$extension);
				
			$isUnsub = 0;
			if(isset($_POST['isUnsub']))
				$isUnsub = 1;
			else
				$isUnsub = 0;
			
			$idOffer	=	$_POST['cmbOffers'];
			$requete = $bdd->prepare('insert into creatives Values(?,?,?,?,?,?,?)');
			$requete->execute(array(NULL,$creativeBdd.'.'.$extension,$idOffer,$isUnsub,0,0,0));
		}
	}
	
	if(!is_null($creativeBdd))
	{
		$full_image_name	=	$creativeBdd.'.'.$extension;
		$result	=	'<h1>Your image was successfully uploaded as :  '.$full_image_name.'</h1>';
		$result.=	'<center><img src="../../Creatives/'.$full_image_name.'"/></center>';
		echo $result;
	}
	
	
?>
