<?php
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 
	
	
	if(!isset($_GET))
	{
		exit;
		header('location:../logout.php');
	}
	else
	{
		if(   (isset($_GET['id_sponsor'])) and ($_GET['id_sponsor']>0) )
		{
			include('../Includes/bdd.php');
			$id_sponsor					= $_GET['id_sponsor'];
			$tab_sponsor_credentials	=	get_sponsor_credentials_by_id($id_sponsor);
			if(count($tab_sponsor_credentials)==3)
			{
				$login		=	$tab_sponsor_credentials[0];
				$password	=	$tab_sponsor_credentials[1];
				$login_page	=	$tab_sponsor_credentials[2];
				
				/*
				$Curl_Session 			=	curl_init($login_page);
				curl_setopt ($Curl_Session, CURLOPT_POST, 1);
				curl_setopt ($Curl_Session, CURLOPT_POSTFIELDS, "u=$login&p=$password");
				curl_setopt ($Curl_Session, CURLOPT_FOLLOWLOCATION, 1);
				$result 				=  	curl_exec ($Curl_Session);
				curl_exec ($Curl_Session);
				curl_close ($Curl_Session);

				print $result;
				*/
			}
		}
	}
    
	function	get_sponsor_credentials_by_id($p_id_sponsor)
	{
		$result	=	array();
		
		if($p_id_sponsor>0)
		{
			include('../Includes/bdd.php');
			$requete = $bdd->prepare('select S.login_sponsor,S.password_sponsor,S.url_login_page_sponsor from sponsor S where S.id_Sponsor = ?');
			$requete->execute(array($p_id_sponsor));
			$row = $requete->fetch();
			if($row)
			{
				$login		=	$row['login_sponsor'];
				$password	=	$row['password_sponsor'];
				$login_page	=	$row['url_login_page_sponsor'];
				
				$result[0]	=	$login;
				$result[1]	=	$password;
				$result[2]	=	$login_page;
			}
		}
		return $result;
	}
	
?>
<div style="display:none;">
	<form id='frmGoToSponsorPage' action="<?php echo $login_page; ?>" method='POST'>
		<input type='hidden' id='username' name='u' value="<?php echo $login; ?>" />
		<input type='hidden' id='password' name='p' value="<?php echo $password; ?>" />
		<input class='btn btn-primary' id="btnGoToSponsorPage" type='submit' />
	</form>
	<script language="javascript">
        document.getElementById("btnGoToSponsorPage").click();
    </script>
</div>	