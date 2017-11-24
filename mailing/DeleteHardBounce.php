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
	<title>EXT - IMAP BOX</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">IMAP</span> BOX</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">IMAP</a></li>
							<li class="active">BOX</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->

				<div class="panel panel-flat">
					    <div class="panel-body">
					
			
					<form class="form-horizontal" method="POST" action="DeleteHardBounce_POST.php" enctype="multipart/form-data">
					    <fieldset class="content-group">
									<legend class="text-bold">IMAP</legend>

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">File</label>
			                        	<div class="col-lg-10">
				                            <input type="file" name="fileHardBounce" class="form-control"/>
			                            </div>
			                        </div>
									
										
									<div class="text-right">
									  <button type="submit" class="btn btn-primary">Submit<i class="icon-arrow-right14 position-right"></i></button>
								    </div>	
										
										
										
									   

						</fieldset>

						</form>		
					
					
						</div>
				</div>		

				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					
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

		$('#cmbServers').change(function(){
		   
		   var ip = $(this).val();
		   $('#framePMTA').attr('src','http://'+ip+':8080');

		});
		
		$('#btnDelete').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'delete';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		
		$('#btnPause').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'pause';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnResume').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'resume';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnReset').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'reset';
		   
		   commands(ip,command,'');
		});
		
		
		
		function commands(ip,command,queue)
		{
		
		  var link = 'http://'+ip+'/exactarget/Send/commandsPMTA.php';
		  
		  $.post(link,{command:command,queue:queue},function(data){
		    alert(data);
		  });
		  
		}
		

		
		
</script>	
</body>
</html>
