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
	<title>EXT - Show VMTAS</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
		<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">VMTAS</span> - Show</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>VMTAS</li>
							<li class="active">Show</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal" method="POST">
					
					    <fieldset class="content-group">
									<legend class="text-bold">NEW VMTA</legend>
									
						<div class="form-group">
			                        	<label class="control-label col-lg-2">Server</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbServers" id="cmbServers" class="select-clear" data-placeholder="Select Server">
											    <option value="-1">Select Server...</option>
                                                <?php
												    $id_Isp = $_SESSION['id_Isp_Employer'];
													$requete = $bdd->query('select * from server');
													while($row = $requete->fetch())
													{
																$requeteIsp = $bdd->prepare('select id_Server from serverisp where id_Server = ? and id_Isp = ?');
																$requeteIsp->execute(array($row['id_Server'],$id_Isp));
																$SubrowIsp = $requeteIsp->fetch();
																if($SubrowIsp)
																{
																	$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 0');
																	$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
																	$SubrowMailer = $requeteMailer->fetch();
																	if(!$SubrowMailer)
																	{
																		
																		?>
																		<option value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>
																		<?php
																	}
																}
																else
																{
																	
																	$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
																	$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
																	$SubrowMailer = $requeteMailer->fetch();
																	if($SubrowMailer)
																	{
																		
																		?>
																		<option value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>
																		<?php
																	}
																	
																}
															  
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
							
				<div id="tableVMTAS">							
							
				</div>	
				</fieldset>
				</form>
				
					    </div>
					</div>
					
					
					<!-- /form horizontal -->

					
					<div id="modal_theme_danger" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header bg-danger">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h6 class="modal-title">Confirmation Alert</h6>
								</div>

								<div class="modal-body">
									<h6 class="text-semibold">Are You Sure ?</h6>
									
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-danger" id="btnDeleteModal">Delete</button>
								</div>
							</div>
						</div>
					</div>
					
					
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
		 
		 var idServer = $(this).val();
		 
	
		 $.post('listVMTA.php',{idServer:idServer},function(data){
			 $('#tableVMTAS').html('').html(data);
		 });
		 
	 });
	 
	</script>
	
</body>
</html>
