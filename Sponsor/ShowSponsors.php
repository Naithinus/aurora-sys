<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Show Sponsors</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Sponsors</span> - Show</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="form_inputs_basic.html">Forms</a></li>
							<li class="active">Basic inputs</li>
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
									<th>Logo</th>
									<th>Is Active</th>
									<th>Actions</th>
									
									
								</tr>
							</thead>
							
							<tbody>
							
							<?php
							    include('../Includes/bdd.php');
								$id_connected_user		=	$_SESSION['id_Employer'];	

								$requete 			=	$bdd->query("select * from sponsor where isActive_Sponsor = 1");
								
								while($row = $requete->fetch())
								{
									
							?>
									<tr>
										<td><?php echo $row["name_Sponsor"];?></td>
										<td><img src="<?php echo 'Images/'.$row["logo_Sponsor"];?>" width="75px;"height="75px;"/></td>
										<td><?php echo ($row['isActive_Sponsor']==1) ? 'Yes' : 'No'?></td>
										<td>
										<?php
											if($_SESSION['type_Employer']!="Mailer" and $_SESSION['type_Employer']!="Team Leader"):
										?>
											<a href="IU_Sponsor.php?id_Sponsor=<?php echo $row["id_Sponsor"];?>" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update"><i class="icon-pencil"></i></a>
											<a class="btn border-danger-400 text-danger-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnDelete" title="Delete" id="<?php echo $row["id_Sponsor"];?>" data-toggle="modal" data-target="#modal_theme_danger"><i class="icon-trash"></i></a>
										<?php		
											endif;
										?>
										<a href="go_sponsor.php?id_sponsor=<?php echo $row["id_Sponsor"];?>" target="_blank" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Go to sponsor page" id="btnGoSponsor" name="btnGoSponsor"><i class="icon-chevron-right"></i></a>
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
	  var idSponsor;
	  $('.btnDelete').click(function(){
	      idSponsor = $(this).attr('id');
	  });
	  
	  $('#btnDeleteModal').click(function(){
	     $.post('D_Sponsor.php',{id_Sponsor:idSponsor},function(){
		    location.reload();
		 });
	  });
</script>

</body>
</html>
