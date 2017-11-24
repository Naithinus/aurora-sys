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
	<title>EXT - Show LOGS</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Show LOGS</span></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Show</a></li>
							<li class="active">LOGS</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->

				<div class="panel panel-flat">
					    <div class="panel-body">
					
			
					<form class="form-horizontal">
					    <fieldset class="content-group">
									<legend class="text-bold">Show LOGS</legend>

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Server</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbServers" id="cmbServers" class="select-clear" data-placeholder="Selet Server">
                                               <option value="-1">Select Server</option>
											    <?php
												   $id_Isp = $_SESSION['id_Isp_Employer'];
												   
												   $requete = $bdd->query('select s.alias_Server , s.id_Server , i.IP_IP from server s , ip i where s.id_IP_Server = i.id_IP');
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
																 <option value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option>
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
																 <option value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option>
																<?php
															}
															
														}
														
													 
													 
												   }
												?>
				                            </select>
			                            </div>
			                        </div>
									
										
										
										<div class="form-group">
											<label class="control-label col-lg-2">ID Send</label>
											<div class="col-lg-10">
												<select name="cmbIdSend" id="cmbIdSend" class="select-clear" data-placeholder="Selet Send">
                                               <option value="-1">Select ID Send</option>
											   <option value="0">Test</option>
											    <?php
												   $idMailer 				 = $_SESSION['id_Employer'];
												   $requete = $bdd->prepare('select id_Send from send where id_Employer_Send = ? order by id_Send desc');
												   $requete->execute(array($idMailer));
												   
												   while($row = $requete->fetch())
												   {
												   
													 ?>
													 <option value="<?php echo $row['id_Send'];?>"><?php echo $row['id_Send'];?></option>
													 <?php
													 
												   }
												?>
				                            </select>
												
												
											</div>
										</div>
										
										
										
									
									
									
									
										
										
									   

						</fieldset>

						</form>		
					
					
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

		$('#cmbServers').change(function(){
		   $('#pnl').html('');
		});
		
		$('#cmbIdSend').change(function(){
		   var ipServer = $('#cmbServers').val();
		   var idSend   = $(this).val();
		   var link     = 'http://'+ipServer+'/exactarget/Send/log_POST.php';
		   var idMailer = <?php echo $_SESSION['id_Employer'];?>;
		   
		   $.post('formLogs.php',{link:link,idSend:idSend,idMailer:idMailer},function(data){
		      $('#pnl').html(data);
		   });
		   
		});
		

		
		
</script>	
</body>
</html>
