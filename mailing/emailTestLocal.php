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
	<title>EXT - Prepare Send</title>
	
	
	

	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	
	
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="css/common.css" type="text/css" />
	<link type="text/css" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css" />
	<link type="text/css" href="css/ui.multiselect.css" rel="stylesheet" />
	
	<script type="text/javascript" src="js/plugins/localisation/jquery.localisation-min.js"></script>
	
	<script type="text/javascript" src="js/ui.multiselect.js"></script>

		<script type="text/javascript">
		$(function(){
			$.localise('ui-multiselect', {/*language: 'en',*/ path: 'js/locale/'});
			$(".multiselect").multiselect();
		});
	</script>
	
</head>

<body style="background-color:#F5F5F5;">
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Prepare</span> - Send</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Prepare Send</a></li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal" method="POST" id="formEmailTest">
					
					    <fieldset class="content-group">
									<legend class="text-bold">Prepare Send</legend>

									
<center>			
<div class="form-group">
				<label class="control-label col-lg-2"></label>								
<table style="position:relative;left:-50px;">
   <tr>
      <td style="padding-right:50px;padding-bottom:50px;">
	    <span class="label bg-success heading-text">Email Test</span><br/>
	  <input type="text" class="form-control" name="txtEmailTest" id="txtEmailTest"/>
	  </td>
	  
	  <td style="padding-right:50px;padding-bottom:50px;">
	    <span class="label bg-success heading-text">Return Path</span><br/>
	  <input type="text" class="form-control" name="txtReturnPath" id="txtReturnPath"/>
	  </td>
   </tr>
   
      <tr>
      <td colspan="2" style="padding-right:50px;padding-bottom:50px;">
	  <span class="label bg-success heading-text">Header : [to] - [domain] - [date]</span><br/>
	  <textarea class="form-control" name="txtHeader" id="txtHeader" style="width:600px;" rows="8">
fromName:--
fromEmail: <contact@[domain]>
subject:--
date:[date]
to:[to]
reply-to:--</textarea>
	  </td>
	  
	  
   </tr>
   
   
   <tr>
      <td colspan="2" style="padding-right:50px;padding-bottom:50px;">
	  <span class="label bg-success heading-text">FILE</span><br/>
	  <textarea class="form-control" name="txtFILE" id="txtFILE" style="width:600px;" rows="8"></textarea>
	  </td>
	  
	  
   </tr>
   
   
   
   <tr>
      <td colspan="2" style="padding-bottom:50px;">
	  <span class="label bg-success heading-text">Body : [to] - [domain] - [date]</span><br/>
	  <textarea class="form-control" name="txtBody" id="txtBody" style="width:600px;height:300px;" rows="8"></textarea>
	  </td>
	  
	  
	
	
	 
   </tr>
   
   
   <tr>
     <td>
	        <span class="label bg-success heading-text">Body preview</span><br/>
			<div id="bodyPreview" style="width:600px;height:300px;background-color:#F8FAFC;border-left:4px solid #2196F3;border-top-right-radius:2px;border-bottom-right-radius:2px;">
			
			</div>
	</td>
	
   </tr>

   
   
</table>	

<br/><br/>

<?php
   include('../Includes/bdd.php');
   
   $domain = $_SERVER['HTTP_HOST'];
   
   if(preg_match("/[0-9]+.[0-9]+.[0-9]+.[0-9]+/", $domain)==1)
   {
	     $requete = $bdd->prepare('select id_Server_IP from ip where IP_IP = ?');
		 $requete->execute(array($domain));
		 $row = $requete->fetch();
		 $idServer = $row['id_Server_IP'];
   }
	
   else
   {   
	   $requete = $bdd->prepare('select i.id_Server_IP from ip i , domain d where i.id_Domain_IP = d.id_Domain and d.name_Domain = ?');
	   $requete->execute(array($domain));
	   $row = $requete->fetch();
	   $idServer = $row['id_Server_IP'];
   }
   
   $requete = $bdd->prepare('select i.id_IP,i.IP_IP,d.name_Domain from ip i ,domain d where i.id_Server_IP = ? and i.id_Domain_IP = d.id_Domain');
   $requete->execute(array($idServer));
    
   ?>
		<select id="cmbIPS" class="multiselect" multiple="multiple" name="cmbIPS[]" style="width:200px;">
		  <?php
		    while($row = $requete->fetch())
			{
			  ?>
			    <option value="<?php echo $row['id_IP']?>"><?php echo $row['IP_IP'].' - '.$row['name_Domain'];?></option>
			  <?php
			}
		  ?>
		</select>
	  
	  
</div>							
									
						</fieldset>
							
							<input type="hidden" name="idCreative" id="idCreative"/>
					
					<center>					
						<table id="tableData" class="table table-bordered table-striped">

						</table>
					</center>
			
			<br/>
								<div class="text-right">
									<button type="button" id="btnTest" class="btn btn-primary">Create Send<i class="icon-arrow-right14 position-right"></i></button>
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
	
	$('#tableData').hide();
	
	$('#btnTest').click(function(){
	   var emailTest = $('#txtEmailTest').val();
	   var returnPath = $('#txtReturnPath').val();
	   var header = $('#txtHeader').val();
	   var body = $('#txtBody').val();
	   var ips = $('#cmbIPS').val();
	   var file = $('#txtFILE').val();
	   
	   $.post('emailTestLocal_POST.php',{txtFILE : file , txtEmailTest:emailTest,txtReturnPath:returnPath,txtHeader:header,txtBody:body,cmbIPS:ips},function(data){
	    alert(data);
	   });
	   
	});
		
		
	</script>
</body>
</html>
