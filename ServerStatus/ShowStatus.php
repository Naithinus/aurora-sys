<?php 
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl      = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Show Servers Status</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Servers</span> - Status</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Servers</li>
							<li class="active">Status</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>Server Name</th>
									<th>Statu</th>
									
									
									
								</tr>
							</thead>
							
							<tbody>
							
							<?php
							    include('../Includes/bdd.php');
								$id_Isp = $_SESSION['id_Isp_Employer'];
								$requete = $bdd->query('select i.IP_IP,s.id_Server,s.alias_Server from server s , ip i where s.id_IP_Server = i.id_IP');
								 
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
														$ip = $row['IP_IP'];
												?>
														<tr>
															<td><?php echo $row['alias_Server'];?></td>
															<td>
															<?php
																
															    exec("ping -c 1 " . $ip, $output, $result);
		
															    if ($result == 0)
																{
																	?>
																	    <span class="label label-success">Connected</span> 
																	<?php
																}
																else
																{
																	?>
																         <span class="label label-danger">Down</span>
																    <?php
																}
															?>
															
															</td>
														</tr>
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
												
												
														$ip = $row['IP_IP'];
												?>
														<tr>
															<td><?php echo $row['alias_Server'];?></td>
															<td>
															<?php
																
															    exec("ping -c 1 " . $ip, $output, $result);
		
															    if ($result == 0)
																{
																	?>
																	    <span class="label label-success">Connected</span> 
																	<?php
																}
																else
																{
																	?>
																         <span class="label label-danger">Down</span>
																    <?php
																}
															?>
															
															</td>
														</tr>
												<?php
											}
											
										}
										
								  
								 }
							  
							?>
								
								
							</tbody>
						</table>
					
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
	  
	</script>
</body>
</html>
