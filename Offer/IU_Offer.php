<?php
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 include('../Includes/bdd.php');
	  
	  $id_offer                    = '';
	  $sid_Offer				   = 0;	
	  $name_Offer			       = '';
	  $id_Sponsor_Offer            = '';
	  $id_Country_Offer            = array();
	  $isActive_Offer              = 0;
	  $isSensitive_Offer           = 0;
	  $link_Offer                  = '';
	  $unsub_Offer                 = '';
	  $notWorkingDays_Offer        = array();
	  $froms                       = '';
	  $subjects					   = '';
	  $id_Vertical_Offer		   = 0;
	  $buttonText                  = 'Insert';
	  $TypeSuppressionFile_Offer   = '';
	  
	  if(isset($_GET['id_Offer']))
	  {
	    $buttonText         = 'Update';
	    $id_Offer = $_GET['id_Offer'];
	    $requete = $bdd->prepare('select * from offer where id_Offer = ?');
	    $requete->execute(array($id_Offer));
		extract($requete->fetch());
		
		$id_Country_Offer = explode(',',$id_Country_Offer);
		$notWorkingDays_Offer = explode(',',$notWorkingDays_Offer);
		
		$requete = $bdd->prepare('select text_From from froms where id_Offer_From = ?');
		$requete->execute(array($id_Offer));
		$result = $requete->fetchAll();
		for($i=0;$i<count($result);$i++)
		{
		    if($i<count($result)-1)
				$froms.=$result[$i]['text_From'].PHP_EOL;
			else
			    $froms.=$result[$i]['text_From'];
		}
		
		
		$requete = $bdd->prepare('select text_Subject from subjects where id_Offer_Subject = ?');
		$requete->execute(array($id_Offer));
		$result = $requete->fetchAll();
		for($i=0;$i<count($result);$i++)
		{
		    if($i<count($result)-1)
				$subjects.=$result[$i]['text_Subject'].PHP_EOL;
			else
			    $subjects.=$result[$i]['text_Subject'];
		}
		
		
		
	  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>Offer</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Offer</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Offer</li>
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
					
					<form class="form-horizontal" method="POST" action="IU_Offer_Post.php" enctype="multipart/form-data">
					
					    <fieldset class="content-group">
									<legend class="text-bold"><?php echo $buttonText;?> Offer</legend>

									
									
									<div class="form-group">
										<label class="control-label col-lg-2">SID</label>
										<div class="col-lg-10">
											<input type="text" name="txtSIDoffer" id="txtSIDoffer" type="number" class="form-control" value="<?php echo $sid_Offer;?>">
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Name</label>
										<div class="col-lg-10">
											<input type="text" name="txtNameOffer" id="txtNameOffer" class="form-control" value="<?php echo $name_Offer;?>">
										</div>
									</div>

									
									<div class="form-group">
										<label class="control-label col-lg-2">Vertical</label>
										<div class="col-lg-10">
										    <select name="cmbVerticals" id="cmbVerticals" class="select-clear" data-placeholder="Select Domain Provider">
										    <?php
											   $requete = $bdd->query('select * from vertical');
											   while($row = $requete->fetch())
											   {
								                     ?>  <option value="<?php echo $row['id_Vertical'];?>"  <?php echo ($row['id_Vertical'] == $id_Vertical_Offer) ? 'selected' : '';?>><?php echo $row['name_Vertical'];?></option>  <?php
											   }
											  ?>
											 </select>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Sponsor</label>
										<div class="col-lg-10">
										    <select name="cmbSponsors" id="cmbSponsors" class="select-clear" data-placeholder="Select Domain Provider">
										    <?php
											   $requete = $bdd->query('select * from sponsor where isActive_Sponsor = 1');
											   while($row = $requete->fetch())
											   {
								                     ?>  <option value="<?php echo $row['id_Sponsor'];?>"  <?php echo ($row['id_Sponsor'] == $id_Sponsor_Offer) ? 'selected' : '';?>><?php echo $row['name_Sponsor'];?></option>  <?php
											   }
											  ?>
											 </select>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Froms</label>
										<div class="col-lg-10">
											<textarea type="text" name="txtFroms" id="txtFroms" class="form-control"><?php echo $froms;?></textarea>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Subjects</label>
										<div class="col-lg-10">
											<textarea type="text" name="txtSubjects" id="txtSubjects" class="form-control"><?php echo $subjects;?></textarea>
										</div>
									</div>
									
									    
										
										<div id="creativeRow">
											<div class="form-group">
												<label class="control-label col-lg-2">Creative</label>

												<div class="col-lg-10">
												<table>
												   <tr>
												     <td><input type="file" name="creatives[]" class="form-control" style="width:600px;dispaly:inline;"></td>
												     <td style="padding-left:50px;"><input type="checkbox" name="isUnsub[]" id="1" value="1"/></td>
													 <td style="padding-left:50px;"><a class="btn border-success-400 text-success-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Add" id="btnAddRow"><i class="icon-plus3"></i></a></td>
												   </tr>
												</table>
													
													
												</div>
												
										   </div>
									   </div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Country</label>
										<div class="col-lg-10">
										   <?php
										     $requete  = $bdd->query('select * from country');
											 while($row = $requete->fetch())
											 {
											     echo $row['name_Country'];?><input type="checkbox" name="chkCountrys[]" value="<?php echo $row['id_Country'];?>" <?php if(in_array($row['id_Country'],$id_Country_Offer)){echo 'checked';};?>/><?php 
											 }
										   ?>
										</div>
									</div>
									
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Is Active</label>
										<div class="col-lg-10">
											<input type="checkbox" name="chkIsActive" id="chkIsActive"  <?php echo ($isActive_Offer == 1) ? 'checked' : '';?>>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Is Sensitive</label>
										<div class="col-lg-10">
											<input type="checkbox" name="chkIsSensitive" id="chkIsSensitive"  <?php echo ($isSensitive_Offer == 1) ? 'checked' : '';?>>
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Link</label>
										<div class="col-lg-10">
											<input type="text" name="txtLink" id="txtLink" class="form-control" value="<?php echo $link_Offer;?>">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Unsub</label>
										<div class="col-lg-10">
											<input type="text" name="txtUnsub" id="txtUnsub" class="form-control" value="<?php echo $unsub_Offer;?>">
										</div>
									</div>
									
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Suppression</label>
										<div class="col-lg-10">
											<input type="file" name="suppression" id="suppressions" class="form-control">
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">D'ont Treat Suppression</label>
										<div class="col-lg-10">
											<input type="checkbox" name="chkTreatSuppression" id="chkTreatSuppression"/>
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Type Suppression</label>
										<div class="col-lg-10">
											<input type="radio" name="radioTypeSuppression" value="text" <?php if($TypeSuppressionFile_Offer == "text"){echo 'checked';}?>/>Text	<input type="radio" name="radioTypeSuppression" value="md5" <?php if($TypeSuppressionFile_Offer == "md5"){echo 'checked';}?>/>MD5
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Not Working Days</label>
										<div class="col-lg-10">
											<table>
											  <tr>
											    <td>Monday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="0" <?php echo (in_array('0',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											  
											  <tr>
											    <td>Tuesday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="1" <?php echo (in_array('1',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											  
											  <tr>
											    <td>Wednesday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="2" <?php echo (in_array('2',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											  
											  <tr>
											    <td>Thursday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="3" <?php echo (in_array('3',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											  
											  <tr>
											    <td>Friday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="4" <?php echo (in_array('4',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											  
											  <tr>
											    <td>Saturday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="5" <?php echo (in_array('5',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											  
											  <tr>
											    <td>Sunday</td>
												<td><input type="checkbox" name="chkNotWorkingDays[]" value="6" <?php echo (in_array('6',$notWorkingDays_Offer)) ? 'checked' : '';?>/></td>
											  </tr>
											</table>
										</div>
									</div>
									
									
									
									
									
						</fieldset>
								
								<?php 
								if(isset($_GET['id_Offer']))
								{
								   ?>
								     
									 <table>
									    <?php
										$requete = $bdd->prepare('select id_Creative,name_Creative from creatives where id_Offer_Creative = ?');
										$requete->execute(array($_GET['id_Offer']));
										while($row = $requete->fetch())
										{
										   ?>
										     <tr>
											   <td><img src="../../Creatives/<?php echo $row['name_Creative'];?>" width="150px" height="150px;"/></td>
											   <td><input type="button" id="<?php echo $row['id_Creative'];?>" class="btnDeleteCreative" value="-"/></td>
											 </tr>
										   <?php
										}
										?>
									 </table>
								     <input type="hidden" name="idOffer" value="<?php echo $id_Offer;?>"/>
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

	<script>  
	   var cpt=1;
	   $('#btnAddRow').click(function(){
	      cpt++;
	      $('#creativeRow').append('<div class="form-group"><label class="control-label col-lg-2">Creative</label><div class="col-lg-8"><table><tr><td><input type="file" name="creatives[]" class="form-control" style="width:600px;"></td><td style="padding-left:50px;"><input type="checkbox" name="isUnsub[]" id="'+cpt+'" value="'+cpt+'"/></td><td style="padding-left:50px;"><a class="btn border-danger-400 text-danger-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnRemoveRow" title="Remove"><i class=" icon-trash"></i></a></td></tr></table></div></div>');
		  
		  
		  $('#'+cpt).parent().next().children().click(function(){
	     var currentID = parseInt($(this).parent().prev().children().attr('id'));
		 var loop = currentID;
		 $(this).parent().parent().parent().parent().parent().parent().remove();
			  for(var i=loop+1;i<=cpt;i++)
			  {
				 $('#'+i).attr('id',currentID).val(currentID);
				 currentID++;
			  }
			  cpt = currentID-1;
		 
	     });
		  
		  
	   });
	   
	   $('.btnDeleteCreative').click(function(){
	      var idCreative = $(this).attr('id');
		  $(this).parent().parent().remove();
		  $.post('D_Creative.php',{id_Creative:idCreative},function(){
		     
		  });
	   });
	   
	   
	   
	   
	   
	   
	   
	   
   
   
	</script>
</body>
</html>
