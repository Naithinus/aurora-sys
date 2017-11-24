<?php
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include('../Includes/bdd.php');
	  
	$id_OS   = '';
	$name_OS = '';
	$bit_OS = 32;
	$buttonText         = 'Insert';
	  
	if(isset($_GET['id_OS']))
	{
	    $buttonText         = 'Update';
	    $id_OS = $_GET['id_OS'];
	    $requete = $bdd->prepare('select * from os where id_OS = ?');
	    $requete->execute(array($id_OS));
		extract($requete->fetch());
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>OS</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">OS</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>OS</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_OS_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> OS</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameOS" id="txtNameOS" class="form-control" value="<?php echo $name_OS;?>">
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-2">Bit</label>
										<div class="col-lg-10">
											<select class="select-clear" data-placeholder="Select OS" name="cmbBit" id="cmbBit">
											   <option value="32" <?php echo ($bit_OS == 32) ? "selected" : "";?>>32 bit</option>
											   <option value="64" <?php echo ($bit_OS == 64) ? "selected" : "";?>>64 bit</option>
											</select>
										</div>
									</div>
									
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_OS']))
								{
								   ?>
								     <input type="hidden" name="id_OS" value="<?php echo $id_OS;?>"/>
								   <?php
								}
								?>
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?> <i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
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

</body>
</html>
