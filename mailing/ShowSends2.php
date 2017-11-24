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
	.sidebar button {
		float: left;
		border: 1px solid #f5f5f5;
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
		left:100px;
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
	.sidebar {
		padding-top:50px;
	}
	</style>
	
</head>
<body style=" position:fixed;top:0;bottom:0;left:0;right:0;background-color:#AAAAAA" class="sidebar-xs">
<?php
	include('../Includes/navbar.php'); ?>

<div class="page-container" id="page-container">
	<div class="page-content">
		<div class="content-wrapper" style="">
			<div class="tab" style="">
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
				<div class="panel panel-flat" style="height:99%">
					<div class="panel-body">
						<a class="btn border-info-800 text-info-800  btn-rounded btn-icon valign-text-bottom btnRefresh" title="Refresh"><div class="divLoading"><i class="icon-loop4"></i></div></a>					
						<!-- <span style="font-size:20px" class="label bg-danger-400">Testing! Don't send!</span>-->
						<div class="form-group">				
							<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 30%">
								<i class="icon-calendar3"></i>  <span></span> <b class="caret"></b>
							</div>
						</div><br/>
						<div id="sends">
							<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>
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
?><script><?php
include ("ShowSendsScripts.php");
?>
</script>
</body>
</html>
<?php ob_end_flush();
?>
