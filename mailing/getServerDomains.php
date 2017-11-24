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
	<title>EXT - Server - Domains - IPS</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Server - Domains - IPS</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Send</a></li>
							<li class="active">Server - Domains - IPS</li>
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
									<legend class="text-bold">Server - Domains - IPS</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Filter</label>
										
											<div class="form-group">
												<div class="col-lg-10">
													<input type="radio" id="byDomains" name="radioFilter" />Domains
													<input type="radio" id="byServers" name="radioFilter" />Servers
													<input type="radio" id="byIPS"     name="radioFilter" />IPS
												</div>
			                                </div>
								    </div>
									
									
									<div class="form-group" id="divDomains">
										<label class="control-label col-lg-2">Domains</label>
										
											<div class="form-group">
												<div class="col-lg-10">
												   <textarea id="txtDomains" class="form-control" rows="10"></textarea>
												</div>
			                                </div>
											
											<div class="form-group">
												<center><button type="button" class="btn btn-primary" id="btnDomains">Search<i class="icon-arrow-right14 position-right"></i></button><center>
										    </div>
											
								    </div>

									
									<div class="form-group" id="divServers">
										<label class="control-label col-lg-2">Servers</label>
										
											<div class="form-group">
												<div class="col-lg-10">
													<select name="cmbServers" id="cmbServers" class="select-clear" data-placeholder="Select Server">
														<option value="-1">Select Server</option>
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
																			?><option value="<?php echo $row['id_Server'];?>"><?php echo $row['alias_Server'];?></option><?php
																		}
																 }
														
																  else
																  {
																	
																		$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
																		$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
																		$SubrowMailer = $requeteMailer->fetch();
																		if($SubrowMailer)
																		{
																			?><option value="<?php echo $row['id_Server'];?>"><?php echo $row['alias_Server'];?></option><?php
																		}
																	
																   }
																 
															}
														?>	
													</select>
												</div>
			                                </div>
								    </div>
									
									
									<div class="form-group" id="divIPS">
										<label class="control-label col-lg-2">IPS</label>
										
											<div class="form-group">
												<div class="col-lg-10">
												   <textarea id="txtIPS" class="form-control" rows="10"></textarea>
												</div>
			                                </div>
											
											<div class="form-group">
												<center><button type="button" class="btn btn-primary" id="btnIPS">Search<i class="icon-arrow-right14 position-right"></i></button><center>
										    </div>
											
								    </div>
									
									
					</form>
					
					</div>
					</div>
					</div>

					<div id="divResult" class="panel-body" style="background-color:white;">
				
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

$('#divDomains').hide();
$('#divServers').hide();
$('#divIPS').hide();


$('#btnDomains').click(function(){
	
	
	var domains = $('#txtDomains').val();
	 
	 $.post('getDomains_Post.php',{type:"domains",domains:domains},function(data){
		 
		 $('#divResult').html('').html(data);
		 
	 });
	 
});



$('#btnIPS').click(function(){
	
	
	var ips = $('#txtIPS').val();
	 
	 $.post('getDomains_Post.php',{type:"ips",ips:ips},function(data){
		 
		 $('#divResult').html('').html(data);
		 
	 });
	 
});




$('#byDomains').click(function(){
	 
	 $('#divDomains').show();
	 $('#divServers').hide();
	 $('#divIPS').hide();
	 $('#divResult').html('');
});


$('#byServers').click(function(){
	 
	 $('#divDomains').hide();
	 $('#divServers').show();
	 $('#divIPS').hide();
	 $('#divResult').html('');
});


$('#byIPS').click(function(){
	 
	 $('#divDomains').hide();
	 $('#divServers').hide();
	 $('#divIPS').show();
	 $('#divResult').html('');
});



$('#cmbServers').change(function(){
	
	var idServer = $(this).val();
	
	$.post('getDomains_Post.php',{type:"servers",idServer:idServer},function(data){
		 
		 $('#divResult').html('').html(data);
		 
	 });
	 
});




</script>	
</body>
</html>
