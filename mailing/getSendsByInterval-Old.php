
<?php

include_once('../Includes/sessionVerificationMailer.php'); 
 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 verify($monUrl);

include('../Includes/bdd.php');

$startDate = $_POST['startDate'];
$endDate   = $_POST['endDate'];

echo
'
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
';							
							
							    
								
								$mailerLastName = $_SESSION['lastName_Employer']; 
								
 
							    $requete = $bdd->prepare("select o.name_Offer, i.name_isp, s.* from send s , offer o , isp i where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp and s.id_Employer_Send=? and date(s.dateCreation) between ? and ? order by s.id_Send desc");
								$requete->execute(array($_SESSION['id_Employer'],$startDate,$endDate));
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
									
									echo
									'
									<tr>
										<td>'.$row["id_Send"].'</td>
										<td>'.$row["name_Offer"].'</td>
										<td>'.$row["name_isp"].'</td>
										<td>'.$listName.'</td>
										<td><input type="text" class="form-control" style="width:70px;"/></td>
										<td><input type="text" class="form-control" style="width:70px;"/></td>
										<td><input type="text" class="form-control" style="width:70px;"/></td>
										<td><span class="label bg-success-400">'.$countList.'</td>
										<td>				  
										  <a class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnEditIPS" title="Edit IPS" data-toggle="modal" data-target="#modal_form_inline"><i class="icon-more2"></i></a>
										  <a href="updateSend.php?id_Send='.$row["id_Send"].'" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
										  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="'.$row["id_Send"].'"><i class="icon-person"></i></a>
										  <a class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnSend" title="Send" id="'.$row["id_Send"].'"><div class="divLoading"><i class="icon-target2"></i></div></a>
										  <a href="ShowSendStats.php?id_Send='.$row["id_Send"].'" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
										  <a class="btn border-grey-400 text-grey-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnCopy" title="Copy Send" data-toggle="modal" data-target="#modal_form_copy"><i class="icon-copy3"></i></a>
										  <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnStop" title="Stop Send" data-toggle="modal" data-target=""><i class="icon-pause"></i></a>
										</td>
									</tr>
									';
							
								}
							
								
							echo'	
							</tbody>
						</table>';
						
?>	

<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="datatables_basic.js"></script>		
<script>

			  
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





</script>		