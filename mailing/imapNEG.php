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
					
			
					<form class="form-horizontal">
					    <fieldset class="content-group">
									<legend class="text-bold">IMAP</legend>

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">ISP</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbISP" id="cmbISP" class="select-clear" data-placeholder="Select ISP">
                                                <option value="aol">AOL</option>
												<option value="yahoo">YAHOO</option>
												<option value="hotmail">HOTMAIL</option>
				                            </select>
			                            </div>
			                        </div>
									
										
										<div class="form-group">
											<label class="control-label col-lg-2">Email</label>
											<div class="col-lg-10">
												<input type="text" name="txtEmail" id="txtEmail" class="form-control" value="">
											</div>
										</div>
									
									
									
									
										
										<div class="form-group">
											<label class="control-label col-lg-2">Password</label>
											<div class="col-lg-10">
												<input type="password" name="txtPassword" id="txtPassword" class="form-control" value="">
											</div>
										</div>
									
									
									    <div class="form-group">
											<label class="control-label col-lg-2">Folder</label>
											<div class="col-lg-10">
												<select name="cmbFolder" id="cmbFolder" class="select-clear" data-placeholder="Select Folder">
													<option value="INBOX">INBOX</option>
													<option value="SPAM">SPAM</option>
												</select>
											</div>
			                            </div>
									   

						</fieldset>

						</form>		
					
					<center>
						<button type="submit" id="btnGO" class="btn btn-primary">GO<i class="icon-arrow-right14 position-right"></i></button>
						<img id="loader" src="../images/loader.gif" width="120" height="120">
					</center>
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
$('#loader').hide();
$('#btnGO').click(function(){
  
  $('#btnGO').hide();
  $('#loader').show();
  var isp       = $('#cmbISP').val();
  var email     = $('#txtEmail').val();
  var password  = $('#txtPassword').val();
  var folder    = $('#cmbFolder').val();
  
  $.post('imapNEGPOST.php',{cmbISP : isp,txtEmail : email,txtPassword :  password , cmbFolder: folder},function(data){
     $('#pnl').html('').html(data);
	 $('#loader').hide();
	 $('#btnGO').show();
  });
  
});

</script>	
</body>
</html>
