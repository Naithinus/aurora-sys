<?php
    date_default_timezone_set('UTC');
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include_once('../Includes/bdd.php');
	
	$buttonText 			                   = 'Insert';
	$id_Server			       		           = '';
	$alias_Server			       		       = '';
	$id_Server_Provider_Server			       = '';
	$username_Server			       		   = '';
	$password_Server			       		   = '';
	$saleDate_Server			       		   = '';
	$expirationDate_Server			       	   = '';
	$id_OS_Server			       		       = '';
	$id_IP_Server			       		       = '';
	$isActive_Server			       		   = '';
	$isp_Server			       		   		   = '';
	$mailerYes_Server			       		   = '';
	$mailerNo_Server			       		   = '';
	
	if(isset($_GET['id_Server']))
	{
	   $buttonText 			   = 'Update';
	   $id_Server              = $_GET['id_Server'];
	   
	   $requete = $bdd->prepare('select * from server where id_Server = ?');
	   $requete->execute(array($id_Server));
	   extract($requete->fetch());
	   
	   $date 					   = new DateTime($saleDate_Server);
	   $saleDate_Server			   = $date->format('m/d/Y');
	   
	   $date 					   = new DateTime($expirationDate_Server);
	   $expirationDate_Server			   = $date->format('m/d/Y');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Server</title>
	
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
	include('../Includes/bdd.php');?>
    


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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Server</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Server</li>
							<li class="active"><?php echo $buttonText;?></li>
					    </ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal" method="POST" action="IU_Server_Post.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Server</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Alias</label>
										<div class="col-lg-10">
											<input type="text" name="txtAlias" id="txtAlias" class="form-control" value="<?php echo $alias_Server;?>">
										</div>
									</div>
									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Server Provider</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbServerProvider" id="cmbServerProvider" class="select-clear" data-placeholder="Select OS">
                                                <?php
													$requete = $bdd->query('select * from serverprovider');
													while($row = $requete->fetch())
													{
													     ?><option value="<?php echo $row['id_ServerProvider'];?>" <?php echo ($row['id_ServerProvider'] == $id_Server_Provider_Server) ? "selected" : "";?>><?php echo $row['name_ServerProvider'];?></option><?php
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">User Name</label>
										<div class="col-lg-10">
											<input type="txt" name="txtUserName"  class="form-control" value="<?php echo $username_Server;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Password</label>
										<div class="col-lg-10">
											<input type="password" name="txtPassword"  class="form-control" value="<?php echo $password_Server;?>">
										</div>
									</div>
									
									
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Sale Date</label>
										<div class="input-group" style="position:relative;left:10px;width:835px;">
											<span class="input-group-addon"><i class="icon-calendar22"></i></span>
											<input type="text" class="form-control daterange-single"  name="txtDateSale" id="txtDateSale" value="<?php echo $saleDate_Server;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Expiration Date</label>
										<div class="input-group" style="position:relative;left:10px;width:835px;">
											<span class="input-group-addon"><i class="icon-calendar22"></i></span>
											<input type="text" class="form-control daterange-single"  name="txtDateExpiration" id="txtDateExpiration" value="<?php echo $expirationDate_Server;?>">
										</div>
									</div>
									

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">OS</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbOS" id="cmbOS" class="select-clear" data-placeholder="Select OS">
                                                <?php
													$requete = $bdd->query('select * from os');
													while($row = $requete->fetch())
													{
													     ?><option value="<?php echo $row['id_OS'];?>" <?php echo ($row['id_OS'] == $id_OS_Server) ? "selected" : "";?>><?php echo $row['name_OS'];?></option><?php
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
									
									<div class="form-group">
										<label class="control-label col-lg-2">Is Active</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkIsActive" id="chkIsActive" <?php if($isActive_Server==1) echo 'checked';?>>
										</div>
									</div>
									
								
								<div id="droits">
								
								<div class="form-group">
										<label class="control-label col-lg-2">ISPS</label>
										
										<?php
										   
										   
										   $subRequete = $bdd->prepare('select id_Isp from serverisp where id_Server = ?');
										   $subRequete->execute(array($id_Server));
										   $split = array();
										   
										   while($subRow = $subRequete->fetch())
										   {
											   $isp_Server = $subRow['id_Isp'];
											   $split[] = $isp_Server;
										   }
										      
										   $requete = $bdd->query('select * from isp');
										   while($row = $requete->fetch())
										   {
										      ?>
											  
											  <?php echo $row['name_isp'];?><input type="checkbox" class="control-success" name="chkISPS[]" value="<?php echo $row['id_Isp'];?>" <?php echo (in_array(strval($row["id_Isp"]),$split)) ? "checked" : "";?>>

											  <?php
										   }
										?>
										
										
								</div>
									
									
								
									<div class="form-group">
										<label class="control-label col-lg-2">Mailers Yes</label>
										<div class="input-group">
											<select name="cmbMailersYes[]" id="cmbMailersYes" multiple>
											 <?php
											 
											    $subRequete = $bdd->prepare('select id_Mailer from servermailer where id_Server = ? and is_Autorised = 1');
											    $subRequete->execute(array($id_Server));
											    $split = array();
										   
											    while($subRow = $subRequete->fetch())
											    {
												   $mailerYes = $subRow['id_Mailer'];
												   $split[] = $mailerYes;
											    }
										   
											    $requete = $bdd->query("select e.id_Employer,e.firstName_Employer,e.LastName_Employer from employer e , type_employer te where e.id_type_Employer_Employer = te.id_type_Employer and te.name_type_Employer IN('Mailer','Team Leader')");
											    
												while($row = $requete->fetch())
												{
													?>
													<option value="<?php echo $row["id_Employer"];?>" <?php echo (in_array(strval($row["id_Employer"]),$split)) ? "selected" : "";?>><?php echo $row["id_Employer"].'-'.$row["firstName_Employer"].'-'.$row["LastName_Employer"];?></option>
													<?php
												}
											 ?>
											</select>
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Mailers NO</label>
										<div class="input-group">
											<select name="cmbMailersNo[]" id="cmbMailersNo" multiple>
											 <?php
											 
												$subRequete = $bdd->prepare('select id_Mailer from servermailer where id_Server = ? and is_Autorised = 0');
											    $subRequete->execute(array($id_Server));
											    $split = array();
										   
											    while($subRow = $subRequete->fetch())
											    {
												   $mailerNo = $subRow['id_Mailer'];
												   $split[] = $mailerNo;
											    }
												
											    $requete = $bdd->query("select e.id_Employer,e.firstName_Employer,e.LastName_Employer from employer e , type_employer te where e.id_type_Employer_Employer = te.id_type_Employer and te.name_type_Employer IN('Mailer','Team Leader')");
											    
												while($row = $requete->fetch())
												{
													?>
													<option value="<?php echo $row["id_Employer"];?>" <?php echo (in_array(strval($row["id_Employer"]),$split)) ? "selected" : "";?>><?php echo $row["id_Employer"].'-'.$row["firstName_Employer"].'-'.$row["LastName_Employer"];?></option>
													<?php
												}
											 ?>
											</select>
										</div>
									</div>
									
									
							</div>	
							<div class="form-group ipRow">
												 <div class="col-md-6">
													 <label>IPS</label>
														<textarea name="txtIP" rows="6" class="form-control" style="resize:none;overflow-y: scroll;"></textarea>
													</div>
											
											
												 <div class="col-md-6">
													 <label>Domains</label>
														<textarea name="cmbDomain" rows="6" class="form-control" style="resize:none;overflow-y: scroll;"></textarea>
													</div>
										</div>
									
									
						</fieldset>
								
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?><i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
								<?php
								  if(isset($_GET['id_Server']))
								  {
								     ?> <input type="hidden" name="id_Server" id="id_Server" value="<?php echo $id_Server;?>"/><?php
								  }
								?>
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
	 
	 
	 $('#droits').hide();
	 
	  $('#chkIsActive').click(function(){
	    if($(this).is(':checked'))
		  $('#droits').show();
		else
		  $('#droits').hide();

	  });
	  
	  <?php
	    if($isActive_Server==1)
		{
			?> $('#droits').show(); <?php
		}
	 ?>
	 
	</script>
</body>
</html>
