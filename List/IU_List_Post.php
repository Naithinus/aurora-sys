<?php
	 set_time_limit(0);
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 

    include('../Includes/bdd.php');
	
	
	
	  $name_List			= $_POST['txtNameList'];
	  $id_Country_List      = $_POST['radioCountry'];
	  $isActive_List        = isset($_POST['chkIsActive']) ? 1 : 0;
	  $id_Type_List			= $_POST['cmbTypeList'];
	  $id_ISP_List			= $_POST['cmbISP'];
	  $isOptIN_List			= isset($_POST['chkIsOptIN']) ? 1 : 0;
	  $fields_List			= '';
	  $id_List              = '';
	  
   if(isset($_POST['id_List']))
   {
	    if(isset($_POST["upload"])){
			$data = $_FILES['data_update']['name'];
			$extension = strtolower(pathinfo($data, PATHINFO_EXTENSION));
			$validExtensions = array('txt','csv');
			if(in_array($extension,$validExtensions))
				$res = move_uploaded_file($_FILES['data_update']['tmp_name'],'tmp/'.$data);
			if($res)
				echo 'uploaded<br>';
			else
				echo 'not uploaded<br>';
		  $new=array();
			$tmp 	= 	fopen("tmp/$data",'r+');
			while($line = fgets($tmp))
			{
				$line  = trim(strtolower($line));
				if($line!=""){
					$new[]=$line;
				}
				
				
			}
			//print_r($new);
			fclose($tmp);
			unlink('tmp/'.$data);
			$old=array();
			$query=$bdd->query("select * from email where id_List_Email='$_POST[id_List]'");
			 while($email=$query->fetch()){
				$old[]=$email["email_Email"];
			 }
			 $result=array_merge(array_diff($old,$new),array_diff($new,$old));
			 foreach($result as $r){
				$requete = $bdd->exec("insert ignore into email Values(NULL,'$r',NULL,NULL,NULL,NULL,'$_POST[id_List]')");
			 }
			
	   }else
	   {
			 $id_List = $_POST['id_List'];
			 
			 $requete = $bdd->prepare('update list set name_List=? , id_Country_List = ? , isActive_List = ? , id_Type_List = ? , id_ISP_List = ? , isOptIN_List = ? where id_List = ?');
			 $requete->execute(array($name_List,$id_Country_List,$isActive_List,$id_Type_List,$id_ISP_List,$isOptIN_List,$id_List));
			 
			if($_POST['dataAction']!='none')
			{		
				 
				 
				 if($isOptIN_List==0)
				 {
				    
					
					$requete = $bdd->prepare('select name_isp from isp where id_Isp = ?');
					  $requete->execute(array($id_ISP_List));
					  $row = $requete->fetch();
					  $nameList = $row['name_isp'];
					  
					  $requete = $bdd->prepare('select name_Country from country where id_Country = ?');
					  $requete->execute(array($id_Country_List));
					  $row = $requete->fetch();
					  $nameCountry = $row['name_Country'];
					  
					  
					  $data = $nameList.$nameCountry.'.txt';
					  
					if($_POST['dataAction']=='ecraser')
					{
					   // Delete Old Emails
					}
					notOptIn($data,$id_List);
					
				 }
				 else
				 {
		            if($_POST['dataAction']=='ecraser')
					{
					   // Delete Old Emails
					}
					OptIn('update');
				 }
	        }
	   }
	 }
	 
   
   
   //UPDATE
   else
   {
		  if($isOptIN_List==0)
		  {
			  $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
			  $requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,$id_Type_List,$id_ISP_List,$isOptIN_List,''));
			  $id_Inserted_List = $bdd->lastInsertId();
			  
			  $requete = $bdd->prepare('select name_isp from isp where id_Isp = ?');
			  $requete->execute(array($id_ISP_List));
			  $row = $requete->fetch();
			  $nameList = $row['name_isp'];
			  
			  $requete = $bdd->prepare('select name_Country from country where id_Country = ?');
			  $requete->execute(array($id_Country_List));
			  $row = $requete->fetch();
			  $nameCountry = $row['name_Country'];
			  
			  
			  $data = $nameList.$nameCountry.'.txt';
			  notOptIn($data,$id_Inserted_List);
			  
			  $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
			  $requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,2,$id_ISP_List,$isOptIN_List,''));
			  
			  $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
			  $requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,3,$id_ISP_List,$isOptIN_List,''));
			  
			  $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
			  $requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,4,$id_ISP_List,$isOptIN_List,''));
			  
			  $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
			  $requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,5,$id_ISP_List,$isOptIN_List,''));
			  
		  }
		  
		  else
		  {
			OptIn('insert');
		  }
	  
   }
   

   
   function notOptIn($fileName,$idList)
   {
          global $bdd;
          $file = fopen('tmp/'.$fileName,'r+');
		  while($line = fgets($file))
		  {
		    $requete = $bdd->prepare('insert into email Values(?,?,?,?,?,?,?)');
			$requete->execute(array(NULL,trim($line),NULL,NULL,NULL,NULL,$idList));
		  }
		  fclose($file);
		  unlink('tmp/'.$fileName);
   }
   
   
   function OptIn($type)
   {
        global $bdd,$name_List,$id_Country_List,$isActive_List,$id_Type_List,$id_ISP_List,$isOptIN_List,$id_List;
		//$data = $_FILES['data']['name'];
		$id_Inserted_List='';
		
        $delimiter = $_POST['cmbDelimiter'];
		$fieldsNames = $_POST['chkField'];
		$fieldsIndexes = $_POST['valuFields'];
		$fieldsToInsert='';
		$values        ='';
		foreach($fieldsNames as $fieldName)
		{
		  $fieldsToInsert.=$fieldName.',';
		  $values.='?,';
		}
		$fieldsToInsert = rtrim($fieldsToInsert,',');
		$values = rtrim($values,',');
		
		if($type=="insert")
		{
			$requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
			$requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,$id_Type_List,$id_ISP_List,$isOptIN_List,$fieldsToInsert));
			$id_Inserted_List = $bdd->lastInsertId();
			
			        $requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
					$requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,2,$id_ISP_List,$isOptIN_List,$fieldsToInsert));
					
					$requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
					$requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,3,$id_ISP_List,$isOptIN_List,$fieldsToInsert));
					
					$requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
					$requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,4,$id_ISP_List,$isOptIN_List,$fieldsToInsert));
					
					$requete = $bdd->prepare('insert into list Values(?,?,?,?,?,?,?,?)');
					$requete->execute(array(NULL,$name_List,$id_Country_List,$isActive_List,5,$id_ISP_List,$isOptIN_List,$fieldsToInsert));
					
					
		}
		else
		{
		   $id_Inserted_List = $id_List;
		}
		
        $fieldsToInsert.=',id_List_Email';		
		$values.=',?';  
		

		  $requete = $bdd->prepare('select name_isp from isp where id_Isp = ?');
		  $requete->execute(array($id_ISP_List));
		  $row = $requete->fetch();
		  $nameList = $row['name_isp'];
		  
		  $requete = $bdd->prepare('select name_Country from country where id_Country = ?');
		  $requete->execute(array($id_Country_List));
		  $row = $requete->fetch();
		  $nameCountry = $row['name_Country'];
		  
		  
		  $data = $nameList.$nameCountry.'.txt';
		  
		  
		  
		  $file = fopen('tmp/'.$data,'r+');
		  while($line = fgets($file))
		  {
		    $fieldsValues=array();
			$explode = explode($delimiter,$line);
			foreach($fieldsIndexes as $fieldIndex)
			{
			   if(strlen(trim($fieldIndex))!=0)
			   $fieldsValues[] = trim($explode[$fieldIndex-1]);
			}
			
			$fieldsValues[] = $id_Inserted_List;
			
			
				$requete = $bdd->prepare('insert into email ('.$fieldsToInsert.') Values('.$values.')');
				$requete->execute($fieldsValues);
		  }
		  fclose($file);
		  unlink('tmp/'.$data);      
				
   }
   
   
   header('location:ShowLists.php');
   
   
?>