<?php
	 include_once('../Includes/sessionVerification.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	include('../Includes/bdd.php');
	
	$buttonText 			                   = isset($_GET['update']) ? 'Update' : 'Insert';
	$id_Server			       		           = $_GET['id_Server'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $buttonText.' ';?>IPS</title>
	
	
	
	
	
	
	
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
	
	
	
	?>
    


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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">IPS</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>IPS</li>
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
					
					
					<form class="form-horizontal" method="POST" action="IU_IP_POST.php">
					
					    <fieldset class="content-group" id="cont">
									<a class="btn border-success-400 text-success-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Add" id="btnAddRow"><i class="icon-plus3"></i></a>
									<legend class="text-bold"><?php echo $buttonText;?> IPS</legend>
									<br/>
									<?php
									
									if(isset($_GET['update']))
									{
										$ids=array();
										$ips=array();
										$Namedomain=array();
									   $requete = $bdd->prepare('select * from ip,domain where id_Server_IP = ? and domain.id_Domain=ip.id_Domain_IP');
									   $requete->execute(array($id_Server));
									   while($row = $requete->fetch())
									   {
										   $ids[]=trim($row["id_IP"]);
										   $ips[]=trim($row["IP_IP"]);
										   $domains[]=trim($row["id_Domain_IP"]);
										   $Namedomain[]=$row["name_Domain"];
									   }
									   for($i=0;$i<count($ids);$i++)
									   {
										   ?>
											
											
											<div class="form-group ipRow">
												<input type="hidden" name="id_IPS[]" id="<?php echo $ids[$i];?>" value="<?php echo $ids[$i];?>"/>
												<label class="control-label col-lg-2">IP</label>
												
												<div class="col-lg-2">
													<input type="text" name="txtIP[]" class="form-control" value="<?php echo $ips[$i]?>">
												</div>
												
												<div class="col-lg-2 cmbDomain">
													<select name="cmbDomain[]" class="select-clear" data-placeholder="Select Domain">
														<option value="<?php echo $domains[$i];?>>"<?php echo  $Namedomain[$i];?></option>
													</select>
												</div>
												<a class="btn border-danger-400 text-danger-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom deleteIP" title="Delete" data-toggle="modal" data-target="#modal_theme_danger"><i class="icon-trash"></i></a>
												
											</div>
											<?php
									    }

									}
									
									
									else
									{
										?>
										
										<div class="form-group ipRow">
											 <?php 
												$requete = $bdd->query('select * from domain');
											 ?>
												 <div class="col-md-4">
													 <label>IPS</label>
														<textarea name="txtIP[]" rows="3" class="form-control" style="resize:none;overflow-y: scroll;"></textarea>
													</div>
											
											
												<div class="col-md-4">
													<label class="">Domains</label>
													<select name="cmbDomain[]" class="select-clear" data-placeholder="Select Domain">
													  <?php
														while($row = $requete->fetch())
														{
														  ?>
															<option value="<?php echo $row['id_Domain'];?>"><?php echo $row['name_Domain'];?></option>
														  <?php
														}
													  ?>
													</select>
												</div>
										</div>
										<?php
									}
									?>
						</fieldset>
								
								
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary"><?php echo $buttonText;?><i class="icon-arrow-right14 position-right"></i></button>
								</div>
								
								
								
								      <input type="hidden" name="id_Server" id="id_Server" value="<?php echo $id_Server;?>"/>
								 
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
				<button type="button" class="btn btn-danger" id="btnDeleteModal" data-dismiss="modal">Delete</button>
			</div>
		</div>
	</div>
</div>

	
	<script>
	 
	
	 
	 $('#btnAddRow').click(function(){
	    var combo = $('.cmbDomain:eq(0)').html();
		
		
		$('#cont').append('<div class="form-group ipRow"><div class="col-md-4"><textarea name="txtIP[]" class="form-control" style="resize:none;overflow-y: scroll;"></textarea></div><div class="col-md-4 cmbDomain"> <?php $requete = $bdd->query('select * from domain');	echo '<select name="cmbDomain[]" class="form-control">';while($row = $requete->fetch()){echo '<option value="'.$row['id_Domain'].'">'.$row['name_Domain'].'</option>';}echo '</select>';?></div><a class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom removeRow" title="Delete ROW"><i class="icon-minus3"></i></a></div>'); 
		$('.removeRow').click(function(){
				$(this).parent().remove();
		 });
	 });
	 
	 var id_IP;
	 
	 
	 $('.deleteIP').click(function(){
	    id_IP = $(this).prev().prev().prev().prev().attr('id');
		
	 });
	 
	 
	 $('#btnDeleteModal').click(function(){
	   
	     $.post('D_IP.php',{idIP : id_IP},function(){
		    $('#'+id_IP).parent().remove();
			
		 });
	});
	  
	 
	</script>
</body>
</html>
