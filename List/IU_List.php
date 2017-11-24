<?php
    set_time_limit(0);
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	
    include('../Includes/bdd.php');
	  
	$id_List                = '';
	$name_List			    = '';
	$id_Country_List        = '';
	$isActive_List          = '0';
	$id_Type_List			= '0';
	$id_ISP_List			= '0';
	$isOptIN_List			= '0';
	$fields_List			= '';
	$buttonText             = 'Insert';
	  
	if(isset($_GET['id_List']))
	{
	    $buttonText         = 'Update';
	    $id_List = $_GET['id_List'];
	    $requete = $bdd->prepare('select * from list where id_List = ?');
	    $requete->execute(array($id_List));
		extract($requete->fetch());
	}	
?>


									
									
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>List</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="../assets/js/pages/picker_date.js"></script>
    <script type="text/javascript" src="../assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_checkboxes_radios.js"></script>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">List</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>List</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_List_Post.php" enctype="multipart/form-data">
					  <fieldset class="content-group">
					<?php
					if(!isset($_GET["upload"]))
					{?>
					  
									<legend class="text-bold"><?php echo $buttonText;?> List</legend>

									<div class="form-group">
										<label class="control-label col-lg-2">List Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameList" id="txtNameList" class="form-control" value="<?php echo $name_List;?>">
										</div>
									</div>

									<div class="form-group">
										<label class="control-label col-lg-2">Country</label>
										<div class="col-lg-10">
										<?php
										 $requete  = $bdd->query('select * from country');
										 while($row = $requete->fetch())
										 {
										    echo $row['name_Country']?><input type="radio" name="radioCountry" id="radioCountry" class="styled" value="<?php echo $row['id_Country'];?>" style="padding-left:50px;" <?php echo ($row['id_Country'] == $id_Country_List) ? 'checked' : '';?> /><?php
										 }
										?>
											
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Active</label>
										<div class="col-lg-10">
										<input type="checkbox" name="chkIsActive" id="chkIsActive" class="switchery" <?php echo ($isActive_List == 1) ? 'checked' : '';?>>
										</div>
									</div>
									
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Type</label>
										<div class="col-lg-10">
										<select name="cmbTypeList" id="cmbTypeList" class="select-clear" data-placeholder="Select Type List">
										<?php
										 $requete  = $bdd->query('select * from typelist');
										 while($row = $requete->fetch())
										 {
										   ?> <option value="<?php echo $row['id_TypeList'];?>" <?php echo ($row['id_TypeList'] == $id_Type_List) ? 'selected' : '';?>><?php echo $row['name_TypeList'];?></option><?php
										 }
										?>
										</select>	
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">ISP</label>
										<div class="col-lg-10">
										<select name="cmbISP" id="cmbISP" class="select-clear" data-placeholder="Select ISP">
										<?php
										 $requete  = $bdd->query('select * from isp');
										 while($row = $requete->fetch())
										 {
										   ?> <option value="<?php echo $row['id_Isp'];?>" <?php echo ($row['id_Isp'] == $id_ISP_List) ? 'selected' : '';?>><?php echo $row['name_isp'];?></option><?php
										 }
										?>
										</select>	
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Opt IN</label>
										<div class="col-lg-10">
										<input type="checkbox" name="chkIsOptIN" id="chkIsOptIN" class="switchery" <?php echo ($isOptIN_List == 1) ? 'checked' : '';?>>
										</div>
									</div>
									
									
									<div class="form-group" id="infosOptIn">
										<label class="control-label col-lg-2">Infos</label>
										<div class="col-lg-10">
										<table>
										  <tr>
										    <td>Delimiter</td>
											<td>
											  <select name="cmbDelimiter">
											   <option value="/">/</option>
											   <option value=",">,</option>
											   <option value="|">|</option>
											   <option value=";">;</option>
											  </select>
											</td>
											<td></td>
										  </tr>
										  
										  <tr>
										    <td>First Name</td>
											<td><input type="checkbox" name="chkField[]" value="firstName_Email"/></td>
											<td><input type="text" name="valuFields[]"/></td>
										  </tr>
										  
										  <tr>
										    <td>Last Name</td>
											<td><input type="checkbox" name="chkField[]" value="lastName_Email"/></td>
											<td><input type="text" name="valuFields[]"/></td>
										  </tr>
										  
										  <tr>
										    <td>Email</td>
											<td><input type="checkbox" name="chkField[]" value="email_Email"/></td>
											<td><input type="text" name="valuFields[]"/></td>
										  </tr>
										  
										  
										</table>
										</div>
									</div>
									
									
									
									
									
									
									
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_List']))
								{
								   ?>
										<div class="form-group">
										<label class="control-label col-lg-2">Action</label>
											<div class="col-lg-10">
												<select name="dataAction" class="form-control">
												  <option value="none">None</option>
												  <option value="ecraser">Ecraser</option>
												  <option value="append">Append</option>
												</select>
											</div>
									    </div>
									
								     <input type="hidden" name="id_List" value="<?php echo $id_List;?>"/>
								   <?php
								}
					}else{
						$query=$bdd->query("select * from list where id_List='$_GET[id_List]'");
						$list=$query->fetch();
						switch($list["id_Type_List"]){
							case "1":
							$type="Fresh";
							break;
							case "2":
							$type="Delivered";
							break;
							case "3":
							$type="Openers";
							break;
							case "4":
							$type="Clickers";
							break;
							case "5":
							$type="Unsubscribers";
							break;
						}
						?>
						<?php echo "<legend class=text-bold>$list[name_List] - $type"?></legend>
						 <input type="hidden" name="id_List" value="<?php echo $id_List;?>"/>
						 <input type="hidden" name="upload" value="<?php echo $id_List;?>"/>
						<div class="form-group">
							<label class="control-label col-lg-2">File</label>
							<div class="col-lg-10">
								<input type="file" name="data_update" class="form-control">
							</div>
						</div><?php
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

	<script>
	
	$('#infosOptIn').hide();
	if($('#chkIsOptIN').is(':checked'))
	  $('#infosOptIn').show();
	
	  $('#chkIsOptIN').click(function(){
	    if($(this).is(':checked'))
		  $('#infosOptIn').show();
		else
		  $('#infosOptIn').hide();

	  });
	 
	</script>
</body>
</html>
