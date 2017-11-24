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
	<title>EXT - SPF Checker</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">SPF</span> - Checker</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">SPF</a></li>
							<li class="active">Checker</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->

				<div class="panel panel-flat">
					    <div class="panel-body">
					
			
					
					    <fieldset class="content-group">
									<legend class="text-bold">SPF CHEKCER</legend>

									<div class="form-group">
										<center>
										<table>
										
										   <tr>
										     <td><label class="control-label col-lg-2">Domains</label></td>
											 
											 <td>
											    <div class="col-lg-10">
													<textarea id="txtDomains" name="txtDomains" rows="8" class="form-control" style="width:500px;"></textarea>
												</div>
											 </td>
											 
										   </tr>
										   
										</table>
										<br/>
										
										<button type="button" id="btnCheck" class="btn btn-primary">Check<i class="icon-arrow-right14 position-right"></i></button>
										</center>
									</div>
									
						</fieldset>

								
					
						</div>
				</div>		

				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body" id="pnl">
					
							
					
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

$('#btnCheck').click(function(){
  
  
  
  var domains = $('#txtDomains').val();
  var d = domains.split('\n');
  var chaine = '';
  for(var i = 0 ; i<d.length ; i++)
  {
	  chaine+=d[i]+',';
  }
  
  $.post('spfPOST2.php',{domains : chaine},function(data){
     $('#pnl').html('').html(data);
  });
  
});

</script>	
</body>
</html>
