<?php
     include_once('Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 if($_SESSION['type_Employer']=="Mailer" || $_SESSION['type_Employer']=="Team Leader")
	   header('location:Send/ShowSends.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>
	
	
	
	
	
	
	
	<?php include('Includes/css.php');?>
	<?php include('Includes/js.php');?>
	

	
	
	<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
	
    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="assets/js/pages/form_select2.js"></script>
	

</head>

<body>
	
	<?php include('Includes/navbar.php');?>
	<?php include('Includes/bdd.php');?>
    


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
		<?php Include('Includes/sidebar.php');?>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Forms</span> - Basic Inputs</h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="form_inputs_basic.html">Forms</a></li>
							<li class="active">Basic inputs</li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal">
					
					    <fieldset class="content-group">
									<legend class="text-bold">Show Sends</legend>

									
									<div class="form-group" id="divServers">
										<label class="control-label col-lg-2">Mailers</label>
										
											<div class="form-group">
												<div class="col-lg-10">
													<select name="cmbMailers" id="cmbMailers" class="select-clear" data-placeholder="Select Mailer">
														<option value="-1">Select Mailer</option>
														<?php
															$requete = $bdd->query('select e.* from employer e , type_employer te where e.id_type_Employer_Employer = te.id_type_Employer and te.name_type_Employer IN("Mailer","Team Leader")');
															while($row = $requete->fetch())
															{
																 ?><option value="<?php echo $row['id_Employer'];?>"><?php echo $row['lastName_Employer'];?></option><?php
															}
														?>	
													</select>
												</div>
			                                </div>
											
											
											<div id="divSends">
											
											
																	
						
											</div>
								    </div>
									
									
									
									
						</fieldset>
								
								
								
								
					</form>
					
					    </div>
					</div>
					<!-- /form horizontal -->

					
					<!-- Footer -->
					<?php include('Includes/footer.php'); ?>
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
	  
	  
	  
	  $('#cmbMailers').change(function(){
		  
		  var idMailer = $(this).val();
		  
		  $.post('Send/getSend_POST.php',{idMailer:idMailer},function(data){
			  
			  $('#divSends').html('').html(data);
			  
			  $('.btnTestSend').click(function(){
					var idSend   = $(this).attr('id');
   
				   $.post('Send/SendTestOutSide.php',{id_Send:idSend},function(data){
					  alert(data);
				   });
   
			  });

		  });
		  
	  });
	</script>
</body>
</html>
