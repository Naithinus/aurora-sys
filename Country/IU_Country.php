<?php 

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 
	include('../Includes/bdd.php');
	
	
    $id_Country           = '';
    $name_Country		  = '';
    $flag_Country         = '';
    $buttonText           = 'Insert';
  
    if(isset($_GET['id_Country']))
    { 
		$buttonText         = 'Update';
		$id_Country = $_GET['id_Country'];
		$requete = $bdd->prepare('select * from country where id_Country = ?');
		$requete->execute(array($id_Country));
		extract($requete->fetch());
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Country</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_checkboxes_radios.js"></script>

	
	
	
	
	<?php
	
	  
	?>
</head>

<body>

	<!-- NAVBAR -->
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Country</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Country</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_Country_Post.php" enctype="multipart/form-data">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Country</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameCountry" id="txtNameCountry" class="form-control" value="<?php echo $name_Country;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="control-label col-lg-2">Flag</label>
										<div class="col-lg-10">
											<input type="file" name="logo" id="logo" class="form-control">
										</div>
									</div>

									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_Country']))
								{
								   ?>
								     <div class="form-group">
										<label class="control-label col-lg-2">Current Flag</label>
										<div class="col-lg-10">
											<img src="Images/<?php echo $flag_Country;?>" style="width:20px;height:20px;"/>
										</div>
									</div>
									
								     
								     <input type="hidden" name="idCountry" value="<?php echo $id_Country;?>"/>
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
