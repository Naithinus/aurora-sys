<?php
     
     include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 if($_SESSION['type_Employer']=="Mailer" || $_SESSION['type_Employer']=="Team Leader")
	   header('location:ShowSends.php');
   
	include('../Includes/bdd.php');
	
    $id_Send = $_GET['id_Send'];
	
	$requete = $bdd->prepare('select o.name_Offer from offer o , send s where s.id_Offer_Send = o.id_offer and s.id_Send = ?');
	$requete->execute(array($id_Send));
	$row = $requete->fetch();
	$name_Offer = $row['name_Offer'];
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - DASHBOARD</title>
	
	
	
	
	
	
	
	<?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	

	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	
    <script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">EXT</span> - DASHBOARD</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active">DASHBOARD</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal">
					
					    <fieldset class="content-group">
						           
									<legend class="text-bold">Show Send History : 
									
									
									<span class="heading-text text-bold">
											<span class="badge bg-primary">
												<?php echo $id_Send;?>
											</span>  
											
											<span class="label bg-danger">
												<?php echo $name_Offer;?>
											</span>
										</span>
																		
									
									</legend>
									
									
									
									<div class="form-group">
										
										<center>
										
										<table>

										<?php
										  $requete = $bdd->prepare('select * from send_history where id_Send = ? order by id_send_history desc');
										  $requete->execute(array($id_Send));
										  while($row = $requete->fetch())
										  {

										?>
										<tr>
										
										  <td style="maring-right:50px;padding-bottom:100px;">
										  <h3 style="padding-right:50px;"><?php echo $row['dateUpdate'];?></h3>
										  </td>
										  
										  <td style="maring-right:50px;padding-bottom:100px;">
										  <span class="label bg-success-400" style="width:100%">Header</span>
										  <textarea rows="8" cols="80" class="form-control"><?php echo $row['header'];?></textarea>
										  </td>
										  
										 <td style="padding-left:50px;padding-bottom:100px;">
										 <span class="label bg-success-400" style="width:100%">Body</span>
										 <textarea rows="8" cols="80" class="form-control"><?php echo $row['body'];?></textarea>
										 </td>
										  
										</tr>
										
										 <?php
										  }
										  ?>
										</table>
											
										</center>
										
								    </div>
									
									
											
									
									
						</fieldset>
								
								
								
								
					</form>
					
					    </div>
					</div>
					<!-- /form horizontal -->

					
					<!-- Footer -->
					<?php include('../Includes/footer.php'); ?>
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
	 
	
	</script>
</body>
</html>
