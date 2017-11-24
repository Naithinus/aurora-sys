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
	<title>EXT - Show Employer Experiences</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic2.js"></script>

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Employer Experiences</span> - Show</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="form_inputs_basic.html">Employer Experiences</a></li>
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
									<th>Function</th>
									<th>ISP</th>
									<th>Date Begin</th>
									<th>Date End</th>
									
									
								</tr>
							</thead>
							
							<tbody>
							
							<?php
							    include('../Includes/bdd.php');
								$id_Employer = $_GET['id_Employer'];
								
							   $requete = $bdd->prepare("select t.name_type_Employer,i.name_Isp,e.dateBegin_Experience,e.dateEnd_Experience from Experience e,type_employer  t , isp i where e.id_Type_Employer_Experience = t.id_type_Employer and e.id_Isp_Experience = i.id_Isp and e.id_Employer_Experience = ?");
							   $requete->execute(array($id_Employer));
							   while($row = $requete->fetch())
							   {
							?>
									<tr>
										<td><?php echo $row["name_type_Employer"];?></td>
										<td><?php echo $row["name_Isp"];?></td>
										<td><?php echo $row["dateBegin_Experience"];?></td>
										<td><?php echo $row["dateEnd_Experience"];?></td>
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

</body>
</html>
