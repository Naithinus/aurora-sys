<?php
    date_default_timezone_set('UTC');
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include_once('../Includes/bdd.php');
	
	$buttonText 			                   = 'Insert';
	$id_Notification			       		           = '';
	$text_Notification			       		       = '';
	
	
	if(isset($_GET['id_Notification']))
	{
	   $buttonText 			         = 'Update';
	   $id_Notification              = $_GET['id_Notification'];
	   
	   $requete = $bdd->prepare('select * from notification where id_Notification = ?');
	   $requete->execute(array($id_Notification));
	   extract($requete->fetch());

	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Notification</title>
	
	
	
	
	
	
	
	<?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
    
    <script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="../assets/js/pages/picker_date.js"></script>

</head>

<body>
	<?php 
	include('../Includes/navbar.php');
	
	
	
	?>
    


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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Notification</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Notification</li>
							<li class="active"><?php echo $buttonText;?></li>
					    </ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal" method="POST" action="IU_Notification_POST.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Notification</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Text Notification</label>
										<div class="col-lg-10">
											<input type="text" name="txtNotification" id="txtNotification" class="form-control" value="<?php echo $text_Notification;?>">
										</div>
									</div>
									
									
									
								
									<div class="form-group">
										<label class="control-label col-lg-2">Mailers Yes</label>
										<div class="input-group">
											<select name="cmbMailersYes[]" id="cmbMailersYes" multiple>
											 <?php
											 
											    $subRequete = $bdd->prepare('select id_mailer from notification_mailer where id_notification = ?');
											    $subRequete->execute(array($id_Notification));
											    $split = array();
										   
											    while($subRow = $subRequete->fetch())
											    {
												   $mailersNotification = $subRow['id_mailer'];
												   $split[] = $mailersNotification;
											    }
										   
											    $requete = $bdd->query("select * from employer");
											    
												while($row = $requete->fetch())
												{
													?>
													<option value="<?php echo $row["id_Employer"];?>" <?php echo (in_array(strval($row["id_Employer"]),$split)) ? "selected" : "";?>><?php echo $row["id_Employer"].'-'.$row["firstName_Employer"].'-'.$row["LastName_Employer"];?></option>
													<?php
												}
											 ?>
											</select>
										</div>
									</div>
									
									
								
									
							</div>		
									
									
						</fieldset>
								
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?><i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
								<?php
								  if(isset($_GET['id_Notification']))
								  {
								     ?> <input type="hidden" name="idNotification" id="idNotification" value="<?php echo $id_Notification;?>"/><?php
								  }
								?>
					</form>
					
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

	
	<script>
	 
	 
	 $('#droits').hide();
	 
	  $('#chkIsActive').click(function(){
	    if($(this).is(':checked'))
		  $('#droits').show();
		else
		  $('#droits').hide();

	  });
	  
	  <?php
	    if($isActive_Server==1)
		{
			?> $('#droits').show(); <?php
		}
	 ?>
	 
	</script>
</body>
</html>
