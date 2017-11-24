<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Show Sends</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	
        <script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Show</span> - Sends</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Send</a></li>
							<li class="active">Show Sends</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					
					<form class="form-horizontal" method="POST" action="IU_Domain_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold">Stats Offer</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Offer</label>
										
											<div class="form-group">
												<div class="col-lg-10">
													<select name="cmbOffer" id="cmbOffer" class="select-clear" data-placeholder="Select Offer">
														<option value="-1">Select Offer</option>
														<?php
															$requete = $bdd->query('select * from offer');
															while($row = $requete->fetch())
															{
																 ?><option value="<?php echo $row['id_offer'];?>"><?php echo $row['name_Offer'];?></option><?php
															}
														?>	
													</select>
												</div>
			                                </div>
								    </div>
									
									
					</form>
					</div>
					</div>
					</div>

					<div id="divStats">
				
					</div>		
					<!-- /form horizontal -->

					
					<!-- Footer -->
					<?php include'../Includes/footer.php'; ?>
					<!-- /footer -->

				
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

<script>

$('#cmbOffer').change(function(){
   var idOffer = $(this).val();
   
   $.post('StatsOffer_POST.php',{cmbOffer : idOffer},function(data){
		$('#divStats').html(data);
   });
   
});


</script>	
</body>
</html>
