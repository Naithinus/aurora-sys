<?php 
ini_set('error_reporting',E_ALL ^ E_WARNING);
set_time_limit(0);
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" 
      type="image/png" 
      href="http://<?php echo $_SERVER['HTTP_HOST'];?>/aurora/icon.png">
	<title>Aurora - Linode Servers</title>
	
	<?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">  
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
</head>
<body>
<?php
include('../Includes/navbar.php'); ?>
<div class="page-container" id="page-container">
	<div class="page-content">	<?php 
		/*if($_SESSION['type_Employer']!="Mailer"){
			Include('../Includes/sidebar.php');
		}*/
		?>
		<div class="content-wrapper">
			<div class="panel panel-flat">
				<div class="panel-body">
					<form action="linode_POST.php" method="post">
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
					<table class='table datatable-basic display' style="font-size:14px"><thead><?php
					//echo "<th>Label</th><th>IPv4</th><th>ipv6</th><th>Status</th><th>Distribution</th><th>Specs</th><th>Hypervisor</th><th>Region</th><th>ID</th><th>Created</th><th>Updated</th>";
					echo "<th>Label</th><th>IPv4</th><th>Status</th><th>Distribution</th><th>Edit</th></thead><tbody>";
					foreach($data as $d=>$e){
						if(in_array($e->label,$MailerServer)){
							echo "<tr>";
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
							//echo "<td>". $e->updated ."</td>";?>
							<td><button class="btn btn-danger" onclick="if (confirm('Are you sure you want to delete this instance?')) { location.href='./linode_POST.php?delete=<?php echo $e->id?>&name=<?php echo $e->label?>'}"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
							<button class="btn btn-info" onclick="location.href='./linode_POST.php?config=<?php echo $e->ipv4[0]?>'"><i class="fa fa-wrench" aria-hidden="true"></i> Config</button></td><?php
							echo "</tr>";
						}
					}?>
					</tbody></table><?php
					/*echo "<pre>";
					print_r($output);
					echo "</pre>";*/
					curl_close ($ch);?>
				</div>
			</div>
		</div>
	</div>
</div>
</body>

</html>