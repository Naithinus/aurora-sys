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
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="../assets/js/pages/picker_date.js"></script>
	
	

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
					
					<form class="form-horizontal" method="POST" action="prepareSendPost.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold">Prepare Send</legend>

									<div class="form-group">
										<label class="control-label col-lg-2"></label>
										<table>
										  <tr>
										     <td style="padding-right:50px;padding-bottom:50px;;padding-bottom:50px;">
											    <span class="label bg-success heading-text">Sponsor</span><br/>
											   <select name="cmbSponsors" id="cmbSponsors" class="select-clear" data-placeholder="Select Sponsor" style="width:200px;">
											     <option value="-1">Select Sponsor</option>
												 <?php
												    $requete = $bdd->query('select * from sponsor where isActive_Sponsor = 1');
													while($row = $requete->fetch())
													{
													   ?> <option value="<?php echo $row['id_Sponsor'];?>"><?php echo $row['name_Sponsor'];?></option><?php
													} 
												 ?>
											   </select>
											   
											 </td>
											 <td style="padding-right:50px;padding-bottom:50px;">
											 <span class="label bg-success heading-text">Vertical</span><br/>
											 <select name="cmbVerticals" id="cmbVerticals" class="select-clear" data-placeholder="Select Vertical" style="width:200px;">
											     <option value="-1">Select Vertical</option>
												 <?php
												    $requete = $bdd->query('select * from vertical');
													while($row = $requete->fetch())
													{
													   ?> <option value="<?php echo $row['id_Vertical'];?>"><?php echo $row['name_Vertical'];?></option><?php
													} 
												 ?>
											   </select>
											 
											 </td>
											 <td style="padding-right:50px;padding-bottom:50px;">
											 <span class="label bg-success heading-text">Offer</span><br/>
											 <select name="cmbOffers" id="cmbOffers" class="select-clear" data-placeholder="Select Offer" style="width:200px;">
											     <option value="-1">Select Offer</option>
											 </select>
											 
											 </td>
										  </tr>
										  
										  
										  
										    <tr>
										     <td style="padding-right:50px;padding-bottom:50px;">
											    <span class="label bg-success heading-text">Froms</span><br/>
											   <select name="cmbFroms" id="cmbFroms" class="select-clear" data-placeholder="Select From" style="width:200px;">
											       <option value="-1">Select From</option>
											   </select>
											   
											 </td>
											 <td style="padding-right:50px;padding-bottom:50px;">
											    <span class="label bg-success heading-text">Subjects</span><br/>
											 <select name="cmbSubjects" id="cmbSubjects" class="select-clear" data-placeholder="Select Subject" style="width:200px;">
											        <option value="-1">Select Subject</option>
											  </select>
											 
											 </td>
											 <td style="padding-right:50px;padding-bottom:50px;">
											    <span class="label bg-success heading-text">ISP</span><br/>
											 <select name="cmbIsps" id="cmbIsps" class="select-clear" data-placeholder="Select ISP" style="width:200px;">
											      <option value="-1">Please Select...</option>
												 
												 <?php
												    $requete = $bdd->query('select * from isp');
													while($row = $requete->fetch())
													{
													   ?> <option value="<?php echo $row['id_Isp'];?>"><?php echo $row['name_isp'];?></option><?php
													} 
												 ?>
											   </select>
											 
											 
											 </td>
											 
				
										  </tr>
										  
										  
										  
										  <tr>
										     <td style="padding-right:50px;padding-bottom:50px;">
											    <span class="label bg-success heading-text">TARGET</span><br/>
												
											   <select name="cmbTarget" id="cmbTarget" class="select-clear" data-placeholder="Select Target" style="width:200px;">
											       <option value="-1">Select Criteria...</option>
												   <option value="1">Openers Vertical</option>
												   <option value="2">Clickers Vertical</option>
											   </select>
											   
											 </td>
											 
											 
											 
											 
											 <td style="padding-right:50px;padding-bottom:50px;" id="tdVerticalTarget">
											 
											 <span class="label bg-success heading-text">Vertical</span><br/>
											 <select name="cmbVerticalsTargets" id="cmbVerticalsTargets" class="select-clear" data-placeholder="Select Vertical" style="width:200px;">
											     <option value="-1">Select Vertical</option>
												 <?php
												    $requete = $bdd->query('select * from vertical');
													while($row = $requete->fetch())
													{
													   ?> <option value="<?php echo $row['id_Vertical'];?>"><?php echo $row['name_Vertical'];?></option><?php
													} 
												 ?>
											   </select>
											 
											 </td>
											 
											 
											
											
											 
				
										  </tr>
										  
										  
										  
										 <tr>
										    
											<!--
											<td style="padding-right:50px;padding-bottom:50px;">
											    <span class="label bg-success heading-text">Track Sender</span><br/>
											    <input type="checkbox" name="chkTrackSender" id="chkTrackSender"/>
											 </td>
											 
											
											<td style="padding-right:50px;padding-bottom:50px;">
											    <span class="label bg-success heading-text">Sender DATA</span><br/>
											    <input type="checkbox" name="chkSenderData" id="chkSenderData"/>
											</td>
											-->
											 
											 
										</tr>	 
										  
										  <tr>
										    <td colspan="3">
											    
												<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="background-color:#FFF;border-left:4px solid #2196F3;">
												
												</div>
											
											</td>
											
										  </tr>
										  
										  
										</table>
										
										
									</div>
									
<br/>	<br/>
<legend class="text-bold"></legend>	
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
	  <input type="text" class="form-control" name="txtReturnPath" id="txtReturnPath" value="return@[domain]"/>
	  </td>
   </tr>
   
      <tr>
      <td colspan="2" style="padding-right:50px;padding-bottom:50px;">
	  <span class="label bg-success heading-text">Header : [to] - [domain] - [date] - [file]</span><br/>
	  <textarea class="form-control" name="txtHeader" id="txtHeader" style="width:600px;" rows="8">
fromName:--
fromEmail: <contact@[domain]>
subject:--
date:[date]
to:[to]
reply-to:<reply@[domain]>
content-type:text/html;</textarea>
	  </td>
	  
	  
   </tr>
   
   
   <tr>
      <td colspan="2" style="padding-bottom:50px;">
	  <span class="label bg-success heading-text">Body : [domain] - [date] - [file]</span><br/>
	  <textarea class="form-control" name="txtBody" id="txtBody" style="width:1000px;height:300px;" rows="8">
<center>
<a href="http://[domain]/[idSend][RandomC/2][idEmail][RandomC/2][idFrom][RandomC/2][idSubject][RandomC/2][idCreative][RandomC/2][idIP]rr">
<img src="http://[domain]/[nameCreative]"/>
</a>
</center>
	  

<center>	  
<a href="http://[domain]/[idSend][RandomC/2][idEmail][RandomC/2][idFrom][RandomC/2][idSubject][RandomC/2][idCreative][RandomC/2][idIP]uu">
<img src="http://[domain]/[nameCreativeUnsub]"/>
</a>
</center>
<br/><br/>
<center>	  
	  <img style="width:0px;height:0px;display:none;" src="http://[domain]/[idSend][RandomC/2][idEmail][RandomC/2][idFrom][RandomC/2][idSubject][RandomC/2][idIP]=[sender]"/>
</center></textarea>
	  </td>
	  
	  
	
	
	 
   </tr>
   
   
   <tr>
     <td colspan="2" style="padding-bottom:50px;">
	        <span class="label bg-success heading-text">Body preview</span><br/>
			<div id="bodyPreview" style="width:600px;height:300px;background-color:#F8FAFC;border-left:4px solid #2196F3;border-top-right-radius:2px;border-bottom-right-radius:2px;">
			
			</div>
	</td>
	
   </tr>

   <?php if(  ($_SESSION['id_Employer']==0) or ($_SESSION['id_Employer']==18)  or ($_SESSION['id_Employer']==18)  or ($_SESSION['id_Employer']==1)  or  ($_SESSION['id_Employer']==5)  or ($_SESSION['id_Employer']==9) ):?>
	<tr>
		<td colspan="2" style="padding-bottom:50px;">
	        <span class="label bg-success heading-text">Auto Response</span><br/>
			<input type="checkbox" name="chkAR" id="chkAR"/>
			<textarea class="form-control" name="txtARList" id="txtARList" style="width:600px;height:300px;" rows="8"></textarea>
		</td>
	</tr>
   <?php endif;?>
   
  
  <tr id="lineNegative">
     <td colspan="2" style="padding-bottom:50px;">
	        <span class="label bg-success heading-text">Negatives</span><br/>
			<select name="cmbNegative" id="cmbNegative" class="select-clear" data-placeholder="Select Negative">
				
				<option value="0">Select Negative</option>
					 <?php
						$idMailer = $_SESSION['id_Employer'];
						
					    $requete = $bdd->prepare('select * from negative where id_mailer = ?');
						$requete->execute(array($idMailer));
						
						while($row = $requete->fetch())
						{
						   ?> <option value="<?php echo $row['id_negative'];?>"><?php echo $row['name_negative'];?></option><?php
						} 
					 ?>
			</select>
			
			<a id="reloadNegatives"><i class="icon-reload-alt"></i></a>
	</td>
	
   </tr>
   
   
   
   
</table>	

</div>							
									
						</fieldset>
							
							<input type="hidden" name="idCreative" id="idCreative"/>
					
					<legend class="text-bold">DATA</legend>
					
					<center>					
						<table id="tableData" class="table table-bordered table-striped">

						</table>
					</center>
			
			<br/>
								<div class="text-right">
									<button type="submit" class="btn btn-primary">Create Send<i class="icon-arrow-right14 position-right"></i></button>
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
	
	//$('#tableData').hide();
	
	$('#lineNegative').hide();
	
	$('.carousel').carousel({
  interval: 0
		});
		
		$('#cmbSponsors').change(function(){
		   $('#cmbVerticals').val('-1');
		   $('#cmbFroms').html('');
		   $('#cmbSubjects').html('');
		   $('.carousel').html('');
		   var idSponsor = $(this).val();
		   $.post('ajax.php',{type:'sponsor',id_Sponsor:idSponsor},function(data){
		      $('#cmbOffers').html(data);
		   });
		});
		
		$('#cmbVerticals').change(function(){
		   $('#cmbFroms').html('');
		   $('#cmbSubjects').html('');
		   $('.carousel').html('');
		   var idVertical = $(this).val();
		   var idSponsor = $('#cmbSponsors').val();
		   
		   $.post('ajax.php',{type:'vertical',id_Sponsor:idSponsor,id_Vertical:idVertical},function(data){
		      $('#cmbOffers').html(data);
		   });
		});
		
		
		$('#cmbOffers').change(function(){
		   var idOffer = $(this).val();
		   
		   $.post('ajax.php',{type:'from',id_Offer:idOffer},function(data){
		      $('#cmbFroms').html(data);
		   });
		   
		    $.post('ajax.php',{type:'subject',id_Offer:idOffer},function(data){
		      $('#cmbSubjects').html(data);
		   });
		   
		   $.post('ajax.php',{type:'creative',id_Offer:idOffer},function(data){
		      $('.carousel').html(data);
			  idCreative = $('.active:eq(0)').children().attr('id');
		      $('#idCreative').val(idCreative);
		   });
		   
		    
		   
		});
		
		
		$('#cmbIsps').change(function(){
			
		   $('#lineNegative').hide();
		   
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
		   
		   if(nameISP == 'hotmail')
			   $('#lineNegative').show();
		});
		
		
		
		
		$('#carousel-example-generic').on('slid.bs.carousel', function () {
		 idCreative = $('.active:eq(0)').children().attr('id');
		 $('#idCreative').val(idCreative);
		});

		
		$('#cmbFroms').change(function(){
		  var from = $("#cmbFroms option:selected").text();
		  var header = $('#txtHeader').val();
		  var newHeader = '';
		  var explode = header.split('\n');
		  
		  
		  for(var i=0;i<explode.length;i++)
		  {
		     var parametrs = explode[i].split(':');
			 if(parametrs[0]=='fromName')
			 {
			    if(i<explode.length-1)
				 newHeader+='fromName:'+from+'\n';
				else
				newHeader+='fromName:'+from;
			 }
			 else
			 {
			    if(i<explode.length-1)
				 newHeader+=explode[i]+'\n';
				else
				newHeader+=explode[i];
			 }
		  }
		  $('#txtHeader').val(newHeader);
		});
		
		
		
		$('#cmbSubjects').change(function(){
		  var subject = $("#cmbSubjects option:selected").text();
		  var header = $('#txtHeader').val();
		  var newHeader = '';
		  var explode = header.split('\n');
		  
		  
		  for(var i=0;i<explode.length;i++)
		  {
		     var parametrs = explode[i].split(':');
			 if(parametrs[0]=='subject')
			 {
			    if(i<explode.length-1)
				 newHeader+='subject:'+subject+'\n';
				else
				newHeader+='subject:'+subject;
			 }
			 else
			 {
			    if(i<explode.length-1)
				 newHeader+=explode[i]+'\n';
				else
				newHeader+=explode[i];
			 }
		  }
		  $('#txtHeader').val(newHeader);
		});
		
		
		$('#txtBody').keyup(function(){
		   var codeHTML = $(this).val();
		   $('#bodyPreview').html('').html(codeHTML);
		});
		
		$('#txtARList').hide();
		
		$('#chkAR').click(function(){
		   if($(this).is(":checked"))
		     $('#txtARList').show();
		   else
		     $('#txtARList').hide();
		});
		
		
		$('#reloadNegatives').click(function(){
			
			$.post('reloadNegative.php',{},function(data){
				
				$('#cmbNegative').html('').html(data);
				
			});
		});
		
		$('#cmbTarget').change(function(){
			
			var target = $(this).val();
			if(target == "1" || target == "2")
			{
				$("#tableData").hide();
				$('#tdVerticalTarget').show();
			}
				 
			else
			{
				$('#tdVerticalTarget').hide();
				$('#tableData').show();
			}
				
		});
		
		$('#tdVerticalTarget').hide();
	</script>
</body>
</html>
