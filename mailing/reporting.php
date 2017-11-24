<?php
	set_time_limit(0);
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl 					= 	"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 
	
	
	function	get_tolal_seed_current_mailer()
	{
		include('../Includes/bdd.php');
		$mailerLastName = $_SESSION['lastName_Employer'];
		$nameList 		= $mailerLastName.'WU';
		$requete 		= $bdd->prepare
		("
			SELECT	count(*) as total
			FROM	email
			WHERE	id_List_Email	=	(SELECT L.id_List FROM list L WHERE L.name_List = ?)	
		");
		$requete->execute(array($nameList));
		$row	=	$requete->fetch();
		
		
		return $row['total'];
	}
	
	
	$txtNumberEmailsToReport	=	(isset($_POST['txtNumberEmailsToReport']))?$_POST['txtNumberEmailsToReport']:get_tolal_seed_current_mailer();
	$cmbISP						=	(isset($_POST['cmbISP']))?$_POST['cmbISP']:'hotmail';
	$chkReadSpamFolder			=	(isset($_POST['chkReadSpamFolder']))?'checked':'';
	$chkReadInboxFolder			=	(isset($_POST['chkReadInboxFolder']))?'checked':'';
	$chkMoveFromSpamToInbox		=	(isset($_POST['chkMoveFromSpamToInbox']))?'checked':'';
	$chkMarkAsFlagged			=	(isset($_POST['chkMarkAsFlagged']))?'checked':'';
	
	?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Reporting Tool</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_checkboxes_radios.js"></script>

	
	
	
	
	
</head>

<body>
	<?php include('../Includes/navbar.php');?>
    


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
		<?php Include('../Includes/sidebar.php');?>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">
				<!-- Page header -->
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reporting</span> Tool</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active">Reporting Tool</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->

				<div class="panel panel-flat">
					    <div class="panel-body">
					
			
					<form class="form-horizontal" id='frmReporting' name='frmReporting' method='POST'>
					    <fieldset class="content-group">
									<legend class="text-bold">Reporting Tool</legend>

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">ISP</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbISP" id="cmbISP" class="select-clear" data-placeholder="Select ISP">
                                                <option value="<?php echo $cmbISP; ?>"><?php echo strtoupper($cmbISP); ?></option>
				                            </select>
			                            </div>
			                        </div>
									
										
									<div class="form-group">
										<label class="control-label col-lg-2">Number of seeds to report</label>
										<div class="col-lg-10">
											<input type="number" name="txtNumberEmailsToReport" id="txtNumberEmailsToReport" class="form-control" value="<?php echo $txtNumberEmailsToReport; ?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Mark as Read (from Spam folder)</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkReadSpamFolder" id="chkReadSpamFolder" <?php echo $chkReadSpamFolder; ?> />
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Mark as Read (from Inbox folder)</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkReadInboxFolder" id="chkReadInboxFolder" <?php echo $chkReadInboxFolder; ?> />
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Move from Spam -> Inbox</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkMoveFromSpamToInbox" id="chkMoveFromSpamToInbox" <?php echo $chkMoveFromSpamToInbox; ?> />
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-2">Mark as Flagged</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkMarkAsFlagged" id="chkMarkAsFlagged" <?php echo $chkMarkAsFlagged; ?> />
										</div>
									</div>
						
						</fieldset>
						<center>
							<input type="submit" id="btnGO" class="btn btn-primary" value="Start Reporting" />
						</center>
					</form>		
					
					
				</div>
			</div>		

				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body" id="pnl">
<?php
/* ob_end_flush(); 
flush(); 
ob_start(); */
$currentISP					=	(isset($_POST['cmbISP']))?$_POST['cmbISP']:null; 
$txtNumberEmailsToReport	=	(isset($_POST['txtNumberEmailsToReport']))?$_POST['txtNumberEmailsToReport']:0;  // a la place de 0 il faut appler la fonction qui calcul le total des emails de warmup de l'utilsateur connecté

$chkReadSpamFolder			=	(isset($_POST['chkReadSpamFolder']))?1:0; 
$chkReadInboxFolder			=	(isset($_POST['chkReadInboxFolder']))?1:0; 
$chkMoveFromSpamToInbox		=	(isset($_POST['chkMoveFromSpamToInbox']))?1:0; 
$chkMarkAsFlagged			=	(isset($_POST['chkMarkAsFlagged']))?1:0; 

if($currentISP=='hotmail')
{
if(  (is_numeric($txtNumberEmailsToReport))  and  ($txtNumberEmailsToReport>0))
	{
		include('../Includes/bdd.php');
		$mailerLastName = $_SESSION['lastName_Employer'];
		$nameList 		= $mailerLastName.'WU';
		$requete 		= $bdd->prepare
		("
		SELECT		E.email_Email,W.password_email 
		FROM 		email E,email_list_warmup W 
		WHERE 		E.id_List_Email = (select id_List from list where name_List = ? )
		AND			E.id_Email		=	W.id_email
		ORDER BY	E.id_Email ASC 
		LIMIT		0,$txtNumberEmailsToReport
		");
		$requete->execute(array($nameList));

		$tab_emails 		= 	array();
		$tab_passwords 		= 	array();
		while($row 			= 	$requete->fetch())
		{
			//Email :
			$email			=	trim($row['email_Email']);
			array_push($tab_emails,$email);

			//Password :
			$password		=	$row['password_email'];
			array_push($tab_passwords,$password);
		}
		$requete->closeCursor();
		
		ob_start();
		for($i=0;$i<count($tab_emails);$i++)
		{
			
			//Flagged ?
			$flagged	=	($chkMarkAsFlagged==1)?'\\\Flagged':''; 
			
			//Junk :
			if($chkReadSpamFolder==1)
			{
				$mbox 			=	imap_open("{imap-mail.outlook.com:993/imap/ssl}Junk", trim($tab_emails[$i]) , trim($tab_passwords[$i]));
				if(!$mbox)
				{
					echo "<br/>Seed : <b><mark>".$tab_emails[$i]."</mark></b>"."\t\t<span class='text-danger'>Cannot connect to MAILBOX: ".imap_last_error()."</span><br/>";
					
					ob_flush();
					flush(); 
					
					continue;
				}
				else
				{
					$countFoundedEmails =	imap_num_msg($mbox);
					if($countFoundedEmails>0)
					{  
						imap_setflag_full($mbox, "1:".$countFoundedEmails, "\\Seen $flagged");
						
						if($chkMoveFromSpamToInbox)
						{
							imap_mail_move($mbox,'1:'.$countFoundedEmails,'inbox');
						}
					}
				}
				imap_close($mbox);
			}
			
			
			
			//Inbox :
			if($chkReadInboxFolder==1)
			{
				$mbox2 			=	imap_open("{imap-mail.outlook.com:993/imap/ssl}INBOX", trim($tab_emails[$i]) , trim($tab_passwords[$i]));
				if(!$mbox2)
				{
					echo "<br/>Seed : <b><mark>".$tab_emails[$i]."</mark></b>"."\t\t<span class='text-danger'>Cannot connect to MAILBOX: ".imap_last_error()."</span><br/>";
					
					ob_flush();
					flush(); 
					
					continue;
				}
				else
				{
					$countFoundedEmails =	imap_num_msg($mbox2);
					if($countFoundedEmails>0)
					{  
						imap_setflag_full($mbox2, "1:".$countFoundedEmails, "\\Seen \\Flagged");
					}
				}
				imap_close($mbox2);
			}
			
			echo "<br/>Seed : <b><mark>".$tab_emails[$i]."</mark></b>"."\t\t<span class='text-success'>Processed"."</span><br/>";
			
			//Display the result
			ob_flush();
			flush();
		}
		ob_end_flush();
	}
}
?>
							
					
					    </div>
					</div>
					<!-- /form horizontal -->

					
					<!-- Footer -->
					<?php include'../Includes/footer.php'; ?>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
</body>
</html>
