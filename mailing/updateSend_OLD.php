<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 include('../Includes/bdd.php');
	  
	  $mailer = $_SESSION['id_Employer'];
	  
	  $id_Send = $_GET['id_Send'];
	  $id_Offer_Send = '';
	  $id_ISP_Send = '';
	  $id_Employer_Send = '';
	  $header_Send = '';
	  $body_Send = '';
	  $emailTest_Send = '';
	  $returnPath_Send = '';
	  $IPS_Send = '';
	  $id_From_Send = '';
	  $id_Subject_Send = '';
	  $id_Creative_Send = '';
	  $startFrom_Send  = '';
	  $isAR			  = '';
	  $ARList		  = '';
	  $id_negative    = '';
	  
	  $requete = $bdd->prepare('select * from send where id_Send = ?');
	  $requete->execute(array($id_Send));
	  extract($requete->fetch());
	  
	  if($_SESSION['firstName_Employer'] != "ADMIN")
	  {
		  
		  if($mailer != $id_Employer_Send)
	        header('location:ShowSends.php');
		
	  }
		  
	  
	  
	  $requete = $bdd->prepare('select name_Offer from offer where id_offer = ?');
	  $requete ->execute(array($id_Offer_Send));
	  $row = $requete->fetch();
	  $name_Offer = $row['name_Offer'];
	  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - <?php echo $id_Send.'-'.$name_Offer;?></title>
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Send</span> - Update Send</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Send</a></li>
							<li class="active">Update Send</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					<center>
					<form class="form-horizontal" method="POST" action="updateSend_POST.php">
					
					    <fieldset class="content-group">
									<legend class="text-bold">Update Send : <span class="label bg-danger heading-text"><?php echo $id_Send;?></span>  <span class="label bg-danger heading-text"><?php echo $name_Offer;?></span></legend>

									<div class="form-group">
										<label class="control-label col-lg-2"></label>
										<table>
										  <tr>
										  
										     <td style="padding-right:0px;padding-bottom:50px;">
											 <span class="label bg-success heading-text">Froms</span><br/>
											   <select name="cmbFroms" id="cmbFroms" class="select-clear" data-placeholder="Select From" style="width:180px;">
												 <?php
												    $requete = $bdd->prepare('select * from froms where id_Offer_From = ?');
													$requete->execute(array($id_Offer_Send));
													while($row = $requete->fetch())
													{
													   ?> <option value="<?php echo $row['id_From'];?>" <?php echo ($row['id_From'] == $id_From_Send) ? 'selected' : '';?>><?php echo $row['text_From'];?></option><?php
													} 
												 ?>
											   </select>
											   
											 </td>
											 
											 
											 <td style="padding-right:0px;padding-bottom:50px;">
											   <span class="label bg-success heading-text">Subjects</span><br/>
											   <select name="cmbSubjects" id="cmbSubjects" class="select-clear" data-placeholder="Select Subject" style="width:180px;">
												 <?php
												    $requete = $bdd->prepare('select * from subjects where id_Offer_Subject = ?');
													$requete->execute(array($id_Offer_Send));
													while($row = $requete->fetch())
													{
													   ?> <option value="<?php echo $row['id_Subject'];?>" <?php echo ($row['id_Subject'] == $id_Subject_Send) ? 'selected' : '';?>><?php echo $row['text_Subject'];?></option><?php
													} 
												 ?>
											   </select>
											   
											 </td>
											 
											 
												 <td style="padding-right:0px;padding-bottom:50px;">
												    <span class="label bg-success heading-text">Email Test</span><br/>
												   <textarea class="form-control" name="txtEmailTest" id="txtEmailTest" style="width:270px;" rows="4"><?php echo $emailTest_Send;?></textarea>
												 </td>
	  
												<td style="padding-right:0px;padding-bottom:50px;">
												   <span class="label bg-success heading-text">Return Path</span><br/>
												  <input type="text" class="form-control" name="txtReturnPath" id="txtReturnPath" value="<?php echo $returnPath_Send;?>" style="width:180px;"/>
												</td>
											  
											  <td style="padding-right:0px;padding-bottom:50px;">
											   <span class="label bg-success heading-text">Start From</span><br/>
												  <input type="text" class="form-control" name="txtStartFrom" id="txtStartFrom" value="<?php echo $startFrom_Send;?>" style="width:180px"/>
											  </td>
	  
											 
										  </tr>
										  
										  

										  
										        <td colspan="4" style="padding-right:50px;padding-bottom:50px;">
												<span class="label bg-success heading-text">Header : [to] - [domain] - [date] - [file]</span><br/>
	  <textarea class="form-control" name="txtHeader" id="txtHeader" style="width:900px;" rows="8"><?php echo $header_Send;?></textarea>
	  </td>
	  
	  
										  
										  </tr>
										  
										    
										  
										  
										     <tr>
      <td colspan="4" style="padding-right:50px;padding-bottom:50px;">
	   <span class="label bg-success heading-text">Body : [domain] - [date] - [file]</span><br/>
	  <textarea class="form-control" name="txtBody" id="txtBody" style="width:900px;" rows="15"><?php echo htmlspecialchars($body_Send);?></textarea>
	  </td>
	  
	  
   </tr>
   
   <tr>
     <td colspan="4" style="padding-right:50px;padding-bottom:50px;">
	        <span class="label bg-success heading-text">Body preview</span><br/>
			<div id="bodyPreview" style="width:900px;height:300px;background-color:#F8FAFC;border-left:4px solid #2196F3;border-top-right-radius:2px;border-bottom-right-radius:2px;">
			
			</div>
	</td>
	</tr>
	
	
	<?php if(  ($_SESSION['id_Employer']==0)  or ($_SESSION['id_Employer']==1)  or  ($_SESSION['id_Employer']==5) ):?>
	<tr>
		<td colspan="2" style="padding-bottom:50px;">
	        <span class="label bg-success heading-text">Auto Response</span><br/>
			<input type="checkbox" name="chkAR" id="chkAR" <?php echo ($isAR == 1) ? 'checked' : '';?> value="<?php echo ($isAR == 1) ? '1' : '0';?>"/>
			<textarea class="form-control" name="txtARList" id="txtARList" style="width:600px;height:300px;" rows="8"><?php echo $ARList;?></textarea>
		</td>
	</tr>
	<?php endif;?>
   
   <?php
     if($id_ISP_Send == 5)
	 {
			?>
		   <tr>
				<td colspan="4" style="padding-bottom:50px;">
					
					<span class="label bg-success heading-text">Negatives</span><br/>
					<select name="cmbNegative" id="cmbNegative" class="select-clear" data-placeholder="Select Negative">
						
						<option value="0">Select Negative</option>
							 <?php
								$idMailer = $_SESSION['id_Employer'];
								
								$requete = $bdd->prepare('select * from negative where id_mailer = ?');
								$requete->execute(array($idMailer));
								
								while($row = $requete->fetch())
								{
								   ?> <option value="<?php echo $row['id_negative'];?>" <?php if($row['id_negative'] == $id_negative){echo "selected";};?>><?php echo $row['name_negative'];?></option><?php
								} 
							 ?>
					</select>
					
					<a id="reloadNegatives"><i class="icon-reload-alt"></i></a>
					
				</td>
			</tr>
			
			<?php
	 }
	 
   ?>
	
   
			
			
   <tr>
       
         <td colspan="4" style="padding-right:50px;padding-bottom:50px;">
		 <span class="label bg-success heading-text">IPS</span><br/>
	  <textarea class="form-control" name="txtIPS" id="txtIPS" style="width:900px;" rows="8"><?php echo $IPS_Send;?></textarea>
	  </td>
	  
	  
   </tr>
   
   
   
   
										</table>
										
										
									</div>
									
					<input type="hidden" id="id_Send" name="id_Send" value="<?php echo $id_Send;?>"/>
					
					<div class="text-right">
									<button type="button" id="btnTestMail" class="btn btn-primary">Send Test Mail<i class="icon-arrow-right14 position-right"></i></button>
									<button type="submit" class="btn btn-primary">Update<i class="icon-arrow-right14 position-right"></i></button>
					</div>
					

								
					</form>
					</center>
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
			  idCreative = $('.active:eq(1)').children().attr('id');
		      $('#idCreative').val(idCreative);
		   });
		   
		    
		   
		});
		
		
		$('#cmbIsps').change(function(){
		   var idIsp = $(this).val();
		   $.post('ajax.php',{type:'isp',id_Isp:idIsp},function(data){
		      $('#tableData').html(data);
			  $('#tableData').show();
		   });
		});
		
		
		
		
		$('#carousel-example-generic').on('slid.bs.carousel', function () {
		 idCreative = $('.active:eq(1)').children().attr('id');
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
		
		String.prototype.replaceAll = function(search, replacement) {
			var target = this;
			return target.split(search).join(replacement);
		};

		$('#txtBody').keyup(function(){
		   var codeHTML = $(this).val();
		   var ip = $('#txtIPS').val().split('\n')[0];
		   codeHTML = codeHTML.replaceAll('[domain]',ip);
		   $('#bodyPreview').html(codeHTML);
		});
		
		
		$('#btnTestMail').click(function(){
		
        var idSend = <?php echo $id_Send;?>;
		var idFrom = $('#cmbFroms').val();
		var idSubject = $('#cmbSubjects').val();
		var emailTest = $('#txtEmailTest').val();
		var returnPath = $('#txtReturnPath').val();
		var header = $('#txtHeader').val();
		var body = $('#txtBody').val();
		var ips = $('#txtIPS').val();
		var chkAR = $('#chkAR').val();
		var txtARList = $('#txtARList').val();
		var idnegative = $('#cmbNegative').val();
		
			$.post('SendTestInside.php',{id_Send:idSend,cmbFroms:idFrom,cmbSubjects:idSubject,txtEmailTest:emailTest,txtReturnPath:returnPath,txtHeader:header,txtBody:body,txtIPS:ips,chkAR:chkAR,txtARList:txtARList,idnegative:idnegative},function(data){
			    alert(data);
			});
		});
		
		
		var isAR = <?php echo $isAR;?>;
		if(isAR == 0)
		 $('#txtARList').hide();
		
		$('#chkAR').click(function(){
		   if($(this).is(":checked"))
		   {
		     $('#txtARList').show();
			 $('#chkAR').val(1);
		   }
		   else
		   {
		     $('#txtARList').hide();
			 $('#chkAR').val(0);
		   }
		});
		
		
		$('#reloadNegatives').click(function(){
			
			$.post('reloadNegative.php',{},function(data){
				
				$('#cmbNegative').html('').html(data);
				
			});
		});
		
		
	</script>
</body>
</html>
