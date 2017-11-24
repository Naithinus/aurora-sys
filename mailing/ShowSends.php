<?php
set_time_limit(0);
date_default_timezone_set('UTC');
ob_start();
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" 
      type="image/png" 
      href="http://<?php echo $_SERVER['HTTP_HOST'];?>/aurora/icon.png">
	<title>Aurora - Mailing</title>
	
	<?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">  
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
		<link rel="stylesheet" type="text/css" href="./css/common.css" />

	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	
	<style>
	#div {
		display: block;
		overflow:auto;
		overflow-x:hidden
	}
	.ranges li:hover
	{
		color:black;
	}
	div.tab {
		overflow: hidden;
		background-color:#fafafa;
		<!-- border: 1px solid #ccc;-->
		
	}
	div.tab button:first-child {
		border-top-left-radius: 0px;
	}
	div.tab button {
		background-color: #f5f5f5;
		border-top-left-radius: 8px;
		border-top-right-radius: 8px;
		float: left;
		border: none;
		outline: none;
		cursor: pointer;
		padding: 10px 10px;
		margin-right: 10px;
		transition: 0.3s;
		font-size: 14px;
	}

	div.tab button:hover {
		background-color: #AAAAAA;
		color:#fff
	}

	div.tab button.active {
		background-color: #AAAAAA;
		color:#fff
	}

	.tabcontent {
		display: block;
		height:87vh;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-top: none;
		overflow:auto;
		overflow-x:hidden
	}


	td {
		border-top: thin solid #719ba7;border-bottom: thin solid #719ba7;
	}
	td:first-child{
		border-left: thin solid #719ba7;border-bottom: thin solid #719ba7;
	}
	td:last-child{
		border-right: thin solid #719ba7;border-bottom: thin solid #719ba7;
	}
	</style>
	
</head>
<body style=" position:fixed;top:0;bottom:0;left:0;right:0;background-color:#AAAAAA" class="sidebar-xs">
<?php
	include('../Includes/navbar.php'); ?>

<div class="page-container" id="page-container">
	<div class="page-content">	<?php 
		if($_SESSION['type_Employer']!="Mailer"){
			Include('../Includes/sidebar.php');
		}			?>
		<div class="content-wrapper">
			<div class="tab">
				<button class="tablinks active" onclick="tab(event, 'Offers')"><i style="" class="icon-gift"></i> Offers</button>
				<button class="tablinks" onclick="tab(event, 'PMTA1')"><i style="color:#FF851B" class="icon-server"></i> PMTA1</button>
				<button class="tablinks" onclick="tab(event, 'PMTA2')"><i style="color:#FF851B" class="icon-server"></i> PMTA2</button>
				<button class="tablinks" onclick="tab(event, 'Process')"><i style="color:#85144b" class="fa fa-tasks"></i> Send Process</button>
				<!--<button class="tablinks" onclick="tab(event, 'Test')"><i class="icon-mail5"></i> Email Test</button>
				<button class="tablinks" onclick="tab(event, 'WarmUP')"><i style="color:#FF4136" class="icon-fire"></i> Warm Up</button>-->
				<button class="tablinks" onclick="tab(event, 'Prepare')"><i style="color:#2ECC40" class="icon-box-add"></i> Prepare Offer</button>
				<button class="tablinks" onclick="tab(event, 'Linode')"><i style="color:#0074D9" class="fa fa-hdd-o"></i> Linode</button>
			</div>
			
			<!-- OFFER TAB -->
			<div id="Offers" class="tabcontent">
				<div class="panel panel-flat">
					<div class="panel-body" height="100%">
						<a class="btn border-info-800 text-info-800  btn-rounded btn-icon valign-text-bottom btnRefresh" title="Refresh"><div class="divLoading"><i class="icon-loop4"></i></div></a>					
						<!-- <span style="font-size:20px" class="label bg-danger-400">Testing! Don't send!</span>-->
						<div class="form-group">				
							<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 30%">
								<i class="icon-calendar3"></i>  <span></span> <b class="caret"></b>
							</div>
						</div><br/>
						<div id="sends" height="100%">
							<center> <img src="loadingg.gif" id="loading"/> </center>
							<table class="table datatable-basic display" id="SendList" style="font-size:14px">
								<thead>
									<tr>
										<th>ID</th>
										<th>Offer</th>
										<th>ISP</th>
										<th>List</th>
										<th>Fraction</th>
										<th>Seed</th>
										<th>X-Delay</th>
										<th>Count</th>
										<th>Send</th>
										<th>Edit</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody style="background-color: #fbfbfb">	
								<div id="Content"><?php
									include('../Includes/bdd.php');
									$idProcess=array();
									$mailerLastName = $_SESSION['lastName_Employer']; 
									$selectProcess=$bdd->query("select send_id from process where user='$mailerLastName'") or die (print_r($bdd->errorInfo()));
									while($line=$selectProcess->fetch()){
										$idProcess[$line["send_id"]][]=$line["send_id"];
									}
									$requete = $bdd->prepare("select o.name_Offer, i.name_isp, s.* from send s , offer o , isp i where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp 
															and s.id_Employer_Send=? and date(s.dateCreation) between (CURRENT_DATE() - INTERVAL 1 WEEK) and CURRENT_DATE() order by s.id_Send desc");
									$requete->execute(array($_SESSION['id_Employer']));
									while($row = $requete->fetch())
									{
										$idS = $row['id_Send'];
										$subRequete = $bdd->prepare('select l.name_List,tl.name_TypeList from sendlist sl , list l , typelist tl where sl.id_List = l.id_List
																	and l.id_Type_List = tl.id_TypeList and sl.id_Send = ?');
										$subRequete->execute(array($idS));								   
										$data = $subRequete->fetchAll();
										if(count($data)>1)
											$listName = 'Mixed';
										else if(count($data)==1)
											$listName = $data[0][0].'-'.$data[0][1];
										else
										{
											if($row['id_ISP_Send'] == 3)
												$listName = 'Sender';
											else
												$listName = $row['extra'];
										}
										
										$tableName = $mailerLastName.$row['id_Send'];
										$requeteCount = $bdd->query("select count(*) from $tableName");
										$countList = 0;
										if($requeteCount)
										{
											$rowCount = $requeteCount->fetch();
											$countList = 0;
											if(($rowCount[0] - $row['startFrom_Send']) > 0)
												$countList = $rowCount[0] - $row['startFrom_Send'];
										}
										$sendInfo=$bdd->query('Select IPS_Send from send where id_send="'.$row["id_Send"].'"');
										$c=0;
										while ($r=$sendInfo->fetch()){
											if ($r[0]!=NULL){
											   $vmtas=explode(PHP_EOL,$r[0]);
											   $c=count($vmtas);
											}
										}											?>
										<tr>
											<td id="<?php echo $row["id_Send"]?>"><?php
												echo $row["id_Send"];
												if(isset($idProcess[$row["id_Send"]])){
													if(in_array($row["id_Send"],$idProcess[$row["id_Send"]])){
														$countSend=count($idProcess[$row["id_Send"]]);
														if($countSend==1){
															echo "<center><i title='$countSend Sending!' style=color:orange class='fa fa-exclamation-triangle' aria-hidden=true></i></center>";
														}else{
															echo "<center><i title='$countSend Sending!' style=color:red class='fa fa-exclamation-triangle' aria-hidden=true></i></center>";
														}
													}
												}?>
											</td>
											<td><?php echo $row["name_Offer"];?></td>
											<td><?php echo $row["name_isp"];?></td>
											<td><?php echo $listName;?></td>
											<td><input type="text" class="form-control" style="width:100%;"/></td>
											<td><input type="text" class="form-control" style="width:100%;"/></td>
											<td><input type="text" class="form-control" style="width:100%;"/></td>
											<td><span class="label bg-success-400"><?php echo $countList;?></span></td>
											<td>
												<a style="margin:1px" class="btn border-primary text-primary  btn-icon btn-xs valign-text-bottom btnSend" title="Send" id="<?php echo $row["id_Send"];?>"><div class="divLoading"><i class="icon-target2"></i></div></a>
												<a style="margin:1px" class="btn border-primary-300 text-primary-300  btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="<?php echo $row["id_Send"];?>"><i class="icon-person"></i></a>
												<a style="margin:1px" class="btn border-success text-success btn-icon btn-xs valign-text-bottom btnStats" title="Stats" id="<?php echo $row["id_Send"];?>" data-toggle="modal" data-target="#modal_stats"><i class="icon-stats-dots"></i></a>
											</td>
											<td>
												<a style="margin:1px" class="btn border-slate text-slate  btn-icon btn-xs valign-text-bottom btnEditIPS" title="<?php echo "$c VMTA\n"; foreach($vmtas as $v){ echo $v."\n";}?>" data-toggle="modal" data-target="#modal_form_inline"><i class="icon-more2"></i></a>
												<a style="margin:1px" class="btn border-warning text-warning btn-icon btn-xs valign-text-bottom btnUpdate" title="Update Send" id="<?php echo $row["id_Send"];?>" data-toggle="modal" data-target="#modal_UpdateSend"><i class=" icon-pencil"></i></a>
											</td>
											<td>
												<!-- <a style="margin:1px" class="btn border-grey-400 text-grey-400  btn-icon btn-xs valign-text-bottom btnCopy" title="Copy Send" data-toggle="modal" data-target="#modal_form_copy"><i class="icon-copy3"></i></a>-->
												<a style="margin:1px" class="btn border-danger-800 text-danger-800  btn-icon btn-xs valign-text-bottom btnDelete" title="Delete Send" id="<?php echo $row["id_Send"];?>" data-toggle="modal" data-target=""><i class="icon-trash"></i></a>
											</td>
										</tr>
									</div><?php
									}	?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- PMTA TAB -->
			<div id="PMTA1" class="tabcontent">
				<div class="panel panel-flat">
					<div class="content" style="padding:10px">
						<form class="form-horizontal">
							<fieldset class="content-group">
								<div class="form-group">
			                        	<label class="control-label col-lg-1">Server</label>
			                        	<div class="col-lg-2">
				                            <select name="cmbServers"  id="cmbServers" class="form-control">
                                               <option value="-1" selected disabled>Select a Server</option> <?php
											$id_Isp = $_SESSION['id_Isp_Employer'];
											$requete = $bdd->query('select s.alias_Server ,s.id_Server,i.IP_IP from server s , ip i where s.id_IP_Server = i.id_IP and isActive_Server = 1');
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
													{	?>
														<option class="form-control" value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option> <?php
													}
												}
												else
												{
													$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
													$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
													$SubrowMailer = $requeteMailer->fetch();
													if($SubrowMailer)
													{	?>
														<option class="form-control" value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option> <?php
													}	
												}
											}	?>
				                            </select>
			                            </div>
									<label class="control-label col-lg-1">Queue Name</label>
									<div class="col-lg-2">
										<input type="text" id="txtQueueName" value="*/*" class="form-control"/>
									</div>
								</div>
								<div class="form-group" style="padding-bottom:0px">
									<div class="col-lg-6" align="center">
										<button type="button" id="btnDelete" class="btn btn-primary">Delete<i class="icon-trash-alt position-right"></i></button>
										<button type="button" id="btnPause" class="btn btn-primary">Pause<i class="icon-pause2 position-right"></i></button>
										<button type="button" id="btnResume" class="btn btn-primary">Resume<i class="icon-play4 position-right"></i></button>
										<button type="button" id="btnSchedule" class="btn btn-primary">Schedule<i class="icon-calendar3 position-right"></i></button>
										<button type="button" id="btnReset" class="btn btn-primary">Reset<i class="icon-reset position-right"></i></button>
									</div>
								</div>
							</fieldset>
						</form>		
					</div>
					<div class="panel-body" id="pnl" style="margin:0;padding:10px" >
						<iframe src="" id="framePMTA" align="center" style="display: block;width:100%;height:75vh;"></iframe>
					</div>
				</div>		
			</div>
			<!-- PMTA2 -->
			<div id="PMTA2" class="tabcontent">
				<div class="panel panel-flat">
					<div class="content" style="padding:10px">
						<form class="form-horizontal">
							<fieldset class="content-group">
								<div class="form-group">
			                        	<label class="control-label col-lg-1">Server</label>
			                        	<div class="col-lg-2">
				                            <select name="cmbServers2"  id="cmbServers2" class="form-control">
                                               <option value="-1" selected disabled>Select a Server</option> <?php
											$id_Isp = $_SESSION['id_Isp_Employer'];
											$requete = $bdd->query('select s.alias_Server ,s.id_Server,i.IP_IP from server s , ip i where s.id_IP_Server = i.id_IP and isActive_Server = 1');
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
													{	?>
														<option class="form-control" value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option> <?php
													}
												}
												else
												{
													$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
													$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
													$SubrowMailer = $requeteMailer->fetch();
													if($SubrowMailer)
													{	?>
														<option class="form-control" value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option> <?php
													}	
												}
											}	?>
				                            </select>
			                            </div>
									<label class="control-label col-lg-1">Queue Name</label>
									<div class="col-lg-2">
										<input type="text" id="txtQueueName2" value="*/*" class="form-control"/>
									</div>
								</div>
								<div class="form-group" style="padding-bottom:0px">
									<div class="col-lg-6" align="center">
										<button type="button" id="btnDelete2" class="btn btn-primary">Delete<i class="icon-trash-alt position-right"></i></button>
										<button type="button" id="btnPause2" class="btn btn-primary">Pause<i class="icon-pause2 position-right"></i></button>
										<button type="button" id="btnResume2" class="btn btn-primary">Resume<i class="icon-play4 position-right"></i></button>
										<button type="button" id="btnSchedule2" class="btn btn-primary">Schedule<i class="icon-calendar3 position-right"></i></button>
										<button type="button" id="btnReset2" class="btn btn-primary">Reset<i class="icon-reset position-right"></i></button>
									</div>
								</div>
							</fieldset>
						</form>		
					</div>
					<div class="panel-body" id="pnl2" style="margin:0;padding:10px" >
						<iframe src="" id="framePMTA2" align="center" style="display: block;width:100%;height:75vh;"></iframe>
					</div>
				</div>		
			</div>
			<!-- PROCESS TAB -->
			<div id="Process" class="tabcontent">
				<div class="panel panel-flat">
					<div class="panel-body">
						<div id="processes">
						<a class="btn border-info-800 text-info-800  btn-rounded btn-icon valign-text-bottom btnRefreshProcess" title="Refresh"><div class="divLoading"><i class="icon-loop4"></i></div></a>					
							<table class="table datatable-basic" id="ProcessList" style="font-size:14px">
								<thead>
									<tr>
										<th>Offer</th>
										<th>host</th>
										<th>Date</th>
										<th>Stop</th>
									</tr>
								</thead>
								<tbody style="background-color: #fbfbfb">
									<div id="content"><?php
										include('../Includes/bdd.php');
										$requete = $bdd->query("select * from process where user='$mailerLastName'");
										while($line=$requete->fetch()){
											$process=trim($line["pid"])."-".trim($line["host"])."-".trim($line["send_id"])."-".trim($line["user"])?>
											<tr>
												<td><?php echo $line["send_id"]?></td>
												<td><?php echo $line["host"]?></td>
												<td><?php echo $line["process_date"]?></td>
												<td>
													<a id="<?php echo $process?>"class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnStopProcess" title="Stop Send" data-toggle="modal" data-target=""><i class="icon-pause"></i></a>
												</td>
											</tr>
											<?php
										}?>
									</div>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- TEST TAB -->
			<div id="Test" class="tabcontent">
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
						</div>	<?php
						$id_Isp = $_SESSION['id_Isp_Employer'];
						$requete = $bdd->query('select id_Server,alias_Server from server');	?>	
						<div class="panel col-md-7">
							<div class="panel-body">
								<div class="form-group">
									<div class="col-lg-3">
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Server</span></label>
											<input type="text" id="txtServerFilterTest" name="txtServerFilter"  class="form-control" placeholder="Filter server">
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">SELECT IPS</span></label>
											<input type="text" id="txtVmtaFilterTest" name="txtVmtaFilter"  class="form-control" placeholder="Filter vmta">
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
											<select id="cmbServersTest" name="cmbServers[]" multiple="multiple" class="form-control" size="15">	<?php
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
														{	?>
															<option value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>	<?php
														}
													}
													else
													{	
														$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
														$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
														$SubrowMailer = $requeteMailer->fetch();
														if($SubrowMailer)
														{	?>
															<option value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>	<?php
														}
													}
												} ?>
											</select>
										</div>
									</div>
									<div class="col-lg-5">
										<div class="form-group">
											<select id="cmbIPsTest" style="overflow-x:auto" name="cmbIPs[]" multiple="multiple" class="form-control"  size="15"></select>
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
						<button type="button" id="btnTestEmail" class="btn btn-primary">Send Test<i class="icon-arrow-right14 position-right"></i></button>
					</div>
				</form>
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
								<h6 class="text-semibold">
									<i class="icon-sort position-left"></i> <span class="label bg-success-400">[RandomCDM/5]</span> : Alpha-Numeric (Upper-Lower case)
								</h6>
								<hr>
								<h6 class="text-semibold">
									<i class="icon-sort position-left"></i> <span class="label bg-success-400">[RandomCM/5]</span> : Characters (Upper-Lower case)
								</h6>
								<hr>									
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal"><i class="icon-cross"></i> Close</button>
							</div>
						</div>
					</div>
				</div>							
			</div>
			<!-- WARMUP TAB -->
			<div id="WarmUP" class="tabcontent">
				<div class="panel panel-flat">
					    <div class="panel-body">
							<form class="form-horizontal" method="POST" id="formEmailTest">
							<div class="col-md-12">
								<div class="panel col-md-5">
									<div class="panel-body">
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Warm UP List:</span></label>
											<textarea class="form-control" name="txtEmailTest" id="txtEmailTest" style="resize:none" rows="14"></textarea>
										</div>
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Return Path:</span></label>
											<input type="text" class="form-control" name="txtReturnPath" id="txtReturnPath" value="[RANDOMC/10]@[domain]"/>
										</div>
									</div>
								</div>	<?php
									$id_Isp = $_SESSION['id_Isp_Employer'];
									$requete = $bdd->query('select id_Server,alias_Server from server');?>
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
													<select id="cmbServers" name="cmbServers[]" multiple="multiple" class="form-control" size="15"> <?php
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
																{	?>
																	<option value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option><?php
																}
															}
															else
															{
																$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
																$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
																$SubrowMailer = $requeteMailer->fetch();
																if($SubrowMailer)
																{?>
																	<option value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option><?php
																}
															}
														} ?>
													</select>
												</div>
											</div>
											<div class="col-lg-5">
												<div class="form-group">
													<select id="cmbIPs" style="overflow-x:auto" name="cmbIPs[]" multiple="multiple" class="form-control"  size="15"></select>
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
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="panel col-md-5">
									<div class="panel-body">
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Header:</span></label>
											<textarea class="form-control text-size-large" name="txtHeader" id="txtHeader" style="width:100%;resize:none;" rows="15"><?php echo "fromName:[sr]\nfromEmail: <contact@[domain]>\nsubject:[ip]\ndate:[date]\nto:[to]\nxid:[xid]\nreply-to:<reply@[domain]></textarea>";?>
										</div>
									</div>
								</div>
								<div class="panel col-md-7">
									<div class="panel-body">
										<div class="form-group">
											<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags"><span class="label bg-success-400">Body: </span></label>
											<textarea class="form-control text-size-large" name="txtBody" id="txtBody" style="width:100%;resize:none;" rows="15"></textarea>
										</div>
									</div>
								</div>	
							</div>
							<div class="col-md-12 text-right"> 
								<button type="button" id="btnTest" class="btn btn-primary">Send Test<i class="icon-arrow-right14 position-right"></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- PREPARE TAB -->
			<div id="Prepare" class="tabcontent">
				<div class="panel panel-flat">
					<div class="panel-body">
						<form class="form-horizontal" method="POST" id="SendForm" action="prepareSendPost.php">
							<fieldset class="content-group">
								<div class="form-group">
									<label class="control-label col-lg-2"></label>
									<table style="font-size:14px">
										<tr>
											<td style="padding-right:50px;padding-bottom:50px;;padding-bottom:50px;border:none;">
												<span class="label bg-success heading-text">Sponsor</span><br/>
												<select name="cmbSponsors" id="cmbSponsors" class="select-clear" data-placeholder="Select Sponsor" style="width:200px;font-size:14px">
													<option value="-1">Select Sponsor</option>
													<?php
													$requete = $bdd->query('select * from sponsor where isActive_Sponsor = 1');
													while($row = $requete->fetch())
													{
													?> <option value="<?php echo $row['id_Sponsor'];?>"><?php echo $row['name_Sponsor'];?></option><?php
													} 
													?>
												</select>
											</td>
										<td style="padding-right:50px;padding-bottom:50px;border:none;">
											<span class="label bg-success heading-text">Vertical</span><br/>
											<select name="cmbVerticals" id="cmbVerticals" class="select-clear" data-placeholder="Select Vertical" style="width:200px;">
												<option value="-1">Select Vertical</option>
												<?php
												$requete = $bdd->query('select * from vertical');
												while($row = $requete->fetch())
												{
												?> <option value="<?php echo $row['id_Vertical'];?>"><?php echo $row['name_Vertical'];?></option><?php
												} 
												?>
											</select>
										</td>
										<td style="padding-right:50px;padding-bottom:50px;border:none;">
											<span class="label bg-success heading-text">Offer</span><br/>
											<select name="cmbOffers" id="cmbOffers" class="select-clear" data-placeholder="Select Offer" style="width:200px;">
												<option value="-1">Select Offer</option>
											</select>
										</td>
										</tr>
										<tr>
											<td style="padding-right:50px;padding-bottom:50px;border:none;">
												<span class="label bg-success heading-text">From</span><br/>
												<select name="cmbFroms" id="cmbFroms" class="select-clear" data-placeholder="Select From" style="width:200px;">
													<option value="-1">Select From</option>
												</select>
											</td>
											<td style="padding-right:50px;padding-bottom:50px;border:none;">
												<span class="label bg-success heading-text">Subject</span><br/>
												<select name="cmbSubjects" id="cmbSubjects" class="select-clear" data-placeholder="Select Subject" style="width:200px;">
													<option value="-1">Select Subject</option>
												</select>
											</td>
											<td style="padding-right:50px;padding-bottom:50px;border:none;">
												<span class="label bg-success heading-text">TARGET</span><br/>
												<select name="cmbTarget" id="cmbTarget" class="select-clear" data-placeholder="Select Target" style="width:200px;">
													<option value="-1">Select Criteria...</option>
													<option value="1">Openers Vertical</option>
													<option value="2">Clickers Vertical</option>
												</select>
											</td>
										</tr>
										<tr>
											<td style="padding-right:50px;padding-bottom:50px;border:none;">
												<span class="label bg-success heading-text">ISP</span><br/>
												<select name="cmbIsps" id="cmbIsps" class="select-clear" data-placeholder="Select ISP" style="width:200px;">
													<option value="-1">Select ISP</option>
														<?php
														$requete = $bdd->query('select * from isp');
														while($row = $requete->fetch())
														{
														?> <option value="<?php echo $row['id_Isp'];?>"><?php echo $row['name_isp'];?>
													</option><?php
														} ?>
												</select>
											</td>
											<td style="padding-right:50px;padding-bottom:50px;border:none;">
												<span class="label bg-success heading-text">Country</span><br/>
												<select name="cmbCountry" id="cmbCountry" class="select-clear" data-placeholder="Select Country" style="width:200px;">
													<option value="-1">Select Country</option>
													<?php
													$requete = $bdd->query('select * from country');
													while($row = $requete->fetch())
													{
													?> <option value="<?php echo $row['id_Country'];?>"><?php echo $row['name_Country'];?></option><?php
													} 
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="padding-bottom:50px;border:none">
												<input type="checkbox" name="chkAR" id="chkAR"/> <span class="label bg-success heading-text">Auto Response</span><br/>
												<textarea class="form-control" name="txtARList" id="txtARList" style="width:700px;height:300px;" rows="8"></textarea>
											</td>
										</tr>
									</table>
								</div>
								<center>
									<div id="divTotalCount" class="bg-blue" style="border-bottom:4px solid orange;font-size:14px">
											<h3 style="display:inline-block;">Total Mixed : </h3><h3 style="position:relative;left:50px;display:inline-block;" id="spanTotalMixed">0</h3>
										</div>
									</center>
									<legend class="text-bold">LISTS</legend>
									<center>					
										<table id="tableData" class="table table-bordered table-striped" style="font-size:14px">
										</table>
									</center>
								<legend class="text-bold"></legend>	
								
								<div class="form-group">
									<label class="control-label col-lg-2"></label>								
									<table >
										<tr>
											<td style="padding-right:50px;padding-bottom:30px;border:none">
												<!-- <span class="label bg-success heading-text">Email Test</span><br/>-->
												<input type="hidden" class="form-control" name="txtEmailTest" id="txtEmailTest"/>
											</td>
											<td style="padding-right:50px;padding-bottom:30px;border:none">
												<input type="hidden" class="form-control" name="txtReturnPath" id="txtReturnPath" value="return@[domain]"/>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="padding-right:50px;padding-bottom:30px;border:none">
												<textarea style="display:none; class="form-control" name="txtHeader" id="txtHeader" style="width:700px;resize:none" rows="8">
fromName:--
fromEmail: <contact@[domain]>
subject:--
date:[date]
to:[to]
reply-to:<reply@[domain]>
content-type:text/html;
												</textarea>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="padding-bottom:50px;border:none">
												<textarea style="display:none; class="form-control" name="txtBody" id="txtBody" style="width:700px;height:300px;resize:none" rows="">
<center>
<a href="http://[domain]/[idSend][RandomC/2][idEmail][RandomC/2][idFrom][RandomC/2][idSubject][RandomC/2][idCreative][RandomC/2][idIP]rr">
<img src="http://[domain]/[nameCreative]"/></a>
</center>
<center>	  
<a href="http://[domain]/[idSend][RandomC/2][idEmail][RandomC/2][idFrom][RandomC/2][idSubject][RandomC/2][idCreative][RandomC/2][idIP]uu">
<img src="http://[domain]/[nameCreativeUnsub]"/></a>
</center><br/><br/>
<center>	  
<img style="width:0px;height:0px;display:none;" src="http://[domain]/[idSend][RandomC/2][idEmail][RandomC/2][idFrom][RandomC/2][idSubject][RandomC/2][idIP]=[sender]"/>
</center></textarea>
											</td>
										</tr>
										<tr>
										</tr>
										<tr id="lineNegative">
											<td style="padding-bottom:50px;border:none">
												<span class="label bg-success heading-text">Negatives</span>  <a id="reloadNegatives"><i class="icon-loop3"></i></a><br/>
												<select name="cmbNegative" id="cmbNegative" class="select-clear" data-placeholder="Select Negative">
												<option value="0">Select Negative</option>
												<?php
												$idMailer = $_SESSION['id_Employer'];
												$requete = $bdd->prepare('select * from negative where id_mailer = ?');
												$requete->execute(array($idMailer));
												while($row = $requete->fetch())
												{
												?> <option value="<?php echo $row['id_negative'];?>"><?php echo $row['name_negative'];?></option><?php
												} 
												?>
												</select>
											</td>
										</tr>
									</table>	
								</div>							
							</fieldset>
							<input type="hidden" name="idCreative" id="idCreative"/>
							<div class="text-right">
								<button type="button" class="btn btn-primary btnPrepareSend">Create Offer <i></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Linode TAB-->
			<div id="Linode" class="tabcontent">
				<div id="div" class="content-wrapper" style="position:absolute;left:0;font-size:14px;width:73%;height:87vh" >
					<div class="panel panel-flat" style="height:100vh" >
						<div class="panel-body">
							<form action="linode_POST.php" method="post" >
								<div class="form-group">
									<div class="row">
										<div class="col-lg-2">
											<input class="form-control" type="text" placeholder="Linode name" name="name">
										</div>
										<div class="col-lg-2">
											<select class="form-control" name="region">
												<option value="us-south-1a">USA South</option>
												<option value="us-west-1a">USA West</option>
												<option value="us-southeast-1a">USA South-East</option>
												<option value="us-east-1a">USA East</option>
												<option selected value="eu-west-1a">UK</option>
												<option value="ap-south-1a">Singapore</option>
												<option value="eu-central-1a">Germany</option>
												<option value="ap-northeast-1a">Japan</option>
											</select>
										</div>
										<div class="col-lg-2">
											<select class="form-control" name="type">
												<option value="g5-nanode-1" selected>Linode 1024</option>
												<option value="g5-standard-1">Linode 2048</option>
											</select>
										</div>
										<div class="col-lg-2">
											<select class="form-control" name="distribution">
												<option value="linode/centos6.8" selected>CentOS 6.8</option>
												<option value="linode/centos7">CentOS 7</option>
											</select>
										</div>
										<div class="col-lg-2">
											<select class="form-control" name="quantity">
												<option value="1" selected>1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
											</select>
										</div>
										<div class="col-lg-2">
											<input type="button" class="btn btn-success" onclick="this.form.submit()" value="Add Linode">
										</div>
									</div>
								</div>
							</form>	<?php
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,"https://api.linode.com/v4/linode/instances");
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: token d49320f62bd07096dcb85605d9ff6e20b2ba9360629e48bd481d7d4b8bdf5129'));
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$server_output = curl_exec ($ch);
							$output = json_decode($server_output);
							$data=$output->data;
							$mailer = $_SESSION['id_Employer'] ;
							$selectMailer=$bdd->query("select alias_Server from server,servermailer where server.id_Server=servermailer.id_Server and is_Autorised=1 and id_Mailer='$mailer'");
							$MailerServer=array();
							while($line=$selectMailer->fetch()){
								$MailerServer[]=$line[0];
							}?>
							<div class="pull-left">
								<button class="btn btn-info"  onclick="if (confirm('Install selected linodes?')) { config_selected();}"><i class="fa fa-wrench" aria-hidden="true"></i> Install</button>
								<button class="btn btn-danger" onclick="if (confirm('Delete selected linodes?')) { delete_selected();}"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
							</div>
							<form action="linode_POST.php" method="post" id="linodes">
							<table class='table datatable-basic' style="font-size:14px;"><thead><?php
							//echo "<th>Label</th><th>IPv4</th><th>ipv6</th><th>Status</th><th>Distribution</th><th>Specs</th><th>Hypervisor</th><th>Region</th><th>ID</th><th>Created</th><th>Updated</th>";
							echo "<th></th><th>Label</th><th>IPv4</th><th>Status</th><th>Distribution</th><th>Installed</th></thead><tbody>";
							foreach($data as $d=>$e){
								if(in_array($e->label,$MailerServer)){
									echo "<tr>";?>
									<td>
										<div class="checkbox checkbox-primary">
												<input id="<?php echo $e->label?>" type="checkbox" class="checkbox" name="selected_linodes[]" value="<?php echo $e->label ."____". $e->id ."____". $e->ipv4[0];;?>">
											<label for="<?php echo $e->label?>"></label>
										</div>
									</td><?php
									echo "<td>". $e->label ."</td>";
									echo "<td>"; foreach($e->ipv4 as $ip) { echo $ip."<br>";}echo "</td>";
									//echo "<td>". $e->ipv6 ."</td>";
									echo "<td>". $e->status ."</td>";
									echo "<td>". $e->distribution ."</td>";
									//echo "<td>"; foreach($e->specs as $key=>$value) { echo "$key: $value<br>";}echo "</td>";
									//echo "<td>". $e->hypervisor ."</td>";
									//echo "<td>". $e->region ."</td>";
									//echo "<td>". $e->id ."</td>";
									//echo "<td>". $e->created ."</td>";
									//echo "<td>". $e->updated ."</td>";
									echo "<td>";
									$connection = @fsockopen($e->ipv4[0], 2304);
									if (is_resource($connection)){
										echo '<center><i style="color:green" class="fa fa-check" aria-hidden="true"></i></center>';
										fclose($connection);
									}
									else{
										echo '<center><i style="color:red" class="fa fa-times" aria-hidden="true"></i></center>';
									}
									?>
									<?php
									echo "</td></tr>";
								}
							}?>
							</tbody></table>
							</form><?php
							/*echo "<pre>";
							print_r($output);
							echo "</pre>";*/
							curl_close ($ch);?>
						</div>
					</div>
				</div>
				<div class="content-wrapper"  style="position:relative;float:right;font-size:14px;width:25%;height:80vh">
					<div class="panel panel-flat" id="div" style="border:1px solid #AAAAAA;height:85vh">
						<center><span><b>INSTALLATION</b></span></center><hr>
						<div class="panel-body"  id="install" style="font-size:12px">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_form_inline" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-body">
				<div class="form-group has-feedback">
					<label>IPS</label><br/>
					<textarea style="resize:none" class="form-control" id="txtIPS" rows="8"></textarea>
				</div>
				<button class="btn btn-warning" onclick="random()">Shuffle IPs</button>
			</div>				
			<div class="modal-body">
				<div class="form-group has-feedback">
					<label>Email Test</label><br/>
					<textarea type="text" style="resize:none" class="form-control" id="txtEmailTestInside"></textarea>
				</div>
			</div>
			<div class="modal-body">
				<div class="form-group has-feedback">
					<label>Start From</label><br/>
					<input type="text" style="resize:none" class="form-control" id="txtStartFromInside"></textarea>
				</div>
			</div>
			<div class="modal-footer text-center">
				<button class="btn btn-primary" id="btnSaveIPS">EDIT<i class="icon-pencil"></i></button>
			</div>	
		</div>
	</div>
</div>
	
<div id="modal_stats" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h5 class="modal-title">Offer Stats</h5>
			</div>
			<div class="modal-body" id="StatsResults" style="margin:auto">
			</div>
		</div>
	</div>
</div>
<div id="modal_UpdateSend" class="modal fade">
	<div class="modal-dialog" style="width:80%">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h5 class="modal-title">Update Offer</h5>
			</div>
			<div class="modal-body" id="UpdateResults" style="margin:auto"></div>
			<div class="modal-footer text-center updateSendButton">
				<button type="button" id="btnUpdateSend" class="btn btn-primary" onclick="btnUpdateSend()">Update
					<i class="icon-pencil3 position-right"></i>
				</button>
			</div>
		</div>
	</div>
</div>


<div id="modal_form_copy" class="modal fade">
	<div class="modal-dialog" style="width:800px;">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h5 class="modal-title">Copy Send</h5>
			</div>
			<form method="POST" action="copySend.php">
				<div class="modal-body">	
					<center>
						<div class="form-group has-feedback">
							<label>ISP: </label><br/>
							<select name="cmbIsps" id="cmbIsps" class="select-clear" data-placeholder="Select ISP" style="width:200px;">
								  <option value="-1">Please Select...</option>
								 
								 <?php
									$requete = $bdd->query('select * from isp');
									while($row = $requete->fetch())
									{
									   ?> <option value="<?php echo $row['id_Isp'];?>"><?php echo $row['name_isp'];?></option><?php
									} 
								 ?>
							   </select>
						</div>
						<div class="form-group has-feedback">
							<label>Country: </label><br/>
							<select name="cmbCountry" id="cmbCountry" class="select-clear" data-placeholder="Select Country" style="width:200px;">
								   <option value="-1">Select Country...</option>
								   <?php
									 $requete = $bdd->query('select * from country');
									 while($row = $requete->fetch())
									 {
									   ?> <option value="<?php echo $row['id_Country'];?>"><?php echo $row['name_Country'];?></option><?php
									 } 
								   ?>
							</select>
						</div>
						<table id="tableData" class="table table-bordered table-striped">
						</table>
					</center>
				</div>
				<input type="hidden" name="idSendCopy" id="idSendCopy"/>
				<div class="modal-footer text-center">
					<button type="submit" class="btn btn-primary" id="btnCopy">Copy<i class="icon-pencil"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
$bdd=null;
?>
<script>
	function btnUpdateSend(){
		$("#updateSend").submit().$("form");
		$('#modal_UpdateSend').trigger('click');
	}
</script>
<!-- PREPARE SEND SCRIPTS -->
<script>

$('.btnPrepareSend').click(function(){
		//var divLoading = $(this).children();
		//divLoading.html('.btnPrepareSend').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
		$("#SendForm").submit();
		
		 
	});
/*$('.btnRefresh').click(function() {
		var divLoading = $(this).children();
		divLoading.html('.btnRefresh').html('<img src="loading.gif" style="width:15px;height:15px;"/>'); 
		$("#sends").load(location.href+" #sends",function(){
			$('#loading').show();
			$.getScript("../assets/js/plugins/tables/datatables/datatables.min.js");
			$.getScript("datatables_basic.js"); 
			$.getScript("script.js");
			$('#loading').hide();
			divLoading.html('').html('<i class="icon-loop4"></i>');
		});
	});*/
var cpt   = 0;

$('#divTotalCount').hide();



$('#lineNegative').hide();

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
		  idCreative = $('.active:eq(0)').children().attr('id');
		  $('#idCreative').val(idCreative);
	   });
	   
		
	   
	});
	
	
	$('#cmbCountry').change(function(){
		
	   $('#spanTotalMixed').html('0');
	   $('#divTotalCount').show();
	   $('#lineNegative').hide();
	   
	   $('#tableData').html('');
	   var nameISP = $('#cmbIsps option:selected').text();
	   var country = $(this).val();
	   
	   if(nameISP!='warm up')
	   {
		   var idIsp = $("#cmbIsps").val();
		   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:country},function(data){
			  $('#tableData').html(data);
			  $('#tableData').show();
			  
			  
			  $('.chkListSelect').click(function(){
				  
				  var count = parseInt($(this).prev().html());
				  
				  if($(this).prop('checked')==true)
					  cpt+=count;
				  else
					  cpt-=count;
				  $('#spanTotalMixed').html(cpt); 
				});


		   });
	   }
	   
	   if(nameISP == 'hotmail')
		   $('#lineNegative').show();
	});
	
	
	$('#cmbIsps').change(function(){
	   
			$('#tableData').hide();
			$('#cmbCountry').val("-1");
			
	});
	
	$('#carousel-example-generic').on('slid.bs.carousel', function () {
	 idCreative = $('.active:eq(0)').children().attr('id');
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
	
	
	$('#txtBody').keyup(function(){
	   var codeHTML = $(this).val();
	   $('#bodyPreview').html('').html(codeHTML);
	});
	/*function showHTML () {

		$('#bodyPreview').html($('#txtBody').val());
	}*/
	
	$('#txtARList').hide();
	
	$('#chkAR').click(function(){
	   if($(this).is(":checked"))
		 $('#txtARList').show();
	   else
		 $('#txtARList').hide();
	});
	
	
	$('#reloadNegatives').click(function(){
		
		$.post('reloadNegative.php',{},function(data){
			
			$('#cmbNegative').html('').html(data);
			
		});
	});
	
	$('#cmbTarget').change(function(){
		
		var target = $(this).val();
		if(target == "1" || target == "2")
		{
			$("#tableData").hide();
			$('#tdVerticalTarget').show();
		}
			 
		else
		{
			$('#tdVerticalTarget').hide();
			$('#tableData').show();
		}
			
	});
	
	$('#tdVerticalTarget').hide();
</script>


<script>
	// Refresh Offer tab
	$('.btnRefresh').click(function() {
		$("#ProcessList").dataTable().fnDestroy();
		$("#SendList").dataTable().fnDestroy();
		var divLoading = $(this).children();
		divLoading.html('.btnRefresh').html('<img src="loading.gif" style="width:15px;height:15px;"/>'); 
		$("#sends").load(location.href+" #sends",function(){
			$('#loading').show();
			$.getScript("../assets/js/plugins/tables/datatables/datatables.min.js");
			$.getScript("datatables_basic.js"); 
			$.getScript("script.js");
			$('#loading').hide();
			divLoading.html('').html('<i class="icon-loop4"></i>');
		});
	
	});
	$('.btnRefreshProcess').click(function() {
		$("#SendList").dataTable().fnDestroy();
		var divLoading = $(this).children();
		divLoading.html('.btnRefreshProcess').html('<img src="loading.gif" style="width:15px;height:15px;"/>'); 
		$("#processes").load(location.href+" #processes",function(){
			$.getScript("../assets/js/plugins/tables/datatables/datatables.min.js");
			$.getScript("datatables_basic.js"); 
			$.getScript("script.js");
			divLoading.html('.btnRefreshProcess').html('<i class="icon-loop4"></i>');
		});
	});
	
	//Tabs
	$(function(){
		$("#Offers").show();
		$("#WarmUP").hide();
		$("#Test").hide();
		$("#PMTA1").hide();
		$("#PMTA2").hide();
		$("#Prepare").hide();
		$("#Process").hide();
		$("#Linode").hide();
		
	});
	function tab(evt, type) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(type).style.display = "block";
		evt.currentTarget.className += " active";
	}
	// Offers
	
	$('#tableData').hide();
	$('#divResult').hide();
	
	// Email Test
	$('#btnSelectTest').click(function(){
		var selectedIPS=$('#select').val();
		$('#cmbIPsTest').empty();
		var server = $('#cmbServerTest').val();
		$.post
		(
			'selectIPS.php',
			{
				selected : selectedIPS , 
				cmbServers : server
			},
			function(data)
			{
				$('#cmbIPsTest').html(data);
				$('#cmbIPsTest').focus();
				selctedVMTAsCount();
			}
		);
		
		
        
    });
	
   
	$('#btnTestTest').click(function(){
		
	   $('#divResultTest').hide();
	   
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
	$('#cmbServersTest').change(function()
	{
		$('#cmbIPsTest').empty();
		var server = $('#cmbServers').val();
		$.post
		(
			'getIPS2.php',
			{cmbServers : server},
			function(data)
			{
				$('#cmbIPsTest').html(data);
			}
		);
		selctedServersCount();
		selctedVMTAsCount();
	});
	
	
	//VMTAs :
	$('#cmbIPsTest').change(function()
	{
		selctedVMTAsCount();
	});
	
	
	//Filter Server :
	$('#txtServerFilterTest').keyup(function()
	{
		var	target_server	=	$('#txtServerFilterTest').val();
		var cptFoundedServers		=	0;
		var cptNotFoundedServers	=	0;
		$("#cmbServersTest option").each(function()
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
			$("#cmbServersTest").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbServersTest option[value='-1']").remove();
	
		
	});
	
	
	
	//Filter VMTA :
	$('#txtVmtaFilterTest').keyup(function()
	{
		var	target_vmta	=	$('#txtVmtaFilterTest').val();
		var cptFoundedVMTAs		=	0;
		var cptNotFoundedVMTAs	=	0;
		$("#cmbIPsTest option").each(function()
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
			$("#cmbIPsTest").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbIPsTest option[value='-1']").remove();
		
		
	});
	
	//Server Count :
	function selctedServersCount()
	{
		var nbr_servers	=	$('#cmbServersTest option:selected').length;
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
		$("#txtServerCountTest").text(str);
	}
	
	
	//VMTA Count :
	function selctedVMTAsCount()
	{
		var nbr_VMTAs	=	$('#cmbIPsTest option:selected').length;
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
		$("#txtVMTACountTest").text(str);
	}
	</script>
<script>
function shuffle(a) {
	var j, x, i;
	for (i = a.length; i; i--) {
		j = Math.floor(Math.random() * i);
		x = a[i - 1];
		a[i - 1] = a[j];
		a[j] = x;
	}
	return a.join("\n");
}
function random() {
	var ipsOriginal = document.getElementById('txtIPS').value;
	var ipsArray = ipsOriginal.split("\n");
	var ipsShuffle = shuffle(ipsArray);
	//var ipsRandom = ipsShuffle.replace(",","\n");
	document.getElementById('txtIPS').value = ipsShuffle;;
}
</script>
<script>
	function pmta() {
		var win = window.open('PMTA.php', '_blank');
		if (win) {
			win.focus();
		} else {
			alert('Please allow popups for this website');
		}
	}
	function preparesend() {
		var win = window.open('prepareSend.php', '_blank');
		if (win) {
			win.focus();
		} else {
			alert('Please allow popups for this website');
		}
	}
</script>


<script>


var idS = '';

$('#loading').hide();
$('#loading_stats').hide();
$('.btnSend').click(function(){
   var divLoading = $(this).children();
   
   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().children().val();
   var Seed     = $(this).parent().prev().prev().prev().children().val();
   var xDelay   = $(this).parent().prev().prev().children().val();
   
   if(xDelay=="")
	  xDelay = 0;
   
   var counterObject = $(this).parent().prev().children();
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');
	  
	  var countListExplode      = data.split('/');
	  var countListFinal        = parseInt(countListExplode[1]);
	  
	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
   });
   
});



$('.btnTestSend').click(function(){
   var idSend   = $(this).attr('id');
   
   $.post('SendTestOutSide.php',{id_Send:idSend},function(data){
      alert(data);
   });
   
});



$('.btnEditIPS').click(function(){
	  
	 idS = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().attr("id");
	  
	  $.post('getIPSSend.php',{type:"ips",idS:idS},function(data){
		  $('#txtIPS').val('').val(data);
	  });
	  
	   $.post('getIPSSend.php',{type:"emailTest",idS:idS},function(data){
		  $('#txtEmailTestInside').val('').val(data);
	  });
	  
	   $.post('getIPSSend.php',{type:"startFrom",idS:idS},function(data){
		  $('#txtStartFromInside').val('').val(data);
	  }); 
	
});
$('.btnStats').click(function(){
	  
	var idSend   = $(this).attr('id');
	$("#StatsResults").empty();
	
	$("#StatsResults").load("GetSendStats.php?id_Send=" + idSend);
	
});

$('.btnUpdate').click(function(){
	var idSend   = $(this).attr('id');
	$("#UpdateResults").empty();
	$('#UpdateResults').load("updateSend.php?id_Send=" + idSend)
});

$('#btnSaveIPS').click(function(){
	var ips       = $('#txtIPS').val();
	var emailTest = $('#txtEmailTestInside').val();
	var startFrom = $('#txtStartFromInside').val();
	
	$.post('editIPS.php',{ips:ips,emailTest:emailTest,startFrom:startFrom,idS:idS},function(data){
		$('#modal_form_inline').trigger('click');
	});
});


$('.btnCopy').click(function(){
	
	var idSendCopy = $(this).prev().prev().attr('id');
	$('#idSendCopy').val(idSendCopy);
});


$('#cmbIsps').change(function(){
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
	$('#tableData').html('');
});		
	

$('.btnStop').click(function(){
	
	var divLoading = $(this).prev().prev().prev().children();
	var idSendStop = $(this).attr('id');
	alert(idSendStop);
	$.post('getCurrentProcess.php',{idSend : idSendStop},function(data){
		var explode = data.split('\n');
		
		for(var i=0;i<explode.length;i++)
		{
		   if(explode[i].trim().length!=0)
		   {
			   var line = explode[i].split('-');
			   var host = line[0];
			   var pid  = line[1];
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

					divLoading.html('').html('<i class="icon-target2"></i>');
				    alert(data);
			   });
			   
		   }
		   
		}
		
	});
});
$('.btnStopProcess').click(function(){
	var SendStop = $(this).attr('id').split('-');
	var pid=SendStop[0];
	var host=SendStop[1];
	var id=SendStop[2];
	var user=SendStop[3];
	$.post('http://'+host+'/exactarget/Send/kill.php',{id:id,pid:pid,host:host,user:user},function(data){
		alert(data);
	});
});	



	function cb(start, end) 
	{
			$('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
	}
	
	cb(moment().subtract(30, 'days'), moment());

    $('#reportrange').daterangepicker({
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
	
	
	
	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		  
		  $('#loading').show();
		  $('#sends').html('');
		  
		  var startDate = picker.startDate.format('YYYY-MM-DD');
		  var endDate   = picker.endDate.format('YYYY-MM-DD');
		  var page = "original";
		  
		  $.post('getSendsByInterval.php',{startDate : startDate , endDate : endDate, page : page},function(data){
			  
			  $('#loading').hide();
			  $('#sends').html(data);	

			  $('.datatable-basic').on( 'draw.dt', function () {





			  
$(".btnSend").unbind( "click" );
$(".btnTestSend").unbind( "click" );
$(".btnEditIPS").unbind( "click" );
$(".btnSend").unbind( "click" );
$(".btnStop").unbind( "click" );
$(".btnCopy").unbind( "click" );


$('.btnSend').click(function(){
   var divLoading = $(this).children();
   
   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().children().val();
   var Seed     = $(this).parent().prev().prev().prev().children().val();
   var xDelay   = $(this).parent().prev().prev().children().val();
   
   if(xDelay=="")
	  xDelay = 0;
   
   var counterObject = $(this).parent().prev().children();
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');
	  
	  var countListExplode      = data.split('/');
	  var countListFinal        = parseInt(countListExplode[1]);
	  
	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
   });
   
});



$('.btnTestSend').click(function(){
   var idSend   = $(this).attr('id');
   
   $.post('SendTestOutSide.php',{id_Send:idSend},function(data){
      alert(data);
   });
   
});





$('#btnSaveIPS').click(function(){
	var ips       = $('#txtIPS').val();
	var emailTest = $('#txtEmailTestInside').val();
	var startFrom = $('#txtStartFromInside').val();
	
	$.post('editIPS.php',{ips:ips,emailTest:emailTest,startFrom:startFrom,idS:idS},function(data){
		$('#modal_form_inline').trigger('click');
	});
});

$('.btnCopy').click(function(){
	
	var idSendCopy = $(this).prev().prev().attr('id');
	$('#idSendCopy').val(idSendCopy);
});



$('#cmbIsps').change(function(){
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
	$('#tableData').html('');
});		
	

$('.btnStop').click(function(){
	
	var divLoading = $(this).prev().prev().prev().children();
	var idSendStop = $(this).prev().prev().prev().attr('id');
	
	$.post('getCurrentProcess.php',{idSend : idSendStop},function(data){
		var explode = data.split('\n');
		
		for(var i=0;i<explode.length;i++)
		{
		   if(explode[i].trim().length!=0)
		   {
			   var line = explode[i].split('-');
			   var host = line[0];
			   var pid  = line[1];
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

					divLoading.html('').html('<i class="icon-target2"></i>');
				    alert(data);
			   });
			   
		   }
		   
		}
		
	});
});



			  });
			  
			  
		  });
		  
	});

$('.btnDelete').click(function(){
   var idSend   = $(this).attr('id');
   var r = confirm("Are you sure you want to delete send " + idSend + "?");
   if (r == true) {
		$.post('DeleteSend.php',{id_Send:idSend},function(data){
		alert(data);
		window.location.reload();
		});
   }

 });
</script>
<script>
//PMTA1
		$('#cmbServers').change(function(){
		   
		   var ip = $(this).val();
		   $('#framePMTA').attr('src','http://'+ip+':2304');

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
		
		
		$('#btnSchedule').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'schedule';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		function commands(ip,command,queue)
		{
		
		  var link = 'http://'+ip+'/exactarget/Send/commandsPMTA.php';
		  
		  $.post('formCommands.php',{link:link,command:command,queue:queue},function(data){
		    //alert(data);
		  });
		  
		}
		

		
		
</script>
<script>
	//PMTA2
		$('#cmbServers2').change(function(){
		   
		   var ip = $(this).val();
		   $('#framePMTA2').attr('src','http://'+ip+':2304');

		});
		
		$('#btnDelete2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'delete';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		
		$('#btnPause2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'pause';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnResume2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'resume';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnReset2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'reset2';
		   
		   commands(ip,command,'');
		});
		
		
		$('#btnSchedule2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'schedule2';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		function commands(ip,command,queue)
		{
		
		  var link = 'http://'+ip+'/exactarget/Send/commandsPMTA.php';
		  
		  $.post('formCommands.php',{link:link,command:command,queue:queue},function(data){
		    //alert(data);
		  });
		  
		}
		

		
		
</script>
<script>
function delete_selected(){
	$("form#linodes").append('<input type="hidden" name="delete_selected" value="" /> ');
	$('form#linodes').submit();
}
function config_selected(){
	$("form#linodes").append('<input type="hidden" name="config_selected" value="" /> ');
	//$('form#linodes').submit();
	/*$.post('linode_POST.php', $('#linodes').serialize(), function (data, textStatus) {
         $('#install').append(data);
    });*/
	$("#install").empty();
	$("#install").append('<center>Installing: Please wait ....</b>');
	$.ajax({
			url: "linode_POST.php",
			type: "POST",
			data: $('#linodes').serialize(),
			xhrFields: {
				onprogress: function(e) {
					console.log(e.target.responseText)
					$("#install").html(e.target.responseText)
					if (e.lengthComputable) {
						console.log(e.loaded / e.total * 100 + '%');
					}
				}
			},
			success: function(text) {
					console.log(text)
					$("#install").html(text+"Done!")
			}
		});
   return false;
}
</script>

</body>
</html>
<?php ob_end_flush();
?>
