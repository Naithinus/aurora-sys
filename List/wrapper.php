<?php
     set_time_limit(0);
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Wrapper</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Wrapper</span></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Wrapper</li>
							<li class="active"></li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal" method="POST" action="wrapper_POST.php" enctype="multipart/form-data">
					
					    <fieldset class="content-group">
									<legend class="text-bold">Wrapper</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">File</label>
										<div class="col-lg-10">
											<input type="file" name="data" id="data" class="form-control">
										</div>
									</div>

									<div class="form-group">
										<label class="control-label col-lg-2">IS Opt IN</label>
										<div class="col-lg-10">
										   <input type="checkbox" name="chkIsOptIN" id="chkIsOptIN"/>											
										</div>
									</div>
									
									<div class="form-group" id="infosOptIn">
										<label class="control-label col-lg-2">Infos</label>
										<div class="col-lg-10">
										<table>
										  <tr>
										    <td>Delimiter</td>
											<td>
											  <select name="cmbDelimiter">
											   <option value="/">/</option>
											   <option value=",">,</option>
											   <option value="|">|</option>
											   <option value=";">;</option>
											  </select>
											</td>
											<td></td>
										  </tr>
										  
										  <tr>
										    <td>INDEX EMAIL</td>
											<td><input type="text" name="indexEmail"/></td>
											<td></td>
										  </tr>
										  
										  </table>
										</div>
									</div>
									
									
									
									
									
									
									
									
									
									
									
						</fieldset>
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary">Wrapp<i class="icon-arrow-right14 position-right"></i></button>
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

	<script>
	
	$('#infosOptIn').hide();
	
	
	  $('#chkIsOptIN').click(function(){
	    if($(this).is(':checked'))
		  $('#infosOptIn').show();
		else
		  $('#infosOptIn').hide();

	  });
	  

	 
	 
	</script>
</body>
</html>
