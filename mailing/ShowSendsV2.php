<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 date_default_timezone_set('UTC');
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Show Sends</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>
	
	
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	
	
	
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	
	<style>
	.ranges li:hover
	{
		color:black;
	}
	</style>
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Show</span> - Sends</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="../indexOfficial.php">Send</a></li>
							<li class="active">Show Sends</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
								<div class="form-group">
										
											
											<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 30%">
												<i class="icon-calendar3"></i>&nbsp;
												<span></span> <b class="caret"></b>
											</div>
								</div>
									
								<br/>
								
								<center> <img src="loadingg.gif" id="loading"/> </center>
							<div id="sends">
							
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>ID SEND</th>
									<th>Offer</th>
									<th>ISP</th>
									<th>List</th>
									<th>Fraction</th>
									<th>Seed</th>
									<th>X-Delay</th>
									<th>Count</th>
									<th>Actions</th>
									
								</tr>
							</thead>
							
							<tbody>
							
							<?php
							    include('../Includes/bdd.php');
								
								$mailerLastName = $_SESSION['lastName_Employer']; 
								
 
							    $requete = $bdd->prepare("select o.name_Offer, i.name_isp, s.* from send s , offer o , isp i where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp and s.id_Employer_Send=? and date(s.dateCreation) between ? and ? order by s.id_Send desc");
								$requete->execute(array($_SESSION['id_Employer'],date("Y-m-d"),date("Y-m-d")));
							    while($row = $requete->fetch())
							    {
								   $idS            = $row['id_Send'];
								   
								   $subRequete     = $bdd->prepare('select l.name_List,tl.name_TypeList from sendlist sl , list l , typelist tl where sl.id_List = l.id_List and l.id_Type_List = tl.id_TypeList and sl.id_Send = ?');
								   $subRequete->execute(array($idS));
								   //$subRow = $subRequete->fetch();
								   
								   $data = $subRequete->fetchAll();
								   if(count($data)>1)
								    $listName = 'Mixed';
								   else if(count($data)==1)
								    $listName = $data[0][0].'-'.$data[0][1];
								   else
								   {
									   if($row['id_ISP_Send'] == 3)
									    $listName = 'Sender';
									   else
									    $listName = $row['extra'];
								   }
								    
									
								   $tableName      = $mailerLastName.$row['id_Send'];
							       $requeteCount   = $bdd->query("select count(*) from $tableName");
								   $rowCount	   = $requeteCount->fetch();
								   $countList      = 0;
								   
								   if(($rowCount[0] - $row['startFrom_Send']) > 0)
								      $countList = $rowCount[0] - $row['startFrom_Send'];
							?>
									<tr>
										<td><?php echo $row["id_Send"];?></td>
										<td><?php echo $row["name_Offer"];?></td>
										<td><?php echo $row["name_isp"];?></td>
										<td><?php echo $listName;?></td>
										<td><input type="text" class="form-control" style="width:70px;"/></td>
										<td><input type="text" class="form-control" style="width:70px;"/></td>
										<td><input type="text" class="form-control" style="width:70px;"/></td>
										<td><span class="label bg-success-400"><?php echo $countList;?></span></td>
										<td>				  
										  <a class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnEditIPS" title="Edit IPS" data-toggle="modal" data-target="#modal_form_inline"><i class="icon-more2"></i></a>
										  <a href="updateSend.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
										  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="<?php echo $row["id_Send"];?>"><i class="icon-person"></i></a>
										  <a class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnSend" title="Send" id="<?php echo $row["id_Send"];?>"><div class="divLoading"><i class="icon-target2"></i></div></a>
										  <a href="ShowSendStats.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
										  <a class="btn border-grey-400 text-grey-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnCopy" title="Copy Send" data-toggle="modal" data-target="#modal_form_copy"><i class="icon-copy3"></i></a>
										  <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnStop" title="Stop Send" data-toggle="modal" data-target=""><i class="icon-pause"></i></a>
										</td>
									</tr>
							<?php
								}
							?>
								
								
							</tbody>
						</table>
					
					</div>
					
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

	
<div id="modal_form_inline" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content text-center">
								<div class="modal-header">
									<h5 class="modal-title">Edit IPS</h5>
								</div>

								
								
									<div class="modal-body">
										<div class="form-group has-feedback">
											<label>IPS: </label><br/>
											<textarea class="form-control" id="txtIPS" rows="10"></textarea>
										</div>
									</div>
									
									<div class="modal-body">
										<div class="form-group has-feedback">
											<label>Email Test: </label><br/>
											<textarea type="text" class="form-control" id="txtEmailTestInside"></textarea>
										</div>
									</div>
									
									<div class="modal-body">
										<div class="form-group has-feedback">
											<label>Start From: </label><br/>
											<textarea type="text" class="form-control" id="txtStartFromInside"></textarea>
										</div>
									</div>
									
									

									<div class="modal-footer text-center">
										<button class="btn btn-primary" id="btnSaveIPS">EDIT<i class="icon-pencil"></i></button>
									</div>
								
							</div>
						</div>
</div>	


<div id="modal_form_copy" class="modal fade">
						<div class="modal-dialog" style="width:800px;">
							<div class="modal-content text-center">
								<div class="modal-header">
									<h5 class="modal-title">Copy Send</h5>
								</div>

								
								    <form method="POST" action="copySend.php">
									
										<div class="modal-body">
											
											<center>
											
											<div class="form-group has-feedback">
												<label>ISP: </label><br/>
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
											</div>
											
																
												<table id="tableData" class="table table-bordered table-striped">

												</table>
											</center>
						
						
										</div>

										<input type="hidden" name="idSendCopy" id="idSendCopy"/>
										
										<div class="modal-footer text-center">
											<button type="submit" class="btn btn-primary" id="btnCopy">Copy<i class="icon-pencil"></i></button>
										</div>
										
								    </form>
							</div>
						</div>
</div>



<script>


var idS = '';

$('#loading').hide();

$('.btnSend').click(function(){
   var divLoading = $(this).children();
   
   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().children().val();
   var Seed     = $(this).parent().prev().prev().prev().children().val();
   var xDelay   = $(this).parent().prev().prev().children().val();
   
   if(xDelay=="")
	  xDelay = 0;
   
   var counterObject = $(this).parent().prev().children();
   var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');
	  if(currentCount - Fraction > 0)
	    newCount = currentCount - Fraction;
	  counterObject.html(newCount);
   });
   
});



$('.btnTestSend').click(function(){
   var idSend   = $(this).attr('id');
   
   $.post('SendTestOutSide.php',{id_Send:idSend},function(data){
      alert(data);
   });
   
});



$('.btnEditIPS').click(function(){
	  
	  
	 idS = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
	  
	  $.post('getIPSSend.php',{type:"ips",idS:idS},function(data){
		  $('#txtIPS').val('').val(data);
	  });
	  
	   $.post('getIPSSend.php',{type:"emailTest",idS:idS},function(data){
		  $('#txtEmailTestInside').val('').val(data);
	  });
	  
	   $.post('getIPSSend.php',{type:"startFrom",idS:idS},function(data){
		  $('#txtStartFromInside').val('').val(data);
	  }); 
	  
});

$('#btnSaveIPS').click(function(){
	var ips       = $('#txtIPS').val();
	var emailTest = $('#txtEmailTestInside').val();
	var startFrom = $('#txtStartFromInside').val();
	
	$.post('editIPS.php',{ips:ips,emailTest:emailTest,startFrom:startFrom,idS:idS},function(data){
		$('#modal_form_inline').trigger('click');
	});
});


$('.btnCopy').click(function(){
	
	var idSendCopy = $(this).prev().prev().attr('id');
	$('#idSendCopy').val(idSendCopy);
});



$('#cmbIsps').change(function(){
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
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#tableData').html('');
});		
	

$('.btnStop').click(function(){
	
	var divLoading = $(this).prev().prev().prev().children();
	var idSendStop = $(this).prev().prev().prev().attr('id');
	
	$.post('getCurrentProcess.php',{idSend : idSendStop},function(data){
		var explode = data.split('\n');
		
		for(var i=0;i<explode.length;i++)
		{
		   if(explode[i].trim().length!=0)
		   {
			   var line = explode[i].split('-');
			   var host = line[0];
			   var pid  = line[1];
			   
			   /*var l = 'http://'+host+'/exactarget/Send/kill.php';
			   alert(l);*/
			   
			   $.post('http://'+host+'/exactarget/Send/kill.php',{pid:pid},function(data){

					divLoading.html('').html('<i class="icon-target2"></i>');
				    alert(data);
			   });
			   
		   }
		   
		}
		
	});
});	



	function cb(start, end) 
	{
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	}
	
	cb(moment().subtract(0, 'days'), moment());

    $('#reportrange').daterangepicker({
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
	
	
	
	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		  
		  $('#loading').show();
		  $('#sends').html('');
		  
		  var startDate = picker.startDate.format('YYYY-MM-DD');
		  var endDate   = picker.endDate.format('YYYY-MM-DD');
		  
		  $.post('getSendsByInterval.php',{startDate : startDate , endDate : endDate},function(data){
			  
			  $('#loading').hide();
			  $('#sends').html(data);	

			  $('.datatable-basic').on( 'draw.dt', function () {





			  
$(".btnSend").unbind( "click" );
$(".btnTestSend").unbind( "click" );
$(".btnEditIPS").unbind( "click" );
$(".btnSend").unbind( "click" );
$(".btnStop").unbind( "click" );
$(".btnCopy").unbind( "click" );


$('.btnSend').click(function(){
   var divLoading = $(this).children();
   
   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().children().val();
   var Seed     = $(this).parent().prev().prev().prev().children().val();
   var xDelay   = $(this).parent().prev().prev().children().val();
   
   if(xDelay=="")
	  xDelay = 0;
   
   var counterObject = $(this).parent().prev().children();
   var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');
	  if(currentCount - Fraction > 0)
	    newCount = currentCount - Fraction;
	  counterObject.html(newCount);
   });
   
});



$('.btnTestSend').click(function(){
   var idSend   = $(this).attr('id');
   
   $.post('SendTestOutSide.php',{id_Send:idSend},function(data){
      alert(data);
   });
   
});



$('.btnEditIPS').click(function(){
	  
	  
	 idS = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
	  
	  $.post('getIPSSend.php',{type:"ips",idS:idS},function(data){
		  $('#txtIPS').val('').val(data);
	  });
	  
	   $.post('getIPSSend.php',{type:"emailTest",idS:idS},function(data){
		  $('#txtEmailTestInside').val('').val(data);
	  });
	  
	   $.post('getIPSSend.php',{type:"startFrom",idS:idS},function(data){
		  $('#txtStartFromInside').val('').val(data);
	  }); 
	  
});

$('#btnSaveIPS').click(function(){
	var ips       = $('#txtIPS').val();
	var emailTest = $('#txtEmailTestInside').val();
	var startFrom = $('#txtStartFromInside').val();
	
	$.post('editIPS.php',{ips:ips,emailTest:emailTest,startFrom:startFrom,idS:idS},function(data){
		$('#modal_form_inline').trigger('click');
	});
});


$('.btnCopy').click(function(){
	
	var idSendCopy = $(this).prev().prev().attr('id');
	$('#idSendCopy').val(idSendCopy);
});



$('#cmbIsps').change(function(){
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
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#tableData').html('');
});		
	

$('.btnStop').click(function(){
	
	var divLoading = $(this).prev().prev().prev().children();
	var idSendStop = $(this).prev().prev().prev().attr('id');
	
	$.post('getCurrentProcess.php',{idSend : idSendStop},function(data){
		var explode = data.split('\n');
		
		for(var i=0;i<explode.length;i++)
		{
		   if(explode[i].trim().length!=0)
		   {
			   var line = explode[i].split('-');
			   var host = line[0];
			   var pid  = line[1];
			   
			   /*var l = 'http://'+host+'/exactarget/Send/kill.php';
			   alert(l);*/
			   
			   $.post('http://'+host+'/exactarget/Send/kill.php',{pid:pid},function(data){

					divLoading.html('').html('<i class="icon-target2"></i>');
				    alert(data);
			   });
			   
		   }
		   
		}
		
	});
});









   
				   
				   


 
			  });
			  
			  
		  });
		  
	});
	
	
</script>	
</body>
</html>
