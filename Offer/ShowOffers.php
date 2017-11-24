<?php
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Show Offers</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Offers</span> - Show</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
					<ul class="breadcrumb">
						<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
						<li>Offers</li>
						<li class="active">Show</li>
					</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>Name</th>
									<th>Sponsor</th>
									<th>Is Active</th>
									<th>Is Sensitive</th>
									<th>Not Working Days</th>
									<th>Actions</th>
									
								</tr>
							</thead>
							
							<tbody>
							
							<?php
							    include('../Includes/bdd.php');
							   $requete = $bdd->query("select o.*,s.name_Sponsor from offer o , sponsor s where o.id_Sponsor_Offer = s.id_Sponsor and s.isActive_Sponsor = 1");
							   while($row = $requete->fetch())
							   {
							?>
									<tr>
										<td><?php echo $row["name_Offer"];?></td>
										<td><?php echo $row["name_Sponsor"];?></td>
										<td><?php echo ($row['isActive_Offer']==1) ? 'Yes' : 'No'?></td>
										<td><?php echo ($row['isSensitive_Offer']==1) ? 'Yes' : 'No'?></td>
										<td>
										  <?php
										     if($row['notWorkingDays_Offer']=='')
											   echo '';
											 else
											 {
											    $explode = explode(',',$row['notWorkingDays_Offer']);
												foreach($explode as $day)
												{
												     switch($day)
													 {
													     case '0':
														   echo 'Monday,';
														 break;
														 
														 case '1':
														   echo 'Tuesday,';
														 break;
														 
														 case '2':
														   echo 'Wednesday,';
														 break;
														 
														 case '3':
														   echo 'Thursday,';
														 break;
														 
														 case '4':
														   echo 'Friday,';
														 break;
														 
														 case '5':
														   echo 'Saturday,';
														 break;
														 
														 case '6':
														   echo 'Sunday';
														 break;
													 }
												}
											 }
										  ?>
										</td>
										<td>
										<a href="IU_Offer.php?id_Offer=<?php echo $row["id_offer"];?>" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update">
											<i class="icon-pencil"></i>
										</a>
										<a class="btn border-danger-400 text-danger-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnDelete" title="Delete" id="<?php echo $row["id_offer"];?>" data-toggle="modal" data-target="#modal_theme_danger">
											<i class="icon-trash"></i>
										</a>

										<a class="btnDownloadSuppressionFile btn border-info-400 text-info-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnDownloadSuppressionFile" title="Download Suppression File"  data-offer-id="<?php echo $row["id_offer"];?>">
											<i class="icon-file-download"></i>
										</a>
										
										</td>
									</tr>
							<?php
								}
							?>
								
								
							</tbody>
						</table>
					
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

	
<div id="modal_theme_danger" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="modal-title">Confirmation Alert</h6>
			</div>

			<div class="modal-body">
				<h6 class="text-semibold">Are You Sure ?</h6>
				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="btnDeleteModal">Delete</button>
			</div>
		</div>
	</div>
</div>




<script>
	  var idOffer;
	  $('.btnDelete').click(function(){
	      idOffer = $(this).attr('id');
	  });
	  
	  $('#btnDeleteModal').click(function(){
	     $.post('D_Offer.php',{id_Offer:idOffer},function(){
		    location.reload();
		 });
	  });
	  
	  
	  $('.btnDownloadSuppressionFile').click(function()
	  {		
			idOffer	=	$(this).attr('data-offer-id'); 
			$.post
			(
				'./Suppression/api_suppression_file.php',
				{id_Offer:idOffer},
				function(ajax_return)
				{
					console.log(ajax_return);
					alert(ajax_return);
				}
			);
	  });
</script>
</body>
</html>
