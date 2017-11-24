<?php
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 include('../Includes/bdd.php');
	  
	  $id_TypeList   = '';
	  $name_TypeList = '';
	  $abr_TypeList  = '';
	  $buttonText         = 'Insert';
	  
	  if(isset($_GET['id_TypeList']))
	  {
	    $buttonText         = 'Update';
	    $id_TypeList = $_GET['id_TypeList'];
	    $requete = $bdd->prepare('select * from typelist where id_TypeList = ?');
	    $requete->execute(array($id_TypeList));
		extract($requete->fetch());
	  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Type List</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Type List</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Type List</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_TypeList_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Type List</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameTypeList" id="txtNameTypeList" class="form-control" value="<?php echo $name_TypeList;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Abreviation</label>
										<div class="col-lg-10">
											<input type="text" name="txtAbreviation" id="txtAbreviation" class="form-control" value="<?php echo $abr_TypeList;?>">
										</div>
									</div>
									
									
									
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_TypeList']))
								{
								   ?>
								     <input type="hidden" name="id_TypeList" value="<?php echo $id_TypeList;?>"/>
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
