<?php
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 
	include('../Includes/bdd.php');
	  
	  $id_type_Employer   = '';
	  $name_type_Employer = '';
	  $buttonText         = 'Insert';
	  
	  if(isset($_GET['id_type_Employer']))
	  {
	    $buttonText         = 'Update';
	    $id_type_Employer = $_GET['id_type_Employer'];
	    $requete = $bdd->prepare('select * from type_employer where id_type_Employer = ?');
	    $requete->execute(array($id_type_Employer));
		extract($requete->fetch());
	  } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Type Employer</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Type Employer</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Type Employer</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_Type_Employer_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Type Employer</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name Type Employer</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameTypeEmployer" id="txtNameTypeEmployer" class="form-control" value="<?php echo $name_type_Employer;?>">
										</div>
									</div>
						</fieldset>
								
								<?php 
								if(isset($_GET['id_type_Employer']))
								{
								   ?>
								     <input type="hidden" name="idTypeEmployer" value="<?php echo $id_type_Employer;?>"/>
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
