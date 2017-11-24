<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 include('../Includes/bdd.php');
	  
	  $mailer = $_SESSION['id_Employer'];
	  
	  $id_Send = $_GET['id_Send'];
	  $id_Offer_Send = '';
	  $id_ISP_Send = '';
	  $id_Employer_Send = '';
	  $header_Send = '';
	  $body_Send = '';
	  $emailTest_Send = '';
	  $returnPath_Send = '';
	  $IPS_Send = '';
	  $id_From_Send = '';
	  $id_Subject_Send = '';
	  $id_Creative_Send = '';
	  $startFrom_Send  = '';
	  $isAR			  = '';
	  $ARList		  = '';
	  $id_negative    = '';
	  
	  $requete = $bdd->prepare('select * from send where id_Send = ?');
	  $requete->execute(array($id_Send));
	  extract($requete->fetch());
	  
	  if($_SESSION['firstName_Employer'] != "ADMIN")
	  {
		  
		  if($mailer != $id_Employer_Send)
	        header('location:ShowSends.php');
		
	  }
		  
	  
	  
	  $requete = $bdd->prepare('select name_Offer from offer where id_offer = ?');
	  $requete ->execute(array($id_Offer_Send));
	  $row = $requete->fetch();
	  $name_Offer = $row['name_Offer'];
	  
	  
	  
	function	fill_combo_ips_send($p_id_send)
	{
		include('../Includes/bdd.php');
		$result	=	null;
		$ips 	= 	get_ips_send_by_id_send($p_id_send);
		if(!is_null($ips))
		{
			foreach($ips as $addresse_ip)
			{
				$ipExplode = explode('-',$addresse_ip);
				$ipFinal   = $ipExplode[1];
				
				if(count($ipExplode) == 2)
				{
						$requete = $bdd->prepare(
						'
							SELECT 	S.alias_Server,I.id_IP,I.IP_IP,D.name_Domain
							FROM 	server S,ip I,domain D
							WHERE 	S.id_Server		=		I.id_Server_IP
							AND		I.id_Domain_IP	=		D.id_Domain
							AND		I.IP_IP			=		?
							LIMIT	0,1
						');
						$requete->execute(array(trim($ipFinal)));
						$row = $requete->fetch();
						
						$result.='<option selected value='.$addresse_ip.'>'.$row['alias_Server'].' | '.$row['IP_IP'].' | '.$row['name_Domain'].'</option>';
				}
				
				else
				{
					$requete = $bdd->prepare('select v.*,s.alias_Server from vmta v,server s where v.id_Server = s.id_Server and v.ip = ?');
					$requete->execute(array($ipFinal));
					
					while($row = $requete->fetch())
					{
						$result.='<option selected value=vmta-'.$row['ip'].'-'.$row['id_Mailer'].'>'.$row['alias_Server'].' | '.$row['ip'].' | '.$row['domain'].'</option>';
					}
					
				}
			}
		}

		if(!is_null($result))
			echo $result;
	}
	
	function	get_ips_send_by_id_send($p_id_send)
	{
		include('../Includes/bdd.php');
		$result		=	null;
		$requete 	= 	$bdd->prepare(
		'
			SELECT 	S.IPS_Send
			FROM 	send S
			WHERE 	S.id_Send		=		?
		');
		$requete->execute(array($p_id_send));
		$row = $requete->fetch();
		if($row)
		{
			$strIPs	=	trim($row['IPS_Send']);
			if(!empty($strIPs))
				$result	=	explode(PHP_EOL,$strIPs);
			else
				$result	=	null;
		}
		
		return $result;
	}  
	
	function	is_selected_server($p_id_send,$p_id_server)
	{
		include('../Includes/bdd.php');
		$result		=	'';
		
		if(  ($p_id_send>0) and ($p_id_server>0)  )
		{
			$tab_ips_send	=	get_ips_send_by_id_send($p_id_send);
			$clause_IN		=	implode("','", $tab_ips_send);
			$clause_IN 		= 	rtrim($clause_IN, ",");
			$clause_IN='\''.$clause_IN.'\'';
			//echo $clause_IN;
			$requete 		= 	$bdd->prepare
			("
				SELECT 	count(*) as founded
				FROM 	ip I
				WHERE 	I.id_Server_IP	=	?
				AND		I.IP_IP in ($clause_IN)
			");
			$requete->execute(array($p_id_server));
			$row	=	$requete->fetch();
			if($row)
			{
				$is_founded	=	$row['founded'];
				if($is_founded>0)
					$result	=	'selected';
				else
					$result	=	'';
			}
			else
			{
				$result	=	'';
			}
		}
		
		echo $result;
	}
	
	
	//var_dump(fill_combo_ips_send(43));
	//var_dump(get_ips_send_by_id_send(43) );
	//echo is_selected_server($id_Send,9);
	//exit;
	  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $id_Send.'-'.$name_Offer;?></title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="../assets/js/pages/picker_date.js"></script>

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
							<h4><span class="text-semibold">Send</span> - Update Send</h4>
						</div>
						<div class="heading-elements">
							<span class="heading-text text-bold">
								<span class="badge bg-primary">
									<?php echo $id_Send;?>
								</span>  
								
								<span class="label bg-danger">
									<?php echo $name_Offer;?>
								</span>
							</span>
							
							
							
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="#">Send</a></li>
							<li class="active">Update Send</li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<div class="row">
						<div class="col-lg-6">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h2 class="panel-title"><u>From / Subject / Return Path</u></h2>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>
					                		<li><a data-action="reload"></a></li>
											<li>
												<span>
													<i href="#modal_tags" class="icon-help" title="Help" style="cursor:pointer;" role="button" data-toggle="modal"></i>
												</span>
											</li>
										</ul>
				                	</div>
								</div>

				                <div class="panel-body">
				                	<br/>
									<form class="form-horizontal" method="POST" action="updateSend_POST-New.php">
										
										<!-- From -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">From</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<select name="cmbFroms" id="cmbFroms" class="select-clear" data-placeholder="Select From">
																 <?php
																	$requete = $bdd->prepare('select * from froms where id_Offer_From = ?');
																	$requete->execute(array($id_Offer_Send));
																	while($row = $requete->fetch())
																	{
																	   ?> <option value="<?php echo $row['id_From'];?>" <?php echo ($row['id_From'] == $id_From_Send) ? 'selected' : '';?>><?php echo $row['text_From'];?></option><?php
																	} 
																 ?>
																</select>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										
										<!-- Subject -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Subject</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<select name="cmbSubjects" id="cmbSubjects" class="select-clear" data-placeholder="Select Subject">
																 <?php
																	$requete = $bdd->prepare('select * from subjects where id_Offer_Subject = ?');
																	$requete->execute(array($id_Offer_Send));
																	while($row = $requete->fetch())
																	{
																	   ?> <option value="<?php echo $row['id_Subject'];?>" <?php echo ($row['id_Subject'] == $id_Subject_Send) ? 'selected' : '';?>><?php echo $row['text_Subject'];?></option><?php
																	} 
																 ?>
																</select>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										
										<!-- Return Path -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Return Path</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<input type="text text-bold" class="form-control" name="txtReturnPath" id="txtReturnPath" value="<?php echo $returnPath_Send;?>" />
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										
										
										<!-- Negative -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Negative</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<div class="input-group">
																	<select name="cmbNegative" id="cmbNegative" class="select-clear" data-placeholder="Select Negative">
																		<option value="0">Select Negative</option>
																		<?php
																			$idMailer = $_SESSION['id_Employer'];
																			
																			$requete = $bdd->prepare('select * from negative where id_mailer = ?');
																			$requete->execute(array($idMailer));
																			
																			while($row = $requete->fetch())
																			{
																			   ?> <option value="<?php echo $row['id_negative'];?>" <?php if($row['id_negative'] == $id_negative){echo "selected";};?>><?php echo $row['name_negative'];?></option><?php
																			} 
																		?>
																	</select>
																	<div class="input-group-btn">
																		
																		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action 
																			<span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li><a id="reloadNegatives"><i class=" icon-reload-alt"></i>Refresh</a></li>
																			<li><a href="../Negative/uploadNegative.php" target="_blank"><i class="icon-add"></i>Upload Negative</a></li>
																		</ul>
																	</div>
																</div>
															</div>
															
															
															
															
														</div>
													</div>
												</div>
											</div>
										</div>
										
										
										
										<!-- Start From -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Start From</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<input type="number" class="form-control" name="txtStartFrom" id="txtStartFrom" value="<?php echo $startFrom_Send;?>"  />
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
				                        
										
										<!-- Email(s) Test -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Email(s) Test</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<textarea placeholder="one email test per line" class="form-control" name="txtEmailTest" id="txtEmailTest" rows="2" style="resize: none;"><?php echo $emailTest_Send;?></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									
								</div>
				            </div>
						</div>
						<div class="col-lg-6">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h2 class="panel-title"><u>Server / IP / Domain</u></h2>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>
					                		<li><a data-action="reload"></a></li>
											<li>
												<span>
													<i href="#modal_tags" class="icon-help" title="Help" style="cursor:pointer;" role="button" data-toggle="modal"></i>
												</span>
											</li>
										</ul>
				                	</div>
								</div>

				                <div class="panel-body">
				                	<!--<form action="#">-->
										<?php
											$id_Isp = $_SESSION['id_Isp_Employer'];
											$requete = $bdd->query('select id_Server,alias_Server from server where isActive_Server = 1');
										?>	
										<div class="row">	
											<div class="col-lg-4">
												<div class="form-group">
													<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags">
														<span class="label bg-primary-400">Server</span>
													</label>
													<input type="text" id="txtServerFilter" name="txtServerFilter"  class="form-control" placeholder="Filter server">
												</div>
											</div>
											<div class="col-lg-8">
												<div class="form-group">
													<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags">
														<span class="label bg-primary-400">VMTA</span>
													</label>
													<input type="text" id="txtVmtaFilter" name="txtVmtaFilter"  class="form-control" placeholder="Filter vmta">
												</div>
											</div>
										</div>	
										
										
										
										<div class="row">		
											<div class="col-lg-4">
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
																	<option <?php is_selected_server($id_Send,$row['id_Server']); ?> value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>
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
																	<option <?php is_selected_server($id_Send,$row['id_Server']); ?> value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>
																	<?php
																}
																
															}
														  
														}
													  ?>
													</select>
												</div>
											</div>
											<div class="col-lg-8">
												<div class="form-group">
													<select id="cmbIPs" name="cmbIPs[]" multiple="multiple" class="form-control"  size="15">
														<?php
															fill_combo_ips_send($id_Send);
														?>
													</select>
												</div>
											</div>
											
										</div>	
									
										<div class="row">
											<div class="col-lg-4">
												<div class="form-group">
													<span class="label label-block label-primary text-left" id="txtServerCount">No  selected server</span>
												</div>
											</div>

											<div class="col-lg-8">
												<div class="form-group">
													<span class="label label-block label-primary text-left" id="txtVMTACount" >No selected VMTA</span>
												</div>
											</div>
										</div>	
									<!--</form>-->
				                </div>
				            </div>
						</div>
					</div>
					
					
					<!-- Header & Body -->
					<div class="row">
						<div class="col-lg-6">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h2 class="panel-title"><u>Header</u></h2>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>
					                		<li><a data-action="reload"></a></li>
											<li>
												<span>
													<i href="#modal_tags" class="icon-help" title="Help" style="cursor:pointer;" role="button" data-toggle="modal"></i>
												</span>
											</li>
										</ul>
				                	</div>
								</div>
								<div class="panel-body">
									<!-- Header -->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<div class="col-lg-12">
													<div class="row">
														<div class="col-md-12">
															<textarea class="form-control" name="txtHeader" id="txtHeader" style="resize:none;" rows="10"><?php echo $header_Send;?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h2 class="panel-title"><u>Body</u></h2>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>
					                		<li><a data-action="reload"></a></li>
											<li>
												<span>
													<i href="#mdl-body_preview" class="icon-file-eye" title="Preview Email" style="cursor:pointer;" role="button" data-toggle="modal"></i>
												</span>
											</li>
											<li>
												<span>
													<i href="#modal_tags" class="icon-help" title="Help" style="cursor:pointer;" role="button" data-toggle="modal"></i>
												</span>
											</li>
										</ul>
				                	</div>
								</div>
								<div class="panel-body">
									<!-- Body -->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<div class="col-lg-12">
													<div class="row">
														<div class="col-md-12">
															<textarea class="form-control" name="txtBody" id="txtBody" style="resize:none;" rows="10"><?php echo htmlspecialchars($body_Send);?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
				<!-- Auto Response -->	
				<?php if(  ($_SESSION['id_Employer']==0)  or  ($_SESSION['id_Employer']==18)  or ($_SESSION['id_Employer']==21)  or($_SESSION['id_Employer']==1)  or  ($_SESSION['id_Employer']==5) or  ($_SESSION['id_Employer']==9)  ):?>	
					<!-- Extra -->
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h2 class="panel-title"><u>Auto Response</u></h2>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>
					                		<li><a data-action="reload"></a></li>
											<li>
												<span>
													<i href="#modal_tags" class="icon-help" title="Help" style="cursor:pointer;" role="button" data-toggle="modal"></i>
												</span>
											</li>
										</ul>
				                	</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<div class="col-lg-12">
													<div class="row">
														<div class="col-md-12">
															Activate Auto Response Settings ?
															<input type="checkbox" name="chkAR" id="chkAR" <?php echo ($isAR == 1) ? 'checked' : '';?> value="<?php echo ($isAR == 1) ? '1' : '0';?>"/>
															<textarea class="form-control" name="txtARList" id="txtARList" placeholder='Put your list of forwarding emails here'style="resize:none;" rows="8"><?php echo $ARList;?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif;?>	
					
					
				
				
				
				<div class="text-right">
		            <input type="hidden" id="id_Send" name="id_Send" value="<?php echo $id_Send;?>"/>
					<button type="button" id="btnTestMail" class="btn btn-primary">Send Test Mail
						<i class="icon-person position-right"></i>
					</button>
					<button type="submit" class="btn btn-primary">Update
						<i class="icon-pencil3 position-right"></i>
					</button>
				</div>
				
				
			</form>
					
					
					
					
			<!-- Modal Preview Body -->
			<div id="mdl-body_preview" class="modal fade">
				<div class="modal-dialog modal-full">
					<div class="modal-content">
						<div class="modal-header bg-info">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<div class="modal-body">
							<div id="bodyPreview">
								
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal Preview Body -->
			
			
			<!-- modal tags -->
			<div id="modal_tags" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<div class="alert alert-info alert-styled-left text-blue-800 content-group">
								<span class="text-semibold">Explanation of existing tags</span> 
							</div>

							<h6 class="text-semibold">
								<i class="icon-server position-left"></i> [sr] : Server Name 
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class="icon-lan position-left"></i> [ip] : IP 
							</h6>
							<hr>
							
							
							
							<h6 class="text-semibold">
								<i class="icon-earth position-left"></i> [domain] : Domain
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class="icon-calendar52 position-left"></i> [date] : Current Date
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class=" icon-envelope position-left"></i> [to] : Recipient
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class="icon-menu6 position-left"></i> [nega] : Negative
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class="icon-sort-alpha-desc position-left"></i> [RandomC/5] : Characters
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class="icon-sort-numeric-asc position-left"></i> [RandomD/5] : Numbers
							</h6>
							<hr>
							
							
							<h6 class="text-semibold">
								<i class="icon-sort position-left"></i> [RandomCD/5] : Alpha-Numeric
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
	
	//$('#tableData').hide();
	
	$('.carousel').carousel({
  interval: 0
		});
		
		$('#cmbSponsors').change(function(){
		   $('#cmbVerticals').val('-1');
		   $('#cmbFroms').html('');
		   $('#cmbSubjects').html('');
		   $('.carousel').html('');
		   var idSponsor = $(this).val();
		   $.post('ajax.php',{type:'sponsor',id_Sponsor:idSponsor},function(data){
		      $('#cmbOffers').html(data);
		   });
		});
		
		$('#cmbVerticals').change(function(){
		   $('#cmbFroms').html('');
		   $('#cmbSubjects').html('');
		   $('.carousel').html('');
		   var idVertical = $(this).val();
		   var idSponsor = $('#cmbSponsors').val();
		   
		   $.post('ajax.php',{type:'vertical',id_Sponsor:idSponsor,id_Vertical:idVertical},function(data){
		      $('#cmbOffers').html(data);
		   });
		});
		
		
		$('#cmbOffers').change(function(){
		   var idOffer = $(this).val();
		   
		   $.post('ajax.php',{type:'from',id_Offer:idOffer},function(data){
		      $('#cmbFroms').html(data);
		   });
		   
		    $.post('ajax.php',{type:'subject',id_Offer:idOffer},function(data){
		      $('#cmbSubjects').html(data);
		   });
		   
		   $.post('ajax.php',{type:'creative',id_Offer:idOffer},function(data){
		      $('.carousel').html(data);
			  idCreative = $('.active:eq(1)').children().attr('id');
		      $('#idCreative').val(idCreative);
		   });
		   
		    
		   
		});
		
		
		$('#cmbIsps').change(function(){
		   var idIsp = $(this).val();
		   $.post('ajax.php',{type:'isp',id_Isp:idIsp},function(data){
		      $('#tableData').html(data);
			  $('#tableData').show();
		   });
		});
		
		
		
		
		$('#carousel-example-generic').on('slid.bs.carousel', function () {
		 idCreative = $('.active:eq(1)').children().attr('id');
		 $('#idCreative').val(idCreative);
		});

		
		$('#cmbFroms').change(function(){
		  var from = $("#cmbFroms option:selected").text();
		  var header = $('#txtHeader').val();
		  var newHeader = '';
		  var explode = header.split('\n');
		  
		  
		  for(var i=0;i<explode.length;i++)
		  {
		     var parametrs = explode[i].split(':');
			 if(parametrs[0]=='fromName')
			 {
			    if(i<explode.length-1)
				 newHeader+='fromName:'+from+'\n';
				else
				newHeader+='fromName:'+from;
			 }
			 else
			 {
			    if(i<explode.length-1)
				 newHeader+=explode[i]+'\n';
				else
				newHeader+=explode[i];
			 }
		  }
		  $('#txtHeader').val(newHeader);
		});
		
		
		
		$('#cmbSubjects').change(function(){
		  var subject = $("#cmbSubjects option:selected").text();
		  var header = $('#txtHeader').val();
		  var newHeader = '';
		  var explode = header.split('\n');
		  
		  
		  for(var i=0;i<explode.length;i++)
		  {
		     var parametrs = explode[i].split(':');
			 if(parametrs[0]=='subject')
			 {
			    if(i<explode.length-1)
				 newHeader+='subject:'+subject+'\n';
				else
				newHeader+='subject:'+subject;
			 }
			 else
			 {
			    if(i<explode.length-1)
				 newHeader+=explode[i]+'\n';
				else
				newHeader+=explode[i];
			 }
		  }
		  $('#txtHeader').val(newHeader);
		});
		
		String.prototype.replaceAll = function(search, replacement) {
			var target = this;
			return target.split(search).join(replacement);
		};

		
		//Body Preview :
		$('#txtBody').keyup(function()
		{
		   $('#bodyPreview').empty();
		   var codeHTML =	$(this).val();
		   var ip 		= 	$('#cmbIPs option:selected').first().text().split(' | ')[1];
		   codeHTML 	= 	codeHTML.replaceAll('[domain]',ip);
		   $('#bodyPreview').html(codeHTML);
		});
		
		
		//Send Test Mail :
		$('#btnTestMail').click(function(){
		
        var idSend = <?php echo $id_Send;?>;
		var idFrom = $('#cmbFroms').val();
		var idSubject = $('#cmbSubjects').val();
		var emailTest = $('#txtEmailTest').val();
		var returnPath = $('#txtReturnPath').val();
		var header = $('#txtHeader').val();
		var body = $('#txtBody').val();
		var ips = $('#cmbIPs').val();
		var chkAR = $('#chkAR').val();
		var txtARList = $('#txtARList').val();
		var idnegative = $('#cmbNegative').val();
		
			$.post('SendTestInside-New.php',{id_Send:idSend,cmbFroms:idFrom,cmbSubjects:idSubject,txtEmailTest:emailTest,txtReturnPath:returnPath,txtHeader:header,txtBody:body,'cmbIPs':ips,chkAR:chkAR,txtARList:txtARList,idnegative:idnegative},function(data)
			{
			    //alert(data);
			});
		});
		
		
		var isAR = <?php echo $isAR;?>;
		if(isAR == 0)
		//$('#txtARList').hide();
		
		$('#chkAR').click(function(){
		   if($(this).is(":checked"))
		   {
		     //$('#txtARList').show();
			 $('#chkAR').val(1);
		   }
		   else
		   {
		     //$('#txtARList').hide();
			 $('#chkAR').val(0);
		   }
		});
		
		
		$('#reloadNegatives').click(function(){
			
			$.post('reloadNegative.php',{},function(data){
				
				$('#cmbNegative').html('').html(data);
				
			});
		});
	
	
	//Servers :
	$('#cmbServers').change(function()
	{
		$('#cmbIPs').empty();
		var server = $('#cmbServers').val();
		$.post
		(
			'getIPS2-New',
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
		$('#txtBody').keyup();
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

<?php 
	
	

?>