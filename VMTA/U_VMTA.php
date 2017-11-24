<?php


	
    date_default_timezone_set('UTC');
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include_once('../Includes/bdd.php');
	
	if( isset ($_POST['cmbServers']) )
	{
		$idServer = $_POST['cmbServers'];
		$ip       = $_POST['cmbIPS'];
		$domain   = $_POST['txtDomain'];
		
		$requete = $bdd->prepare('update vmta set domain = ? where ip = ? and id_Mailer = ?');
		$requete->execute(array($domain,$ip,$_SESSION['id_Employer']));
		
		$ch = curl_init();
		
		$url = 'http://'.$ip.'/exactarget/PMTA/getFileContent.php';
			
			$fields = array(
				'fileName'    => urlencode('vmta.txt')
		);


		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');


		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
		
		$newReplace = '<virtual-mta vmta-'.$ip.'-'.$_SESSION['id_Employer'].'>host-name '.$domain;
		
		$result = preg_replace('#<virtual-mta vmta-'.$ip.'-'.$_SESSION['id_Employer'].'>host-name [a-zA-Z0-9_.-]+#',$newReplace,$result);
		

	
		
		
		$url = 'http://'.$ip.'/exactarget/PMTA/setFileContent.php';
			
			$fields = array(
				'file'    => urlencode('vmta.txt'),
				'data'    => urlencode($result)
		);


		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');


		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $out = curl_exec($ch);
		
		header('location:ShowVMTAS.php');
		
		
	}
	
	else
	{
		
		$idServerCombo = 0;
		$ipCombo       = '';
		$domainCombo   = '';
		
		if(isset($_GET['ip']))
		{
			$m  = $_GET['m'];
			$requete = $bdd->prepare('select * from vmta where ip = ? and id_Mailer = ?');
		    $requete->execute(array($_GET['ip'],$m));
			$row = $requete->fetch();
			$idServerCombo = $row['id_Server'];
			$domainCombo   = $row['domain'];
			$ipCombo       = $_GET['ip'];
		}
		
	
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Update VMTA</title>
	
	
	
	
	
	
	
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
	<?php 
	include('../Includes/navbar.php');
	include('../Includes/bdd.php');
	
	
	?>
    


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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">VMTA</span> - Update</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>VMTA</li>
							<li class="active">Update</li>
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
									<legend class="text-bold">Update VMTA</legend>

									
									

									
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
																		<option value="<?php echo $row['id_Server']?>" <?php if($idServerCombo == $row['id_Server']) {echo 'selected';} ?>><?php echo $row['alias_Server'];?></option>
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
																		<option value="<?php echo $row['id_Server']?>" <?php if($idServerCombo == $row['id_Server']) {echo 'selected';} ?>><?php echo $row['alias_Server'];?></option>
																		<?php
																	}
																	
																}
															  
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
									
									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">IPS</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbIPS" id="cmbIPS" class="select-clear" data-placeholder="Select IP">
                                                <?php
												  if(isset($_GET['ip']))
												  {
													  $requete = $bdd->prepare('select * from ip where id_Server_IP = ?');
													  $requete->execute(array($idServerCombo));
													  while($row = $requete->fetch())
													  {
														  ?>
														  <option value="<?php echo $row['IP_IP'];?>" <?php if($ipCombo == $row['IP_IP']) {echo 'selected';} ?>> <?php echo $row['IP_IP'];?> </option>
														  <?php
														  
													  }
												  }
												?>
										
				                            </select>
			                            </div>
			                        </div>
									
									<center>
									 <h2 id="titleResult"></h2>
									</center>
									
									<div class="form-group" id="domainLine">
										<label class="control-label col-lg-2">Domain</label>
										<div class="col-lg-10">
											<input type="text" name="txtDomain" id="txtDomain" class="form-control" value="<?php echo $domainCombo;?>">
										</div>
									</div>
									
								
									
									
									
						</fieldset>
								
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary">Update<i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
								
					</form>
					
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
	 
	 $('#domainLine').hide();
	 $('#titleResult').hide();
	 
	 <?php 
	 if ( isset($_GET['ip']) ) 
	 { 	?> 
		$('#domainLine').show(); 
		<?php
	 } ?>
	 
	 $('#cmbServers').change(function(){
		
	    var idServer = $(this).val();
		
		$.post('getIPS.php',{idServer : idServer},function(data){
			
			$('#cmbIPS').html(data);
			
		});
		 
	 });
	 
	 $('#cmbIPS').change(function(){
		
	    var ipTxt = $(this).val();
		
		$.post('domainVMTA.php',{ipTxt : ipTxt},function(data){
			
			var spl    = data.split('-');
			var result = spl[0];
			if(result == "1")
			{
				$('#titleResult').hide();
				$('#txtDomain').val(spl[1]);
				$('#domainLine').show();
			}
			else
			{
				$('#domainLine').hide();
				$('#titleResult').text('This IP Dont have a VMTA').show();
			}
			
		});
		 
	 });
	 
	 
	</script>
</body>
</html>
<?php
	}
?>
