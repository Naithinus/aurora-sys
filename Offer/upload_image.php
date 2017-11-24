<?php
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl	=	"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 'On');
	include('../Includes/bdd.php');	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Upload Images</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Image</span> - Upload Image</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Offer</li>
							<li class="active">Images</li>
					    </ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat" id='fdl'>
					    <div class="panel-body">
					
					<form class="form-horizontal" enctype="multipart/form-data" id="upload_form" name="upload_form">
					
					    <fieldset class="content-group" >
									<legend class="text-bold">Upload Image</legend>

									
									<div class="form-group">
										<label class="control-label col-lg-2">Sponsor</label>
										<div class="col-lg-10">
										    <select name="cmbSponsors" id="cmbSponsors" class="select-clear" data-placeholder="Select Domain Provider">
										    <option value="-1">Please select</option>
											<?php
											   $requete = $bdd->query('select * from sponsor');
											   while($row = $requete->fetch())
											   {
													?>  <option value="<?php echo $row['id_Sponsor'];?>"  ><?php echo $row['name_Sponsor'];?></option>  <?php
											   }
											  ?>
											 </select>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Vertical</label>
										<div class="col-lg-10">
										    <select name="cmbVerticals" id="cmbVerticals" class="select-clear" data-placeholder="Select Domain Provider">
										    <option value="-1">Please select</option>
											<?php
											   $requete = $bdd->query('select * from vertical');
											   while($row = $requete->fetch())
											   {
								                     ?>  <option value="<?php echo $row['id_Vertical'];?>"  ><?php echo $row['name_Vertical'];?></option>  <?php
											   }
											  ?>
											 </select>
										</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="control-label col-lg-2">Offer</label>
										<div class="col-lg-10">
										    <select name="cmbOffers" id="cmbOffers" class="select-clear" data-placeholder="Select Offer">
												<option value="-1">Please select</option>
											 </select>
										</div>
									</div>
									
									
								<div id="creativeRow">
									<div class="form-group">
										<label class="control-label col-lg-2">Creative</label>

										<div class="col-lg-10">
											<table>
												<tr>
													<td>
														<input type="file" id="creatives" name="creatives" class="form-control" style="width:600px;dispaly:inline;"/>
													</td>
													<td style="padding-left:50px;">
														<input type="checkbox" name="isUnsub" id="isUnsub" value="0"/>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								<div class="text-center">
									<button type="submit" id="btnUploadImage" class="btn btn-primary">Upload <i class="icon-arrow-right14 position-right"></i></button>
									
								</div>
									
						</fieldset>
					</form>
				</div>
			</div>
					
					
			<div class="text-center">
					<img id="loader" src="http://exactarget.net/exactarget/images/loader.gif" width="120" height="120" />
			</div>
			<div class="panel panel-flat">
				<div class="panel-body" id="pnl-result">
			
					
			
				</div>
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
	  
		$('#pnl-result').hide();
		$('#loader').hide();
		
		
		//Fill Combo Offers :
		$('#cmbVerticals').change(function()
		{
			
			var idSponsor 	=	$('#cmbSponsors').val();
			var	idVertical	=	$('#cmbVerticals').val();
			//alert(idSponsor+'/'+idVertical);
			if(   (idSponsor>0) &&  (idVertical>0)    )
			{
				$('#cmbOffers').empty();
				$.post
				(
					'get_offers.php',
					{'id_sponsor':idSponsor,'id_vertical':idVertical},
					function(offers)
					{
						$('#cmbOffers').html(offers);
					}
				);
			}
		});
		
		
		//Upload Image :
		$('#upload_form').on('submit', function (e) 
		{
			e.preventDefault();
			if(check_data() > 0)
			{
				$('#loader').show();
				$('#btnUploadImage').hide();
				$('#fdl').hide();
				
				$.ajax
				({
					url:"upload_image_post.php", 
					type:"POST",             
					data:new FormData(this),
					contentType:false,
					cache:false,      
					processData:false, 
					success:function (response) 
					{
						$('#loader').hide();
						$('#pnl-result').show();
						$("#pnl-result").html(response);
					},
					error:function(jqXHR, exception)
					{
						alert('Error Ajax : an error was occured while uploading your image. Please retry again or contact your support team !');
					}
				});
			}
		});
		
		
		function check_data()
		{
			var	id_sponsor				=	$('#cmbSponsors').val();
			var id_offer				=	$('#cmbOffers').val();
			var	ok_image				=	0;
	
			//Sponsor :
			if(id_sponsor<=0)
			{
				alert("You have to choose the sponsor !");
				return false;
			}	
	
	
			//Offer :
			if(id_offer<=0)
			{
				alert("You have to choose the offer !");
				return false;
			}	
	
	
			//Image Offer :
			ok_image	=	is_valid_image('creatives');
			if(ok_image!=1)
			{
				alert(ok_image);
				return false;
			}
	
	
	
	
			return (id_sponsor && id_offer && ok_image);
		}
	   
		function is_valid_image(image_name)
		{
			var	result =	0;
			
			var val = $("#"+image_name).val();
			if(val=='')
			{
				result	=	'You have to choose the image to upload !';
			}
			else
			{
				if (!val.match(/(?:jpg|png|jpeg|JPG|PNG|JPEG)$/)) 
				{
					result	=	"Only images ending with '.jpg' or '.png' or '.jpeg' extension are accepted !";
				}
				else
				{
					result	=	1;
				}
			}
			
			return result;
		}
	   
	   
	   
	   
   
   
	</script>
</body>
</html>
