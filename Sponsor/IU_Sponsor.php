<?php
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 include('../Includes/bdd.php');
	  
	  $id_Sponsor             = '';
	  $name_Sponsor			  = '';
	  $logo_Sponsor           = '';
	  $isActive_Sponsor        = '0';
	  $buttonText         = 'Insert';
	  
	  if(isset($_GET['id_Sponsor']))
	  {
	    $buttonText         = 'Update';
	    $id_Sponsor = $_GET['id_Sponsor'];
	    $requete = $bdd->prepare('select * from sponsor where id_Sponsor = ?');
	    $requete->execute(array($id_Sponsor));
		extract($requete->fetch());
	  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Sponsor</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	

	
	
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_checkboxes_radios.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/styling/switchery.min.js"></script>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Sponsor</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Sponsor</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_Sponsor_Post.php" enctype="multipart/form-data">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Sponsor</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">Name Sponsor</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameSponsor" id="txtNameSponsor" class="form-control" value="<?php echo $name_Sponsor;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Login Sponsor</label>
										<div class="col-lg-10">
											<input type="text" name="txtLoginSponsor" id="txtLoginSponsor" class="form-control" value="<?php echo $login_sponsor;?>">
										</div>
									</div>
									
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Password Sponsor</label>
										<div class="col-lg-10">
											<input type="text" name="txtPasswordSponsor" id="txtPasswordSponsor" class="form-control" value="<?php echo $password_sponsor;?>">
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-2">URL SignIn Sponsor</label>
										<div class="col-lg-10">
											<input type="text" name="txtUrlSponsor" id="txtUrlSponsor" class="form-control" value="<?php echo $url_login_page_sponsor;?>">
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-2">Affiliate ID</label>
										<div class="col-lg-10">
											<input type="text" name="txtAffiliateIdSponsor" id="txtAffiliateIdSponsor" class="form-control" value="<?php echo $affiliate_id_sponsor;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">API Access Key</label>
										<div class="col-lg-10">
											<input type="text" name="txtApiAccessKey" id="txtApiAccessKey" class="form-control" value="<?php echo $api_access_key_sponsor;?>">
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-2">API Host URL</label>
										<div class="col-lg-10">
											<input type="text" name="txtApiHostURL" id="txtApiHostURL" class="form-control" value="<?php echo $api_host_url;?>">
										</div>
									</div>
									
									
									
									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Plateform Sponsor</label>
			                        	<div class="col-lg-10">
				                            <select name="cmbPlateformSponsor" id="cmbPlateformSponsor" class="select-clear" data-placeholder="Select Plateform">
                                                <?php
													$requete = $bdd->query('select id_plateform,name_plateform from plateform');
													while($row = $requete->fetch())
													{
													     ?><option value="<?php echo $row['id_plateform'];?>" <?php echo ($row['id_plateform'] == $id_plateform_sponsor) ? "selected" : "";?>><?php echo $row['name_plateform'];?></option><?php
													}
												?>	
				                            </select>
			                            </div>
			                        </div>
									
									

									<div class="form-group">
										<label class="control-label col-lg-2">Logo</label>
										<div class="col-lg-10">
											<input type="file" name="logo" id="logo" class="form-control">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Is Active</label>
										<div class="checkbox checkbox-switchery col-lg-10">
											<input type="checkbox" class="switchery" name="chkIsActive" id="chkIsActive" <?php if($isActive_Sponsor==1) echo 'checked';?>>
										</div>
									</div>
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Link Type</label>
			                        	<div class="col-lg-10">
				                            <select name="linkType" id="linkType" class="select-clear">
                                                <?php
													$requete = $bdd->query('select * from  link_type');
													while($row = $requete->fetch())
													{
														if($row["type_id"]==$link_type){
															?><option value="<?php echo $row["type_id"];?>" selected><?php echo $row["type_value"]?></option><?php
														}else{
															?><option value="<?php echo $row["type_id"];?>"><?php echo $row["type_value"]?></option><?php
														}
													     
													}
												?>	
				                            </select>
			                            </div>
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_Sponsor']))
								{
								   ?>
								     <img src="Images/<?php echo $logo_Sponsor;?>"/>
								     <input type="hidden" name="idSponsor" value="<?php echo $id_Sponsor;?>"/>
								   <?php
								}
								?>
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?> <i class="icon-arrow-right14 position-right"></i></button>
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

</body>
</html>
