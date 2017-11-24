<?php
	
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 

    include('../Includes/bdd.php');
	
	$name_Sponsor			= 	$_POST['txtNameSponsor'];
	$isActive_Sponsor       = 	isset($_POST['chkIsActive']) ? 1 : 0;
	$login_sponsor			=	$_POST['txtLoginSponsor'];
	$password_sponsor		=	$_POST['txtPasswordSponsor'];
	$url_login_page_sponsor	=	$_POST['txtUrlSponsor'];
	$id_plateform_sponsor	=   $_POST['cmbPlateformSponsor'];
	$api_access_key_sponsor	=	$_POST['txtApiAccessKey'];
	$api_host_url			=	$_POST['txtApiHostURL'];
	$affiliate_id_sponsor	=	$_POST['txtAffiliateIdSponsor'];
	
	$logo                   = '';
	$logoBdd				= ''; 

	$linkType=$_POST["linkType"];
     // UPDATE	 
    if(isset($_POST['idSponsor']))
    {
      $id_Sponsor = $_POST['idSponsor'];
	  if(strlen($_FILES['logo']['name'])!=0)
	  {
	     $requete = $bdd->prepare('select logo_Sponsor from sponsor where id_Sponsor = ?');
		 $requete->execute(array($id_Sponsor));
		 $row = $requete->fetch();
		 $oldLogo = $row['logo_Sponsor'];
		 
	     unlink('Images/'.$oldLogo);
	     upload();
		 $requete = $bdd->prepare('update sponsor set name_Sponsor = ? , logo_Sponsor = ? , isActive_Sponsor = ? , login_sponsor = ? , password_sponsor = ?, url_login_page_sponsor = ? , id_plateform_sponsor = ? , api_access_key_sponsor = ? , api_host_url = ? , affiliate_id_sponsor = ?, link_type = ? where id_Sponsor = ?');
		 $requete->execute(array($name_Sponsor,$logoBdd,$isActive_Sponsor,$login_sponsor,$password_sponsor,$url_login_page_sponsor,$id_plateform_sponsor,$api_access_key_sponsor,$api_host_url,$affiliate_id_sponsor,$linkType,$id_Sponsor));
	  }
	  else
	  {
	     $requete = $bdd->prepare('update sponsor set name_Sponsor = ? , isActive_Sponsor = ? , login_sponsor = ? , password_sponsor = ?, url_login_page_sponsor = ? , id_plateform_sponsor = ? , api_access_key_sponsor = ?, api_host_url = ? ,affiliate_id_sponsor = ?,link_type = ?  where id_Sponsor = ?');
		 $requete->execute(array($name_Sponsor,$isActive_Sponsor,$login_sponsor,$password_sponsor,$url_login_page_sponsor,$id_plateform_sponsor,$api_access_key_sponsor,$api_host_url,$affiliate_id_sponsor,$linkType,$id_Sponsor));
	  }
     
    }
   
    // INSERT
    else
    {
      upload();
	  $logo = $_FILES['logo']['name']; 
      
	  $requete = $bdd->prepare('insert into sponsor Values(?,?,?,?,?,?,?,?,?,?,?,?)');
	  $requete->execute(array(NULL,$name_Sponsor,$logoBdd,$isActive_Sponsor,$login_sponsor,$password_sponsor,$url_login_page_sponsor,$id_plateform_sponsor,$api_access_key_sponsor,$api_host_url,$affiliate_id_sponsor,$linkType));
	  
    }
   
    function upload()
    {
	  global $logoBdd,$name_Sponsor;
      $logo = $_FILES['logo']['name'];
	  $extension = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
	  $validExtensions = array('jpg','png','jpeg','gif');
	  
	  $logoBdd = $name_Sponsor.'.'.$extension;
	  
	  if(in_array($extension,$validExtensions))
	    move_uploaded_file($_FILES['logo']['tmp_name'],'Images/'.$logoBdd);
    }
   
   header('location:ShowSponsors.php');
   
   
?>