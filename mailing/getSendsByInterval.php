
<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

include('../Includes/bdd.php');

$startDate = $_POST['startDate'];
$endDate   = $_POST['endDate'];
$page = $_POST["page"];
if ($page=="original") 
{
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
	   
	   $countList      = 0;
	   
	   if($requeteCount)
	   {
		   $rowCount	   = $requeteCount->fetch();
		   
		   if(($rowCount[0] - $row['startFrom_Send']) > 0)
			  $countList = $rowCount[0] - $row['startFrom_Send'];
	   }
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
	</table>';?>
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
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');
	  
	  var countListExplode      = data.split('/');
	  var countListFinal        = parseInt(countListExplode[1]);
	  
	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
	  
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
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
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
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

					divLoading.html('').html('<i class="icon-target2"></i>');
				    alert(data);
			   });
			   
		   }
		   
		}
		
	});
});





</script>
<?php
}
if ($page=="repeat") 
{
	echo
	'
	<table class="table datatable-basic">
	<thead>
	<tr>
		<th>ID</th>
		<th>Offer</th>
		<th>ISP</th>
		<th>List</th>
		<th>Fraction</th>
		<th>Seed</th>
		<th>X-Delay</th>
		<th>Repeat</th>
		<th>Count</th>
		<th>Actions</th>
		
	</tr>
	</thead>

	<tbody style="background-color: #fbfbfb">
	';							


	$mailerLastName = $_SESSION['lastName_Employer']; 


	$requete = $bdd->prepare("select o.name_Offer, i.name_isp, s.* from send s , offer o , isp i where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp and s.id_Employer_Send=? and date(s.dateCreation) between ? and ? order by s.id_Send desc");
	$requete->execute(array($_SESSION['id_Employer'],$startDate,$endDate));
	$count=1;
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
	   
	   $countList      = 0;
	   
	   if($requeteCount)
	   {
		   $rowCount	   = $requeteCount->fetch();
		   
		   if(($rowCount[0] - $row['startFrom_Send']) > 0)
			  $countList = $rowCount[0] - $row['startFrom_Send'];
	   }
	   $sendInfo=$bdd->query('Select IPS_Send from send where id_send="'.$row["id_Send"].'"');
	   $c=0;
	   while ($r=$sendInfo->fetch()){
		   if ($r[0]!=NULL){
			   $vmtas=explode(PHP_EOL,$r[0]);
			   $c=count($vmtas);
		   }
	   }?>
	   <tr>
			<td style="border-left: thin solid #719ba7;border-top: thin solid #719ba7;border-bottom: thin solid #719ba7"><?php echo $row["id_Send"];?></td>
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7;"><?php echo $row["name_Offer"];?></td>
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7"><?php echo $row["name_isp"];?></td>
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7"><?php echo $listName;?></td>
			
			<!-- FRACTION -->
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
				<br><br>
				<form id="FractionForm<?php echo $count?>">
					<input id="SelectedFraction<?php echo $count?>" onkeyup="calculateFraction(<?php echo $c?>,<?php echo $count ?>)" type="text" class="form-control" style="width:70px;"/>
				</form>
				<br>
				<div>
					<center>
					<span  id="FractionDiv<?php echo $count?>" align="bottom" class="label bg-danger-400">0 TOTAL<br>(0 x 1)  * <?php echo $c?>
					</span>
					</center>
				</div>
			</td>
			<!-- --->
			
			
			<!-- SEED -->
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
				<br><br>
				<input type="text" class="form-control" style="width:70px;"/>
				<br><center><span align="bottom" class="label bg-default-400">&#8291;<br>&#8291;</span></center>
			</td>
			
			<!-- X-DELAY -->
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
				<br><br>
				<form id="SecondsForm<?php echo $count?>">
					<input id="SelectedSeconds<?php echo $count?>" onkeyup="calculateSeconds(<?php echo $count?>)" type="text" class="form-control" style="width:70px;"/>
				</form>
				<br>
				
				<center><span id="SecondsDiv<?php echo $count?>" align="bottom" class="label bg-danger-400">0<br>Seconds</span></center>
				
			</td>
			<!-- -->
			
			<!-- REPEAT -->
			<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
				
				<br><br>
				<form id="LoopForm<?php echo $count?>">
					<input  id="SelectedLoop<?php echo $count?>" onkeyup="calculateLoop(<?php echo $count?>,<?php echo $c ?>)" value="1" type="text" class="form-control" style="width:70px;"/>
				</form>
				<br><center><span align="bottom"  class="label bg-danger-400"><?php echo $c."<br> VMTA"?></span></center>
				
			</td>
			<!--  -->
			
			<td align="center" style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
				<span class="label bg-success-400"><?php echo $countList;?></span></td>
			
			<td style="border-top: thin solid #719ba7;border-right: thin solid #719ba7;border-bottom: thin solid #719ba7">				  
			  <a class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnEditIPS" title="Edit IPS" data-toggle="modal" data-target="#modal_form_inline"><i class="icon-more2"></i></a>
			  <a href="updateSend.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
			  
			  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="<?php echo $row["id_Send"];?>"><i class="icon-person"></i></a>
			 
			 <a class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnSend" title="Send" id="<?php echo $row["id_Send"];?>"><div class="divLoading"><i class="icon-target2"></i></div></a>
			  <a href="ShowSendStats.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
			  <a class="btn border-grey-400 text-grey-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnCopy" title="Copy Send" data-toggle="modal" data-target="#modal_form_copy"><i class="icon-copy3"></i></a>
			  <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnStop" title="Stop Send" data-toggle="modal" data-target=""><i class="icon-pause"></i></a>
			 <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnDelete" title="Delete Send" id="<?php echo $row["id_Send"];?>" data-toggle="modal" data-target=""><i class="icon-trash"></i></a>
			</td>
		</tr>
		<script>
			function calculateFraction(vmta,count) {
				var myForm = document.forms['FractionForm' + count];
				var inputVal = myForm.elements['SelectedFraction' + count].value;
				var fraction = inputVal;
				var repeat = document.getElementById('SelectedLoop' + count).value;
				f = Math.round((fraction/vmta));
				if(vmta==0) f = 0,fraction=0;
				if(repeat==0) repeat = 1;
				if(!inputVal) inputVal = 0;
				document.getElementById('FractionDiv' + count).innerHTML = fraction*repeat + " Total<br>" + "(" + f + " X " + repeat + ") * " + vmta;;
			}
		</script>
		<script>
			function calculateLoop(count,vmta) {
				var myForm = document.forms['LoopForm' + count];
				var fraction = document.getElementById('SelectedFraction' + count).value
				var inputVal = myForm.elements['SelectedLoop' + count].value;
				var repeat = inputVal;
				if(!vmta) vmta = 1;
				if(!inputVal) inputVal = 1;
				document.getElementById('FractionDiv' + count).innerHTML = fraction*repeat + " Total<br>" + "(" + Math.round((fraction/vmta)) + " X " + repeat + ") * " + vmta;;
			}
		</script>
		<script>
			function calculateSeconds(count) {
				var myForm = document.forms['SecondsForm' + count];
				var inputVal = myForm.elements['SelectedSeconds' + count].value;
				if(!inputVal) inputVal = 0;
				document.getElementById('SecondsDiv' + count).innerHTML = parseInt(inputVal)/1000000 + "<br>Seconds";;
			}
		</script>
		<script>
			function shuffle(a) {
				var j, x, i;
				for (i = a.length; i; i--) {
					j = Math.floor(Math.random() * i);
					x = a[i - 1];
					a[i - 1] = a[j];
					a[j] = x;
				}
				return a.join("\n");
			}
			function random() {
				var ipsOriginal = document.getElementById('txtIPS').value;
				var ipsArray = ipsOriginal.split("\n");
				var ipsShuffle = shuffle(ipsArray);
				//var ipsRandom = ipsShuffle.replace(",","\n");
				document.getElementById('txtIPS').value = ipsShuffle;;
			}
		</script>
		<script>
			function pmta() {
				var win = window.open('PMTA.php', '_blank');
				if (win) {
					win.focus();
				} else {
					alert('Please allow popups for this website');
				}
			}
			function preparesend() {
				var win = window.open('prepareSend.php', '_blank');
				if (win) {
					win.focus();
				} else {
					alert('Please allow popups for this website');
				}
			}
		</script>
		<?php
		$count+=1;
			}
		?>
	</tbody>
</table>
<script>


var idS = '';

$('#loading').hide();

$('.btnSend').click(function(){

   var divLoading = $(this).children();
   
   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().prev().find('input').val();
   var Seed     = $(this).parent().prev().prev().prev().prev().find('input').val();
   var xDelay   = $(this).parent().prev().prev().prev().find('input').val();
   var repeat   = $(this).parent().prev().prev().find('input').val();
   if(xDelay=="")
	  xDelay = 0;
   if(repeat=="")
	  repeat = 1;
   
   var counterObject = $(this).parent().prev().children();
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend_repeat.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay,repeat:repeat},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');

	  
	  var countListExplode      = data.split('|');
	  var countListFinal        = parseInt(countListExplode[1]);
	  
	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
   });
   
   
})



$('.btnTestSend').click(function(){
   var idSend   = $(this).attr('id');
   
   $.post('SendTestOutSide.php',{id_Send:idSend},function(data){
      alert(data);
   });
   
});

$('.btnDelete').click(function(){
   var idSend   = $(this).attr('id');
   var r = confirm("Are you sure you want to delete send " + idSend + "?");
   if (r == true) {
		$.post('DeleteSend.php',{id_Send:idSend},function(data){
		alert(data);
		window.location.reload();
		});
   }

 });

$('.btnEditIPS').click(function(){
	  
	  
	 idS = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
	  
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
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
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
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

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
		  var page = "repeat";
		  
		  $.post('getSendsByInterval.php',{startDate : startDate , endDate : endDate, page : page},function(data){
			  
			  $('#loading').hide();
			  $('#sends').html(data);	

			  $('.datatable-basic').on( 'draw.dt', function () {





			  
$(".btnSend").unbind( "click" );
$(".btnTestSend").unbind( "click" );
$(".btnEditIPS").unbind( "click" );
$(".btnSend").unbind( "click" );
$(".btnStop").unbind( "click" );
$(".btnCopy").unbind( "click" );






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
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
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
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

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
<?php
		
}

/***** DIRECT **/
if ($page=="direct") 
{
		echo
	'
	<table class="table datatable-basic">
	<thead>
	<tr>
		<th>ID</th>
		<th>Offer</th>
		<th>ISP</th>
		<th>List</th>
		<th>Fraction</th>
		<th>Seed</th>
		<th>X-Delay</th>
		<th>Repeat</th>
		<th>Count</th>
		<th>Actions</th>
		
	</tr>
	</thead>

	<tbody style="background-color: #fbfbfb">
	';							


	$mailerLastName = $_SESSION['lastName_Employer']; 


	$requete = $bdd->prepare("select o.name_Offer, i.name_isp, s.* from send s , offer o , isp i where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp and s.id_Employer_Send=? and date(s.dateCreation) between ? and ? order by s.id_Send desc");
	$requete->execute(array($_SESSION['id_Employer'],$startDate,$endDate));
	$count=1;
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
	   
	   $countList      = 0;
	   
	   if($requeteCount)
	   {
		   $rowCount	   = $requeteCount->fetch();
		   
		   if(($rowCount[0] - $row['startFrom_Send']) > 0)
			  $countList = $rowCount[0] - $row['startFrom_Send'];
	   }
	   $sendInfo=$bdd->query('Select IPS_Send from send where id_send="'.$row["id_Send"].'"');
	   $c=0;
	   while ($r=$sendInfo->fetch()){
		   if ($r[0]!=NULL){
			   $vmtas=explode(PHP_EOL,$r[0]);
			   $c=count($vmtas);
		   }
	   }?>
	   <tr>
										<td style="border-left: thin solid #719ba7;border-top: thin solid #719ba7;border-bottom: thin solid #719ba7"><?php echo $row["id_Send"];?></td>
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7;"><?php echo $row["name_Offer"];?></td>
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7"><?php echo $row["name_isp"];?></td>
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7"><?php echo $listName;?></td>
										
										<!-- FRACTION -->
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
											<br><br>
											<form id="FractionForm<?php echo $count?>">
												<input id="SelectedFraction<?php echo $count?>" onkeyup="calculateFraction(<?php echo $c?>,<?php echo $count ?>)" type="text" class="form-control" style="width:70px;"/>
											</form>
											<br>
											<div>
												<center>
												<span  id="FractionDiv<?php echo $count?>" align="bottom" class="label bg-danger-400">0 TOTAL<br>(0 x 1)  * <?php echo $c?>
												</span>
												</center>
											</div>
										</td>
										<!-- --->
										
										
										<!-- SEED -->
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
											<br><br>
											<input type="text" class="form-control" style="width:70px;"/>
											<br><center><span align="bottom" class="label bg-default-400">&#8291;<br>&#8291;</span></center>
										</td>
										
										<!-- X-DELAY -->
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
											<br><br>
											<form id="SecondsForm<?php echo $count?>">
												<input id="SelectedSeconds<?php echo $count?>" onkeyup="calculateSeconds(<?php echo $count?>)" type="text" class="form-control" style="width:70px;"/>
											</form>
											<br>
											
											<center><span id="SecondsDiv<?php echo $count?>" align="bottom" class="label bg-danger-400">0<br>Seconds</span></center>
											
										</td>
										<!-- -->
										
										<!-- REPEAT -->
										<td style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
											
											<br><br>
											<form id="LoopForm<?php echo $count?>">
												<input  id="SelectedLoop<?php echo $count?>" onkeyup="calculateLoop(<?php echo $count?>,<?php echo $c ?>)" value="1" type="text" class="form-control" style="width:70px;"/>
											</form>
											<br><center><span align="bottom"  class="label bg-danger-400"><?php echo $c."<br> VMTA"?></span></center>
											
										</td>
										<!--  -->
										
										<td align="center" style="border-top: thin solid #719ba7;border-bottom: thin solid #719ba7">
											<span class="label bg-success-400"><?php echo $countList;?></span></td>
										
										<td style="border-top: thin solid #719ba7;border-right: thin solid #719ba7;border-bottom: thin solid #719ba7">				  
										  <a class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnEditIPS" title="Edit IPS" data-toggle="modal" data-target="#modal_form_inline"><i class="icon-more2"></i></a>
										  <a href="updateSend.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
										  
										  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="<?php echo $row["id_Send"];?>"><i class="icon-person"></i></a>
										 
										 <a class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnSend" title="Send" id="<?php echo $row["id_Send"];?>"><div class="divLoading"><i class="icon-target2"></i></div></a>
										  <a href="ShowSendStats.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
										  <a class="btn border-grey-400 text-grey-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnCopy" title="Copy Send" data-toggle="modal" data-target="#modal_form_copy"><i class="icon-copy3"></i></a>
										  <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnStop" title="Stop Send" data-toggle="modal" data-target=""><i class="icon-pause"></i></a>
										 <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnDelete" title="Delete Send" id="<?php echo $row["id_Send"];?>" data-toggle="modal" data-target=""><i class="icon-trash"></i></a>
										</td>
									
										<?php /*<td style="border-bottom: thin solid #719ba7;border-left: thin solid #719ba7;border-right: thin solid #719ba7"  align="right">				  
										  <a class="btn border-indigo-400 text-indigo-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnEditIPS" title="Edit IPS" data-toggle="modal" data-target="#modal_form_inline"><i class="icon-more2"></i></a>
										  <a href="updateSend.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
										  <!-- SENDDDD -->
										  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="<?php echo $row["id_Send"];?>"><i class="icon-person"></i></a>
										 
										 <a class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnSend" title="Send" id="<?php echo $row["id_Send"];?>"><div class="divLoading"><i class="icon-target2"></i></div></a>
										  <a href="ShowSendStats.php?id_Send=<?php echo $row["id_Send"];?>" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
										  <a class="btn border-grey-400 text-grey-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnCopy" title="Copy Send" data-toggle="modal" data-target="#modal_form_copy"><i class="icon-copy3"></i></a>
										  <a class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnStop" title="Stop Send" data-toggle="modal" data-target=""><i class="icon-pause"></i></a>
										</td>*/?>
									</tr>
									<script>
										function calculateFraction(vmta,count) {
											var myForm = document.forms['FractionForm' + count];
											var inputVal = myForm.elements['SelectedFraction' + count].value;
											var fraction = inputVal;
											var repeat = document.getElementById('SelectedLoop' + count).value;
											f = Math.round((fraction/vmta));
											if(vmta==0) f = 0,fraction=0;
											if(repeat==0) repeat = 1;
											if(!inputVal) inputVal = 0;
											document.getElementById('FractionDiv' + count).innerHTML = fraction*repeat + " Total<br>" + "(" + f + " X " + repeat + ") * " + vmta;;
										}
									</script>
									<script>
										function calculateLoop(count,vmta) {
											var myForm = document.forms['LoopForm' + count];
											var fraction = document.getElementById('SelectedFraction' + count).value
											var inputVal = myForm.elements['SelectedLoop' + count].value;
											var repeat = inputVal;
											if(!vmta) vmta = 1;
											if(!inputVal) inputVal = 1;
											document.getElementById('FractionDiv' + count).innerHTML = fraction*repeat + " Total<br>" + "(" + Math.round((fraction/vmta)) + " X " + repeat + ") * " + vmta;;
										}
									</script>
									<script>
										function calculateSeconds(count) {
											var myForm = document.forms['SecondsForm' + count];
											var inputVal = myForm.elements['SelectedSeconds' + count].value;
											if(!inputVal) inputVal = 0;
											document.getElementById('SecondsDiv' + count).innerHTML = parseInt(inputVal)/1000000 + "<br>Seconds";;
										}
									</script>
									<script>
										function shuffle(a) {
											var j, x, i;
											for (i = a.length; i; i--) {
												j = Math.floor(Math.random() * i);
												x = a[i - 1];
												a[i - 1] = a[j];
												a[j] = x;
											}
											return a.join("\n");
										}
										function random() {
											var ipsOriginal = document.getElementById('txtIPS').value;
											var ipsArray = ipsOriginal.split("\n");
											var ipsShuffle = shuffle(ipsArray);
											//var ipsRandom = ipsShuffle.replace(",","\n");
											document.getElementById('txtIPS').value = ipsShuffle;;
										}
									</script>
									<script>
										function pmta() {
											var win = window.open('PMTA.php', '_blank');
											if (win) {
												win.focus();
											} else {
												alert('Please allow popups for this website');
											}
										}
										function preparesend() {
											var win = window.open('prepareSend.php', '_blank');
											if (win) {
												win.focus();
											} else {
												alert('Please allow popups for this website');
											}
										}
									</script>
									
									

							<?php
								$count+=1;
								}
							?>
								
								
							</tbody>
						</table>
						<script>


var idS = '';

$('#loading').hide();

$('.btnSend').click(function(){

   var divLoading = $(this).children();
   
   divLoading.html('').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
   var idSend   = $(this).attr('id');
   var Fraction = $(this).parent().prev().prev().prev().prev().prev().find('input').val();
   var Seed     = $(this).parent().prev().prev().prev().prev().find('input').val();
   var xDelay   = $(this).parent().prev().prev().prev().find('input').val();
   var repeat   = $(this).parent().prev().prev().find('input').val();
   if(xDelay=="")
	  xDelay = 0;
   if(repeat=="")
	  repeat = 1;
   
   var counterObject = $(this).parent().prev().children();
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend_direct.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay,repeat:repeat},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');

	  
	  var countListExplode      = data.split('|');
	  var countListFinal        = parseInt(countListExplode[1]);
	  
	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
   });
   
   
});



$('.btnTestSend').click(function(){
   var idSend   = $(this).attr('id');
   
   $.post('SendTestOutSide2.php',{id_Send:idSend},function(data){
      alert(data);
   });
   
});

$('.btnDelete').click(function(){
   var idSend   = $(this).attr('id');
   var r = confirm("Are you sure you want to delete send " + idSend + "?");
   if (r == true) {
		$.post('DeleteSend.php',{id_Send:idSend},function(data){
		alert(data);
		window.location.reload();
		});
   }

 });
$('.btnEditIPS').click(function(){
	  
	  
	 idS = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
	  
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
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
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
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

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
		  var page = "direct";
		  
		  $.post('getSendsByInterval.php',{startDate : startDate , endDate : endDate, page : page},function(data){
			  
			  $('#loading').hide();
			  $('#sends').html(data);	

			  $('.datatable-basic').on( 'draw.dt', function () {





			  
$(".btnSend").unbind( "click" );
$(".btnTestSend").unbind( "click" );
$(".btnEditIPS").unbind( "click" );
$(".btnSend").unbind( "click" );
$(".btnStop").unbind( "click" );
$(".btnCopy").unbind( "click" );
$(".btnDelete").unbind( "click" );


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
   //var currentCount = parseInt(counterObject.html());
   var newCount     = 0;
   
   $.post('RealSend.php',{id_Send:idSend,fraction:Fraction,seed:Seed,xDelay:xDelay},function(data){
      alert(data);
	  divLoading.html('').html('<i class="icon-target2"></i>');
	  
	  var countListExplode      = data.split('/');
	  var countListFinal        = parseInt(countListExplode[1]);
	  
	  if(countListFinal < 0)
		  countListFinal = 0;
	  counterObject.html(countListFinal);
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
		   
				$('#tableData').hide();
				$('#cmbCountry').val("-1");
				
});

$('#cmbCountry').change(function(){
		   $('#tableData').html('');
		   var nameISP = $('#cmbIsps option:selected').text();
		   if(nameISP!='warm up')
		   {
			   var idIsp = $('#cmbIsps').val();
			   var idCountry = $(this).val();
			   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:idCountry},function(data){
				  $('#tableData').html(data);
				  $('#tableData').show();
			   });
		   }
});


$('.btnCopy').click(function(){
	
	$('#cmbIsps').val('-1');
	$('#cmbCountry').val('-1');
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
			   var lien = 'http://'+host+'/exactarget/Send/kill.php';
			   
			   $.post('killTempo.php',{lien:lien,pid:pid},function(data){

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
<?php
}
						
?>	

<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="datatables_basic.js"></script>		
		