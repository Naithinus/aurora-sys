<?php
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 date_default_timezone_set('UTC');
	include('../Includes/bdd.php');
	
	$buttonText 			           = 'Insert';
	$id_Domain			       		   = '';
	$name_Domain   			   		   = '';
	$saleDate_Domaine   			   = '';
	$expirationDate_Domain   		   = '';
	$id_Domain_Provider_Domain   	   = '';
	
	if(isset($_GET['id_Domain']))
	{
	   $buttonText 			           = 'Update';
	   $id_Domain                      = $_GET['id_Domain'];
	   
	   $requete = $bdd->prepare('select * from domain where id_Domain = ?');
	   $requete->execute(array($id_Domain));
	   extract($requete->fetch());
	   
	   $date 					       = new DateTime($saleDate_Domaine);
	   $saleDate_Domaine			   = $date->format('m/d/Y');
	   
	   $date 					       = new DateTime($expirationDate_Domain);
	   $expirationDate_Domain		   = $date->format('m/d/Y');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Domain</title>
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Domain</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="form_inputs_basic.html">Domain</a></li>
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
					
					<form class="form-horizontal" method="POST" action="IU_Domain_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Domain</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Domain Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtDomainName" id="txtDomainName" class="form-control" value="<?php echo $name_Domain;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Sale Date</label>
										<div class="input-group" style="position:relative;left:10px;width:835px;">
											<span class="input-group-addon"><i class="icon-calendar22"></i></span>
											<input type="text" class="form-control daterange-single"  name="txtDateSale" id="txtDateSale" value="<?php echo $saleDate_Domaine;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Expiration Date</label>
										<div class="input-group" style="position:relative;left:10px;width:835px;">
											<span class="input-group-addon"><i class="icon-calendar22"></i></span>
											<input type="text" class="form-control daterange-single"  name="txtDateExpiration" id="txtDateExpiration" value="<?php echo $expirationDate_Domain;?>">
										</div>
									</div>
									

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Provider</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbDomainProvider" id="cmbDomainProvider" class="select-clear" data-placeholder="Select Domain Provider">
                                                <?php
													$requete = $bdd->query('select * from domainprovider');
													while($row = $requete->fetch())
													{
													     ?><option value="<?php echo $row['id_DomainProvider'];?>" <?php echo ($row['id_DomainProvider'] == $id_Domain_Provider_Domain) ? "selected" : "";?>><?php echo $row['name_DomainProvider'];?></option><?php
													}
												?>	
				                            </select>
			                            </div>
			                        </div>

									
						</fieldset>
								
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?><i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
								<?php
								  if(isset($_GET['id_Domain']))
								  {
								     ?> <input type="hidden" name="id_Domain" id="id_Domain" value="<?php echo $id_Domain;?>"/><?php
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
