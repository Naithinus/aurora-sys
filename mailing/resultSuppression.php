<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	include('../Includes/bdd.php');
	  
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Result Suppression</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Result</span> - Suppression</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Result</li>
							<li class="active">Supression</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					
					
					    <fieldset class="content-group">
									<legend class="text-bold">Result Suppression</legend>
									 
									 <center>
									 
									<div>
									
									<table>
									
									  <tr>
									    <td> Original Count:</td>
										<td> <span class="label bg-blue"><?php echo $_GET['originalCount'];?></span> </td>
									  </tr>
									  
									  <tr>
									    <td> Final Count:</td>
										<td> <span class="label bg-success-400"><?php echo $_GET['finalCount'];?></span> </td>
									  </tr>
									  
									   <tr>
									    <td> Total Deleted:</td>
										<td> <span class="label bg-danger"><?php echo $_GET['originalCount']-$_GET['finalCount'];?></span> </td>
									  </tr>
									  
									  
									</table>
									  
									  <br/>
									  <h3>You will be redirected in 5 seconds...</h3>
									  <h3>Or <a href="ShowSends.php">Click Here</a></h3>
									    
									  
									</div>
									 <center>
									 
									
									
						</fieldset>
								
								
								
								
					
					
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

<meta http-equiv="refresh" content="5; url=http://79.143.189.72/exactarget/Send/ShowSends.php">


