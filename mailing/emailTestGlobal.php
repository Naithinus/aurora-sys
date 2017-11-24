<?php
	 include_once('../../exactarget/Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Email Test Global</title>
	
	
	

	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	
	

	
        
	
	
</head>

<body style="background-color:#F5F5F5;">
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
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Email Test Global</span></h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Email Test Global</a></li>
						</ul>
					</div>
				</div>
				


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
							<form class="form-horizontal" method="POST" id="formEmailTest">
							<div class="col-md-12">
								<div class="panel col-md-5">
									<div class="panel-body">
										
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Email Test:</span></label>
											<textarea class="form-control" name="txtEmailTest" id="txtEmailTest" style="resize:none" rows="4"></textarea>
										</div>
										
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Return Path:</span></label>
											<input type="text" class="form-control" name="txtReturnPath" id="txtReturnPath" value="[RANDOMC/10]@[domain]"/>
										</div>
										
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Header:</span></label>
											<textarea class="form-control text-size-large" name="txtHeader" id="txtHeader" style="width:100%;resize:none;" rows="8">
fromName:[sr]
fromEmail: <contact@[domain]>
subject:[ip]
date:[date]
to:[to]
reply-to:<reply@[domain]></textarea>
										</div>
										
										
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Body: </span></label>
											<textarea class="form-control text-size-large" name="txtBody" id="txtBody" style="width:100%;height:auto;resize:none;font-size:12px" rows="15"></textarea>
										</div>
										
										
									</div>
								</div>
									
									
									
									<?php
										include('../Includes/bdd.php');
										$id_Isp = $_SESSION['id_Isp_Employer'];
										$requete = $bdd->query('select id_Server,alias_Server from server');
									?>	
									<div class="panel col-md-7">
										<div class="panel-body">
											<div class="form-group">
												<div class="col-lg-3">
													<div class="form-group">
														<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Server</span></label>
														<input type="text" id="txtServerFilter" name="txtServerFilter"  class="form-control" placeholder="Filter server">
													</div>
												</div>
												<div class="col-lg-5">
													<div class="form-group">
														<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">SELECT IPS</span></label>
														<input type="text" id="txtVmtaFilter" name="txtVmtaFilter"  class="form-control" placeholder="Filter vmta">
													</div>
												</div>
												<div class="col-lg-1 pull-right">
													<div class="form-group pull-right">
														<label></label>
														<span align="right"><button  type="button" class="btn btn-info" id="btnSelect">Select IPS</button></span>
													</div>
												</div>

											</div>
											<div class="form-group">
												<div class="col-lg-3">
													<div class="form-group">
														<select id="cmbServers" name="cmbServers[]" multiple="multiple" class="form-control" size="15">
														  <?php
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
												<div class="col-lg-5">
													<div class="form-group">
														<select id="cmbIPs" style="overflow-x:auto" name="cmbIPs[]" multiple="multiple" class="form-control"  size="15">
														</select>
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<textarea class="form-control" style="height:200px;resize:none;overflow-y:scroll;" name="select" id="select"></textarea>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-lg-3">
													<span class="label label-block label-primary text-left" id="txtServerCount">No  selected server</span>
												</div>

												<div class="col-lg-5">
													<span class="label label-block label-primary text-left" id="txtVMTACount" >No selected VMTA</span>
												</div>
											</div>	
											<div class="form-group">
												<div class="col-lg-3">
													<label><span class="label bg-info-400">Fraction</span></label>
													<input type="text" id="fraction" name="fraction"  class="form-control" placeholder="Fraction">
												</div>
												<div class="col-lg-3">
													<label><span class="label bg-info-400">Delay</span></label>
													<input type="text" id="delay" name="delay"  class="form-control" placeholder="in seconds">
												</div>
												<div class="col-lg-3">
													<center><label><span class="label bg-info-400">Rotation</span></label><br>
													<input type="checkbox" id="rotation" name="rotation"></center>
												</div>
											</div>
											<div class="form-group">
												<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">File:</span></label>
												<textarea class="form-control" name="txtFILE" id="txtFILE" style="width:100%;resize:none" rows="15"></textarea>
											</div>
											
										</div>
									</div>
								
							</div>
							<div class="col-md-12 text-right"> 
								<button type="button" id="btnTest" class="btn btn-primary">Send Test<i class="icon-arrow-right14 position-right"></i></button>
							</div>
						</form>
						
						
						
					<!-- modal tags -->
					<div id="modal_tags" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<div class="alert alert-info alert-styled-left text-blue-800 content-group">
						                <span class="text-semibold">Explanation of existing tags</span> 
									</div>

									<h6 class="text-semibold">
										<i class="icon-server position-left"></i> <span class="label bg-success-400">[sr]</span> : Server Name 
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class="icon-lan position-left"></i> <span class="label bg-success-400">[ip]</span> : IP 
									</h6>
									<hr>
									
									
									
									<h6 class="text-semibold">
										<i class="icon-earth position-left"></i> <span class="label bg-success-400">[domain]</span> : Domain )
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class="icon-calendar52 position-left"></i> <span class="label bg-success-400">[date]</span> : Current Date
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class=" icon-envelope position-left"></i> <span class="label bg-success-400">[to]</span> : Recipient
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class="icon-list position-left"></i> <span class="label bg-success-400">[file]</span> : Test Multiple
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class="icon-sort-alpha-desc position-left"></i> <span class="label bg-success-400">[RandomC/5]</span> : Characters
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class="icon-sort-numeric-asc position-left"></i> <span class="label bg-success-400">[RandomD/5]</span> : Numbers
									</h6>
									<hr>
									
									
									<h6 class="text-semibold">
										<i class="icon-sort position-left"></i> <span class="label bg-success-400">[RandomCD/5]</span> : Alpha-Numeric
									</h6>
									<hr>

																		
								</div>

								<div class="modal-footer">
									<button class="btn btn-primary" data-dismiss="modal"><i class="icon-cross"></i> Close</button>
								</div>
							</div>
						</div>
					</div>
					<!-- /iconified modal -->
							
					</div>
				</div>
					<!-- /form horizontal -->
				
				<div class="alert alert-warning alert-styled-left" id="divResult">
										<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Warning!</span> Better <a href="#" class="alert-link">check yourself</a>, you're not looking too good.
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
	
	$('#tableData').hide();
	
	$('#divResult').hide();
	
	$('#btnSelect').click(function(){
		var selectedIPS=$('#select').val();
		$('#cmbIPs').empty();
		var server = $('#cmbServers').val();
		$.post
		(
			'selectIPS.php',
			{
				selected : selectedIPS , 
				cmbServers : server
			},
			function(data)
			{
				$('#cmbIPs').html(data);
				$('#cmbIPs').focus();
				selctedVMTAsCount();
			}
		);
		
		
        
    });
	
	$('#btnTest').click(function(){
		
	   $('#divResult').hide();
	   
	   var emailTest = $('#txtEmailTest').val();
	   var returnPath = $('#txtReturnPath').val();
	   var header = $('#txtHeader').val();
	   var body = $('#txtBody').val();
	   //var ips = $('#txtIPS').val();
	   var ips = $('#cmbIPs').val();
	   var file = $('#txtFILE').val();
	   var fraction = $('#fraction').val();
		var delay = $('#delay').val();
		if ($('#rotation').is(':checked')) {
			var rotation = 1;
		}else{
			var rotation = 0;
		}
	   
	   $.post
	   (
			'emailTestGlobal_POST.php',
			{
				txtFILE : file,
				txtEmailTest:emailTest,
				txtReturnPath:returnPath,
				txtHeader:header,
				txtBody:body,
				txtIPS:ips,
				delay:delay,
				fraction,fraction,
				rotation:rotation
			},
			function(data)
			{
				var output = data.split('/');
				alert(output);
				if(output[0].length!=0)
				{
					$('#divResult').html(output[0]).show();
				}
					
			});
	   
	});
	
	
	//Servers :
	$('#cmbServers').change(function()
	{
		$('#cmbIPs').empty();
		var server = $('#cmbServers').val();
		$.post
		(
			'getIPS2.php',
			{cmbServers : server},
			function(data)
			{
				$('#cmbIPs').html(data);
			}
		);
		selctedServersCount();
		selctedVMTAsCount();
	});
	
	
	//VMTAs :
	$('#cmbIPs').change(function()
	{
		selctedVMTAsCount();
	});
	
	
	//Filter Server :
	$('#txtServerFilter').keyup(function()
	{
		var	target_server	=	$('#txtServerFilter').val();
		var cptFoundedServers		=	0;
		var cptNotFoundedServers	=	0;
		$("#cmbServers option").each(function()
		{
			if($(this).text().toLowerCase().indexOf(target_server.toLowerCase()) >= 0)
			{
				$(this).show();
				cptFoundedServers++;
			}
			else
			{
				$(this).hide();
				cptNotFoundedServers++;
			}
		});
		
		if(cptFoundedServers==0)
			$("#cmbServers").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbServers option[value='-1']").remove();
	
		
	});
	
	
	
	//Filter VMTA :
	$('#txtVmtaFilter').keyup(function()
	{
		var	target_vmta	=	$('#txtVmtaFilter').val();
		var cptFoundedVMTAs		=	0;
		var cptNotFoundedVMTAs	=	0;
		$("#cmbIPs option").each(function()
		{
			if($(this).text().toLowerCase().indexOf(target_vmta.toLowerCase()) >= 0)
			{
				$(this).show();
				cptFoundedVMTAs++;
			}
			else
			{
				$(this).hide();
				cptNotFoundedVMTAs++;
			}
		});
		
		if(cptFoundedVMTAs==0)
			$("#cmbIPs").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbIPs option[value='-1']").remove();
		
		
	});
	
	//Server Count :
	function selctedServersCount()
	{
		var nbr_servers	=	$('#cmbServers option:selected').length;
		var str			=	'';
		if(nbr_servers==0)
		{
			str			=	'No Server selected';
		}
		else
		{
			var	plurielS	=	( (nbr_servers==1)?"":"s");
			str				=	nbr_servers+' selected Server'+plurielS;
		}
		$("#txtServerCount").text(str);
	}
	
	
	//VMTA Count :
	function selctedVMTAsCount()
	{
		var nbr_VMTAs	=	$('#cmbIPs option:selected').length;
		var str			=	'';
		if(nbr_VMTAs==0)
		{
			str			=	'No VMTA selected';
		}
		else
		{
			var	plurielS	=	( (nbr_VMTAs==1)?"":"s");
			str				=	nbr_VMTAs+' selected VMTA'+plurielS;
		}
		$("#txtVMTACount").text(str);
	}
	
	
	
	
	
		
		
	</script>
</body>
</html>
