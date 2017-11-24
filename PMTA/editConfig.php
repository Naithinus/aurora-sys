<?php
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl 					= 	"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	include('../Includes/bdd.php');	
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Edit Config PMTA</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Edit Config</span> PMTA</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active">Edit Config PMTA</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->

				<div class="panel panel-flat">
					    <div class="panel-body">
					
			
					<form class="form-horizontal" id='formEditConfig' name='formEditConfig' method=''>
					    <fieldset class="content-group">
									<legend class="text-bold">Edit Config PMTA</legend>

									
									<div class="form-group">
			                        	<label class="control-label col-lg-2">Servers</label>
			                        	<div class="col-lg-10">
										<select name="cmbServers" id="cmbServers" class="select-clear" data-placeholder="Selet Server">
                                               <option value="-1">Select Server</option>
				                            <?php
											$id_Isp = $_SESSION['id_Isp_Employer'];
											$requete = $bdd->query('select s.alias_Server ,s.id_Server,i.IP_IP from server s , ip i where s.id_IP_Server = i.id_IP');
											while($row = $requete->fetch())
											{
													    $requeteIsp = $bdd->prepare('select id_Server from serverisp where id_Server = ? and id_Isp = ?');
														$requeteIsp->execute(array($row['id_Server'],$id_Isp));
														$SubrowIsp = $requeteIsp->fetch();
														if($SubrowIsp)
														{
															$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 0');
															$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
															$SubrowMailer = $requeteMailer->fetch();
															if(!$SubrowMailer)
															{
																
																?><option value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option><?php
															}
														}
														
														else
														{
															
															$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
															$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
															$SubrowMailer = $requeteMailer->fetch();
															if($SubrowMailer)
															{
																
																?><option value="<?php echo $row['IP_IP'];?>"><?php echo $row['alias_Server'];?></option><?php
															}
															
														}
													
													
											}
											?>
										</select>	
			                            </div>
			                        </div>
									
										
									<div class="form-group">
										<label class="control-label col-lg-2"></label>
										<div class="col-lg-10" id="divFiles">
											
										</div>
									</div>
									
									
									
									
								</fieldset>
								
								<center>
									<input type="button" id="btnEdit" class="btn btn-primary" value="Edit Config" />
								</center>
						</form>		
					
					
				</div>
			</div>		

				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body" id="pnl">

							
					
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

var file = '';

$('#cmbServers').change(function(){

		var ip = $(this).val();
		
		$('#divFiles').html('');
		
		$.post('formGetContentPMTA.php',{link:ip},function(data){
			
			 $('#divFiles').html(data);
			 
			 $('.file').click(function(){
				 
				  var fileName = $(this).attr('id');
				  file		   = fileName;
				  
				  $.post('formGetFileContent.php',{link:ip,fileName:fileName},function(data){
				  
				     $('#txtContent').val(data);
					 
				  });
			 });
		});
	
});


$('#btnEdit').click(function(){

		var ip   = $('#cmbServers').val();
		var data = $('#txtContent').val();
		
		
		$.post('formSetFileContent.php',{link:ip,file:file,data:data},function(data){

			alert(data);
			
		});
	
	
});

/*
$('#btnGO').click(function()
{
  
  var isp       = $('#cmbISP').val();
  var email     = $('#txtEmail').val();
  var password  = $('#txtPassword').val();
  var folder    = $('#cmbFolder').val();
  
  $.post('imapPOST.php',{cmbISP : isp,txtEmail : email,txtPassword :  password , cmbFolder: folder},function(data){
     $('#pnl').html('').html(data);
  });
  
});
*/

</script>	
</body>
</html>
