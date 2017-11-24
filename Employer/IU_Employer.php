<?php
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	date_default_timezone_set('UTC');
	include('../Includes/bdd.php');
	
	$buttonText 			   = 'Insert';
	$id_Employer			   = '';
	$firstName_Employer 	   = '';
	$lastName_Employer 		   = '';
	$dob_Employer			   = '';
	$dateIn_Employer 		   = '';
	$dateOut_Employer          = '';
	$salaire_Employer 		   = '';
	$id_type_Employer_Employer = '1';
	$password_Employer         = '';
	$id_Isp_Employer           = '1';
	
	if(isset($_GET['id_Employer']))
	{
	   $buttonText 			   = 'Update';
	   $id_Employer            = $_GET['id_Employer'];
	   
	   $requete = $bdd->prepare('select * from employer where id_Employer = ?');
	   $requete->execute(array($id_Employer));
	   extract($requete->fetch());
	   
	   $date 					   = new DateTime($dob_Employer);
	   $dob_Employer			   = $date->format('m/d/Y');
	   
	   $date 					   = new DateTime($dateIn_Employer);
	   $dateIn_Employer			   = $date->format('m/d/Y');
	   
	   $date 					   = new DateTime($dateOut_Employer);
	   $dateOut_Employer		   = $date->format('m/d/Y');
	}
	
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Employer</title>
	
	
	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Employer</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="form_inputs_basic.html">Employer</a></li>
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
					
					<form class="form-horizontal" method="POST" action="IU_Employer_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Employer</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">First Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtFirstName" id="txtFirstName" class="form-control" value="<?php echo $firstName_Employer;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Last Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtLastName" id="txtLastName" class="form-control" value="<?php echo $lastName_Employer;?>">
										</div>
									</div>
									
								
								
									<div class="form-group">
										<label class="control-label col-lg-2">Date Of Birth</label>
										<div class="col-lg-10">
											<input type="text" name="txtDOB" id="txtDOB" class="form-control" value="<?php echo $dob_Employer;?>" placeholder="MM/DD/YYYY">
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Date In</label>
										<div class="input-group" style="position:relative;left:10px;width:835px;">
											<span class="input-group-addon"><i class="icon-calendar22"></i></span>
											<input type="text" class="form-control daterange-single"  name="txtDateIn" id="txtDateIn" value="<?php echo $dateIn_Employer;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Date Out </label>
										<div class="input-group" style="position:relative;left:10px;width:835px;">
											<span class="input-group-addon"><i class="icon-calendar22"></i></span>
											<input type="text" class="form-control daterange-single"  name="txtDateOut" id="txtDateOut" value="<?php echo $dateOut_Employer;?>">
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Salaire</label>
										<div class="col-lg-10">
											<input type="text" name="txtSalaire" id="txtSalaire" class="form-control" value="<?php echo $salaire_Employer;?>">
										</div>
									</div>
									
									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Type Employer</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbTypeEmployer" id="cmbTypeEmployer" class="select-clear" data-placeholder="Select Type Employer">
                                                <?php
													$requete = $bdd->query('select * from type_employer');
													while($row = $requete->fetch())
													{
													   if($row['id_type_Employer'] == $id_type_Employer_Employer)
													   {
													   ?>
													     <option value="<?php echo $row['id_type_Employer'];?>" selected><?php echo $row['name_type_Employer'];?></option>
													   <?php
													   }
													   
													   else
													   {
													      ?>
													     <option value="<?php echo $row['id_type_Employer'];?>"><?php echo $row['name_type_Employer'];?></option>
													     <?php
													   }
													  
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
									
									
									
									
									<div class="form-group" id="divISP">
			                        	<label class="control-label col-lg-2">ISP</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbISP" id="cmbISP" class="select-clear" data-placeholder="Select ISP">
                                                <?php
													$requete = $bdd->query('select * from isp');
													while($row = $requete->fetch())
													{
													   if($row['id_Isp'] == $id_Isp_Employer)
													   {
													   ?>
													     <option value="<?php echo $row['id_Isp'];?>" selected><?php echo $row['name_isp'];?></option>
													   <?php
													   }
													   
													   else
													   {
													      ?>
													     <option value="<?php echo $row['id_Isp'];?>"><?php echo $row['name_isp'];?></option>
													     <?php
													   }
													  
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
									
									
									
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Password</label>
										<div class="col-lg-10">
											<input type="password" class="form-control" name="txtPassword" id="txtPassword" value="<?php echo $password_Employer;?>">
										</div>
									</div>
									
									
						</fieldset>
								
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?><i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
								<?php
								  if(isset($_GET['id_Employer']))
								  {
								     ?> <input type="hidden" name="idEmployer" id="idEmployer" value="<?php echo $id_Employer;?>"/><?php
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
	  <?php 
	  if(is_null($id_Isp_Employer))
	  {
	    ?>
		  $("#divISP").hide();
		<?php
	  }  
	  ?>
	  $('#cmbTypeEmployer').change(function(){
	     var type = $('#cmbTypeEmployer :selected').text();
		 if(type == "Mailer" || type== "Team Leader")
		   $("#divISP").show();
		   
		 else
		   $("#divISP").hide();

	  });
	  
	</script>
</body>
</html>
