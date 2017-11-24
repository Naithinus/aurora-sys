<?php
     date_default_timezone_set('UTC');
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
	<title>EXT - Show Servers</title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Servers</span> - Show</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Servers</li>
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
									<th>Alias</th>
									<th>Provider</th>
									<th>User</th>
									<th>Sale Date</th>
									<th>Exp Date</th>
									<th>OS Server</th>
									<th>IP Main</th>
									<th>Domain Main</th>
									<th>Action</th>
								</tr>
							</thead>
							
							<tbody>
							
							<?php
							    include('../Includes/bdd.php');
							   $requete = $bdd->query("select s.* , sp.name_ServerProvider , o.name_OS , i.IP_IP , d.name_Domain from server s , serverprovider sp , os o , ip i , domain d where s.id_Server_Provider_Server = sp.id_ServerProvider and s.id_OS_Server = o.id_OS and s.id_IP_Server = i.id_IP and i.id_Domain_IP = d.id_Domain");
							   while($row = $requete->fetch())
							   {
							?>
									<tr>
										<td><?php echo $row["alias_Server"];?></td>
										<td><?php echo $row["name_ServerProvider"];?></td>
										<td><?php echo $row["username_Server"];?></td>
										<td><?php echo $row["saleDate_Server"];?></td>
										<td><?php echo $row["expirationDate_Server"];?></td>
										<td><?php echo $row["name_OS"];?></td>
										<td><?php echo $row["IP_IP"];?></td>
										<td><?php echo $row["name_Domain"];?></td>
										
										<td>
										<a href="IU_Server.php?id_Server=<?php echo $row["id_Server"];?>" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update"><i class="icon-pencil"></i>
										</a>
									    <a class="btn border-danger-400 text-danger-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnDelete" title="Delete" id="<?php echo $row["id_Server"];?>" data-toggle="modal" data-target="#modal_theme_danger"><i class="icon-trash"></i>
										</a>
										<a  class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnBuildConfig" id="<?php echo $row["id_Server"];?>" title="Build Config"><i class="icon-hammer-wrench"></i></a>
										<a  class="btn border-orange-400 text-orange-400 btn-flat btn-icon btn-md  btnRecords" id="<?php echo $row["id_Server"];?>" title="Add Records"><i class="fa fa-random"></i>
										</a>
										</td>
										<td></td>
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
	  var idServer;
	  $('.btnDelete').click(function(){
	      idServer = $(this).attr('id');
	  });
	  
	  $('#btnDeleteModal').click(function(){
	     $.post('D_Server.php',{id_Server:idServer},function(){
		    location.reload();
		 });
	  });
	  $('.btnRecords').click(function(){
		  idServer = $(this).attr('id');
	   $.post('namecheap_POST.php',{id_Server:idServer},function(data){
		  alert(data);
	   });
	   
	});
	  
	  
	$('.btnBuildConfig').click(function(e)
	{
		//var divLoading 	= $(this).children();
		//divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
		var id_server	=	$(this).attr('id');
		//alert(id_server);
		$.post
		(
			'../PMTA/build_pmta_config.php',
			{'id_server':id_server},
			function(result)
			{
				alert(result);
				//divLoading.html('').html('<i class="icon-hammer-wrench"></i>');
			}
		);	
	});
</script>
	
</body>
</html>
