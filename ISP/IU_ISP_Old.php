<?php
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	
	include('../Includes/bdd.php');
	  
	$id_Isp             = '';
    $name_isp			= '';
	$logo_isp           = '';
	$is_free_isp        = '0';
	$buttonText         = 'Insert';
	  
	if(isset($_GET['id_ISP']))
	{
	    $buttonText         = 'Update';
	    $id_ISP = $_GET['id_ISP'];
	    $requete = $bdd->prepare('select * from isp where id_ISP = ?');
	    $requete->execute(array($id_ISP));
		extract($requete->fetch());
	}
	  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>ISP</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">ISP</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>ISP</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_ISP_Post.php" enctype="multipart/form-data">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> ISP</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name ISP</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameISP" id="txtNameISP" class="form-control" value="<?php echo $name_isp;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">LOGO ISP</label>
										<div class="col-lg-10">
											<input type="file" name="logoISP" id="logoISP" class="form-control">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Is Free</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkIsFree" id="chkIsFree" <?php if($is_free_isp==1) echo 'checked';?>>
										</div>
									</div>
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_ISP']))
								{
								   ?>
								    
									
									<div class="form-group">
										<label class="control-label col-lg-2">Current LOG</label>
										<div class="col-lg-10">
											<img src="Images/<?php echo $logo_isp;?>" width="75px;"height="75px;"/>
										</div>
									</div>
									
									
								     
								     <input type="hidden" name="idISP" value="<?php echo $id_ISP;?>"/>
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
