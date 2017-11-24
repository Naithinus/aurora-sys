<?php

	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
	verify($monUrl);
	
	include('../Includes/bdd.php');
	  
	$id_ServerProvider             = '';
	$name_ServerProvider			 = '';
	$note_ServerProvider           = '';
	$website_ServerProvider        = '';
	$logo_ServerProvider			 = '';
	$buttonText         = 'Insert';
	  
	if(isset($_GET['id_ServerProvider']))
	{
	    $buttonText         = 'Update';
	    $id_ServerProvider  = $_GET['id_ServerProvider'];
	  
	    $requete = $bdd->prepare('select * from serverprovider where id_ServerProvider = ?');
	    $requete->execute(array($id_ServerProvider));
		extract($requete->fetch());
	}	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Server Provider</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Server Provider</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
					<ul class="breadcrumb">
						<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
						<li>Server Provider</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_ServerProvider_Post.php" enctype="multipart/form-data">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Server Provider</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtName" id="txtName" class="form-control" value="<?php echo $name_ServerProvider;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Note</label>
										<div class="col-lg-10">
											<textarea type="text" name="txtNote" id="txtNote" class="form-control"><?php echo $note_ServerProvider;?></textarea>
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Web Site</label>
										<div class="col-lg-10">
											<input type="text" name="txtWebSite" id="txtWebSite" class="form-control" value="<?php echo $website_ServerProvider;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Logo</label>
										<div class="col-lg-10">
											<input type="file" name="logo" id="logo" class="form-control">
										</div>
									</div>
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_ServerProvider']))
								{
								   ?>
								     <img src="Images/<?php echo $logo_ServerProvider;?>"/>
								     <input type="hidden" name="id_ServerProvider" value="<?php echo $id_ServerProvider;?>"/>
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
