
	function cb(start, end) 
	{
			$('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
	}
	
	cb(moment().subtract(30, 'days'), moment());

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
		  var page = "original";
		  
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

		
		//Body Preview :
		$('#txtBody').keyup(function()
		{
		   $('#bodyPreview').empty();
		   var codeHTML =	$(this).val();
		   var ip 		= 	$('#cmbIPs option:selected').first().text().split(' | ')[1];
		   codeHTML 	= 	codeHTML.replaceAll('[domain]',ip);
		   $('#bodyPreview').html(codeHTML);
		});
		
		
		//Send Test Mail :
		$('#btnTestMail').click(function(){
		
        var idSend = <?php echo $id_Send;?>;
		var idFrom = $('#cmbFroms').val();
		var idSubject = $('#cmbSubjects').val();
		var emailTest = $('#txtEmailTest').val();
		var returnPath = $('#txtReturnPath').val();
		var header = $('#txtHeader').val();
		var body = $('#txtBody').val();
		var ips = $('#cmbIPs').val();
		var chkAR = $('#chkAR').val();
		var txtARList = $('#txtARList').val();
		var idnegative = $('#cmbNegative').val();
		
			$.post('SendTestInside.php',{id_Send:idSend,cmbFroms:idFrom,cmbSubjects:idSubject,txtEmailTest:emailTest,txtReturnPath:returnPath,txtHeader:header,txtBody:body,'cmbIPs':ips,chkAR:chkAR,txtARList:txtARList,idnegative:idnegative},function(data)
			{
			    alert(data);
			});
		});
		
		
		var isAR = <?php echo $isAR;?>;
		if(isAR == 0)
		//$('#txtARList').hide();
		
		$('#chkAR').click(function(){
		   if($(this).is(":checked"))
		   {
		     //$('#txtARList').show();
			 $('#chkAR').val(1);
		   }
		   else
		   {
		     //$('#txtARList').hide();
			 $('#chkAR').val(0);
		   }
		});
		
		
		$('#reloadNegatives').click(function(){
			
			$.post('reloadNegative.php',{},function(data){
				
				$('#cmbNegative').html('').html(data);
				
			});
		});
	
	
	//Servers :
	$('#cmbServers').change(function()
	{
		$('#cmbIPs').empty();
		var server = $('#cmbServers').val();
		$.post
		(
			'getIPS2.php',
			{cmbServers : server},
			function(data)
			{
				$('#cmbIPs').html(data);
			}
		);
		selctedServersCount();
		selctedVMTAsCount();
	});
	
	
	//VMTAs :
	$('#cmbIPs').change(function()
	{
		selctedVMTAsCount();
		$('#txtBody').keyup();
	});
	
	
	//Filter Server :
	$('#txtServerFilter').keyup(function()
	{
		var	target_server	=	$('#txtServerFilter').val();
		var cptFoundedServers		=	0;
		var cptNotFoundedServers	=	0;
		$("#cmbServers option").each(function()
		{
			if($(this).text().toLowerCase().indexOf(target_server.toLowerCase()) >= 0)
			{
				$(this).show();
				cptFoundedServers++;
			}
			else
			{
				$(this).hide();
				cptNotFoundedServers++;
			}
		});
		
		if(cptFoundedServers==0)
			$("#cmbServers").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbServers option[value='-1']").remove();
	
		
	});
	
	
	
	//Filter VMTA :
	$('#txtVmtaFilter').keyup(function()
	{
		var	target_vmta	=	$('#txtVmtaFilter').val();
		var cptFoundedVMTAs		=	0;
		var cptNotFoundedVMTAs	=	0;
		$("#cmbIPs option").each(function()
		{
			if($(this).text().toLowerCase().indexOf(target_vmta.toLowerCase()) >= 0)
			{
				$(this).show();
				cptFoundedVMTAs++;
			}
			else
			{
				$(this).hide();
				cptNotFoundedVMTAs++;
			}
		});
		
		if(cptFoundedVMTAs==0)
			$("#cmbIPs").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbIPs option[value='-1']").remove();
		
		
	});
	
	//Server Count :
	function selctedServersCount()
	{
		var nbr_servers	=	$('#cmbServers option:selected').length;
		var str			=	'';
		if(nbr_servers==0)
		{
			str			=	'No Server selected';
		}
		else
		{
			var	plurielS	=	( (nbr_servers==1)?"":"s");
			str				=	nbr_servers+' selected Server'+plurielS;
		}
		$("#txtServerCount").text(str);
	}
	
	
	//VMTA Count :
	function selctedVMTAsCount()
	{
		var nbr_VMTAs	=	$('#cmbIPs option:selected').length;
		var str			=	'';
		if(nbr_VMTAs==0)
		{
			str			=	'No VMTA selected';
		}
		else
		{
			var	plurielS	=	( (nbr_VMTAs==1)?"":"s");
			str				=	nbr_VMTAs+' selected VMTA'+plurielS;
		}
		$("#txtVMTACount").text(str);
	}

	<script>
	function btnUpdateSend(){
		$("#updateSend").submit().$("form");
		$('#modal_UpdateSend').trigger('click');
	}
</script>
<!-- PREPARE SEND SCRIPTS -->
<script>

$('.btnPrepareSend').click(function(){
		//var divLoading = $(this).children();
		//divLoading.html('.btnPrepareSend').html('<img src="loading.gif" style="width:15px;height:15px;"/>');
		$("#SendForm").submit();
		
		 
	});
/*$('.btnRefresh').click(function() {
		var divLoading = $(this).children();
		divLoading.html('.btnRefresh').html('<img src="loading.gif" style="width:15px;height:15px;"/>'); 
		$("#sends").load(location.href+" #sends",function(){
			$('#loading').show();
			$.getScript("../assets/js/plugins/tables/datatables/datatables.min.js");
			$.getScript("datatables_basic.js"); 
			$.getScript("script.js");
			$('#loading').hide();
			divLoading.html('').html('<i class="icon-loop4"></i>');
		});
	});*/
var cpt   = 0;

$('#divTotalCount').hide();



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
	
	
	$('#cmbCountry').change(function(){
		
	   $('#spanTotalMixed').html('0');
	   $('#divTotalCount').show();
	   $('#lineNegative').hide();
	   
	   $('#tableData').html('');
	   var nameISP = $('#cmbIsps option:selected').text();
	   var country = $(this).val();
	   
	   if(nameISP!='warm up')
	   {
		   var idIsp = $("#cmbIsps").val();
		   $.post('ajax.php',{type:'isp',id_Isp:idIsp,country:country},function(data){
			  $('#tableData').html(data);
			  $('#tableData').show();
			  
			  
			  $('.chkListSelect').click(function(){
				  
				  var count = parseInt($(this).prev().html());
				  
				  if($(this).prop('checked')==true)
					  cpt+=count;
				  else
					  cpt-=count;
				  $('#spanTotalMixed').html(cpt); 
				});


		   });
	   }
	   
	   if(nameISP == 'hotmail')
		   $('#lineNegative').show();
	});
	
	
	$('#cmbIsps').change(function(){
	   
			$('#tableData').hide();
			$('#cmbCountry').val("-1");
			
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
	/*function showHTML () {

		$('#bodyPreview').html($('#txtBody').val());
	}*/
	
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


<script>
	// Refresh Offer tab
	$('.btnRefresh').click(function() {
		$("#ProcessList").dataTable().fnDestroy();
		var divLoading = $(this).children();
		divLoading.html('.btnRefresh').html('<img src="loading.gif" style="width:15px;height:15px;"/>'); 
		$("#sends").load(location.href+" #sends",function(){
			$('#loading').show();
			$.getScript("../assets/js/plugins/tables/datatables/datatables.min.js");
			$.getScript("../assets/js/core/libraries/jquery.min.js");
			$.getScript("datatables_basic.js"); 
			$.getScript("script.js");
			$('#loading').hide();
			divLoading.html('').html('<i class="icon-loop4"></i>');
		});
	
	});
	$('.btnRefreshProcess').click(function() {
		$("#SendList").dataTable().fnDestroy();
		var divLoading = $(this).children();
		divLoading.html('.btnRefreshProcess').html('<img src="loading.gif" style="width:15px;height:15px;"/>'); 
		$("#processes").load(location.href+" #processes",function(){
			$.getScript("../assets/js/plugins/tables/datatables/datatables.min.js");
			$.getScript("datatables_basic.js"); 
			$.getScript("script.js");
			divLoading.html('.btnRefreshProcess').html('<i class="icon-loop4"></i>');
		});
	});
	
	//Tabs
	$(function(){
		$("#Offers").show();
		$("#WarmUP").hide();
		$("#Test").hide();
		$("#PMTA1").hide();
		$("#PMTA2").hide();
		$("#Prepare").hide();
		$("#Process").hide();
		$("#Linode").hide();
		
	});
	function tab(evt, type) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(type).style.display = "block";
		evt.currentTarget.className += " active";
	}
	// Offers
	
	$('#tableData').hide();
	$('#divResult').hide();
	
	// Email Test
	$('#btnSelectTest').click(function(){
		var selectedIPS=$('#select').val();
		$('#cmbIPsTest').empty();
		var server = $('#cmbServerTest').val();
		$.post
		(
			'selectIPS.php',
			{
				selected : selectedIPS , 
				cmbServers : server
			},
			function(data)
			{
				$('#cmbIPsTest').html(data);
				$('#cmbIPsTest').focus();
				selctedVMTAsCount();
			}
		);
		
		
        
    });
	
   
	$('#btnTestTest').click(function(){
		
	   $('#divResultTest').hide();
	   
	   var emailTest = $('#txtEmailTest').val();
	   var returnPath = $('#txtReturnPath').val();
	   var header = $('#txtHeader').val();
	   var body = $('#txtBody').val();
	   //var ips = $('#txtIPS').val();
	   var ips = $('#cmbIPs').val();
	   var file = $('#txtFILE').val();
	   var fraction = $('#fraction').val();
		var delay = $('#delay').val();
		if ($('#rotation').is(':checked')) {
			var rotation = 1;
		}else{
			var rotation = 0;
		}
	   
	   $.post
	   (
			'emailTestGlobal_POST.php',
			{
				txtFILE : file,
				txtEmailTest:emailTest,
				txtReturnPath:returnPath,
				txtHeader:header,
				txtBody:body,
				txtIPS:ips,
				delay:delay,
				fraction,fraction,
				rotation:rotation
			},
			function(data)
			{
				var output = data.split('/');
				alert(output);
				if(output[0].length!=0)
				{
					$('#divResult').html(output[0]).show();
				}
					
			});
	   
	});
	
	
	//Servers :
	$('#cmbServersTest').change(function()
	{
		$('#cmbIPsTest').empty();
		var server = $('#cmbServers').val();
		$.post
		(
			'getIPS2.php',
			{cmbServers : server},
			function(data)
			{
				$('#cmbIPsTest').html(data);
			}
		);
		selctedServersCount();
		selctedVMTAsCount();
	});
	
	
	//VMTAs :
	$('#cmbIPsTest').change(function()
	{
		selctedVMTAsCount();
	});
	
	
	//Filter Server :
	$('#txtServerFilterTest').keyup(function()
	{
		var	target_server	=	$('#txtServerFilterTest').val();
		var cptFoundedServers		=	0;
		var cptNotFoundedServers	=	0;
		$("#cmbServersTest option").each(function()
		{
			if($(this).text().toLowerCase().indexOf(target_server.toLowerCase()) >= 0)
			{
				$(this).show();
				cptFoundedServers++;
			}
			else
			{
				$(this).hide();
				cptNotFoundedServers++;
			}
		});
		
		if(cptFoundedServers==0)
			$("#cmbServersTest").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbServersTest option[value='-1']").remove();
	
		
	});
	
	
	
	//Filter VMTA :
	$('#txtVmtaFilterTest').keyup(function()
	{
		var	target_vmta	=	$('#txtVmtaFilterTest').val();
		var cptFoundedVMTAs		=	0;
		var cptNotFoundedVMTAs	=	0;
		$("#cmbIPsTest option").each(function()
		{
			if($(this).text().toLowerCase().indexOf(target_vmta.toLowerCase()) >= 0)
			{
				$(this).show();
				cptFoundedVMTAs++;
			}
			else
			{
				$(this).hide();
				cptNotFoundedVMTAs++;
			}
		});
		
		if(cptFoundedVMTAs==0)
			$("#cmbIPsTest").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbIPsTest option[value='-1']").remove();
		
		
	});
	
	//Server Count :
	function selctedServersCount()
	{
		var nbr_servers	=	$('#cmbServersTest option:selected').length;
		var str			=	'';
		if(nbr_servers==0)
		{
			str			=	'No Server selected';
		}
		else
		{
			var	plurielS	=	( (nbr_servers==1)?"":"s");
			str				=	nbr_servers+' selected Server'+plurielS;
		}
		$("#txtServerCountTest").text(str);
	}
	
	
	//VMTA Count :
	function selctedVMTAsCount()
	{
		var nbr_VMTAs	=	$('#cmbIPsTest option:selected').length;
		var str			=	'';
		if(nbr_VMTAs==0)
		{
			str			=	'No VMTA selected';
		}
		else
		{
			var	plurielS	=	( (nbr_VMTAs==1)?"":"s");
			str				=	nbr_VMTAs+' selected VMTA'+plurielS;
		}
		$("#txtVMTACountTest").text(str);
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


<script>


var idS = '';

$('#loading').hide();
$('#loading_stats').hide();
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
	  
	 idS = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().attr("id");
	  
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
$('.btnStats').click(function(){
	  
	var idSend   = $(this).attr('id');
	$("#StatsResults").empty();
	
	$("#StatsResults").load("GetSendStats.php?id_Send=" + idSend);
	
});

$('.btnUpdate').click(function(){
	var idSend   = $(this).attr('id');
	$("#UpdateResults").empty();
	$('#UpdateResults').load("updateSend.php?id_Send=" + idSend)
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
	var idSendStop = $(this).attr('id');
	alert(idSendStop);
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
$('.btnStopProcess').click(function(){
	var SendStop = $(this).attr('id').split('-');
	var pid=SendStop[0];
	var host=SendStop[1];
	var id=SendStop[2];
	var user=SendStop[3];
	$.post('http://'+host+'/exactarget/Send/kill.php',{id:id,pid:pid,host:host,user:user},function(data){
		alert(data);
	});
});	



	function cb(start, end) 
	{
			$('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
	}
	
	cb(moment().subtract(30, 'days'), moment());

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
		  var page = "original";
		  
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
</script>
<script>
//PMTA1
		$('#cmbServers').change(function(){
		   
		   var ip = $(this).val();
		   $('#framePMTA').attr('src','http://'+ip+':2304');

		});
		
		$('#btnDelete').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'delete';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		
		$('#btnPause').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'pause';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnResume').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'resume';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnReset').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'reset';
		   
		   commands(ip,command,'');
		});
		
		
		$('#btnSchedule').click(function(){
		
		   var ip       = $('#cmbServers').val();
		   var command = 'schedule';
		   var queue    = $('#txtQueueName').val();
		   
		   commands(ip,command,queue);
		});
		
		
		function commands(ip,command,queue)
		{
		
		  var link = 'http://'+ip+'/exactarget/Send/commandsPMTA.php';
		  
		  $.post('formCommands.php',{link:link,command:command,queue:queue},function(data){
		    //alert(data);
		  });
		  
		}
		

		
		
</script>
<script>
	//PMTA2
		$('#cmbServers2').change(function(){
		   
		   var ip = $(this).val();
		   $('#framePMTA2').attr('src','http://'+ip+':2304');

		});
		
		$('#btnDelete2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'delete';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		
		$('#btnPause2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'pause';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnResume2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'resume';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		$('#btnReset2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'reset2';
		   
		   commands(ip,command,'');
		});
		
		
		$('#btnSchedule2').click(function(){
		
		   var ip       = $('#cmbServers2').val();
		   var command = 'schedule2';
		   var queue    = $('#txtQueueName2').val();
		   
		   commands(ip,command,queue);
		});
		
		
		function commands(ip,command,queue)
		{
		
		  var link = 'http://'+ip+'/exactarget/Send/commandsPMTA.php';
		  
		  $.post('formCommands.php',{link:link,command:command,queue:queue},function(data){
		    //alert(data);
		  });
		  
		}
		

		
		
</script>
<script>
function delete_selected(){
	$("form#linodes").append('<input type="hidden" name="delete_selected" value="" /> ');
	$('form#linodes').submit();
}
function config_selected(){
	$("form#linodes").append('<input type="hidden" name="config_selected" value="" /> ');
	//$('form#linodes').submit();
	/*$.post('linode_POST.php', $('#linodes').serialize(), function (data, textStatus) {
         $('#install').append(data);
    });*/
	$("#install").empty();
	$("#install").append('<center>Installing: Please wait ....</b>');
	$.ajax({
			url: "linode_POST.php",
			type: "POST",
			data: $('#linodes').serialize(),
			xhrFields: {
				onprogress: function(e) {
					console.log(e.target.responseText)
					$("#install").html(e.target.responseText)
					if (e.lengthComputable) {
						console.log(e.loaded / e.total * 100 + '%');
					}
				}
			},
			success: function(text) {
					console.log(text)
					$("#install").html(text+"Done!")
			}
		});
   return false;
}
</script>
