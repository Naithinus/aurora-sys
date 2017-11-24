<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<?php
header('Access-Control-Allow-Origin: *');

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

<?php
	if($id_sponsor!=12)
	{
?>		
		<div style='display:none;'>
			<form id='login' action="<?php echo $login_page; ?>" method='post'>
				<input type='hidden' id='uname' name='uname' value="<?php echo $login; ?>" />
				
				<input type='hidden' id='pword' name='pword' value="<?php echo $password; ?>" />
				
				<input id="btnGoToSponsorPage" type='submit' value="Login" />
				
				<input type="radio" value="agent" name="utype" id="utype_agent"  checked/>
								
				<input type="radio" value="advertiser" name="utype" id="utype_adv" />
				
				<input type="hidden" name="action" value="login"/>
			</form>
	
			<script language="javascript">
				// send form request
				var objRequest = new Request
				({
					url: 'http://affiliate.idrivecontrol.com/process.php',
					link: 'ignore',
					onSuccess: function(strResponse)
					{
						var objResponse = JSON.decode(strResponse);

						// successful login
						if(objResponse.success && objResponse['location'])
						{
							window.top.location.href = objResponse['location'];
							
						}
					}
				});

				$('#login').submit(function(objEvent)
				{
					var domLoginForm = $('#login');
					strUType = $( 'utype_agent' ).get( 'checked' ) ? 'agent' : 'advertiser';
					objRequest.send( domLoginForm.toQueryString() + '&utype=' + strUType );
				});
				
				
				
				document.getElementById("btnGoToSponsorPage").click();
				
			
				
				
			</script>	
		</div>
<?php
	}
	
	else
	{
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
<?php		
	}
	
//if($id_sponsor==2)
	//header('Location: http://affiliate.idrivecontrol.com');
?>


	
