<?php
     ini_set ('magic_quotes_gpc', 0);
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
	  
	  
	  
	function	fill_combo_ips_send($p_id_send)
	{
		include('../Includes/bdd.php');
		$result	=	null;
		$ips 	= 	get_ips_send_by_id_send($p_id_send);
		if(!is_null($ips))
		{
			foreach($ips as $addresse_ip)
			{
				$requete = $bdd->prepare(
				'
					SELECT 	S.alias_Server,I.id_IP,I.IP_IP,D.name_Domain
					FROM 	server S,ip I,domain D
					WHERE 	S.id_Server		=		I.id_Server_IP
					AND		I.id_Domain_IP	=		D.id_Domain
					AND		I.IP_IP			=		?
					LIMIT	0,1
				');
				$requete->execute(array(trim($addresse_ip)));
				$row = $requete->fetch();
				
				$result.='<option selected value='.$row['id_IP'].'>'.$row['alias_Server'].' | '.$row['IP_IP'].' | '.$row['name_Domain'].'</option>';
			}
		}

		if(!is_null($result))
			echo $result;
	}
	
	function	get_ips_send_by_id_send($p_id_send)
	{
		include('../Includes/bdd.php');
		$result		=	null;
		$requete 	= 	$bdd->prepare(
		'
			SELECT 	S.IPS_Send
			FROM 	send S
			WHERE 	S.id_Send		=		?
		');
		$requete->execute(array($p_id_send));
		$row = $requete->fetch();
		if($row)
		{
			$strIPs	=	trim($row['IPS_Send']);
			if(!empty($strIPs))
				$result	=	explode(PHP_EOL,$strIPs);
			else
				$result	=	null;
		}
		
		return $result;
	}  
	
	function	is_selected_server($p_id_send,$p_id_server)
	{
		include('../Includes/bdd.php');
		$result		=	'';
		
		if(  ($p_id_send>0) and ($p_id_server>0)  )
		{
			$tab_ips_send	=	get_ips_send_by_id_send($p_id_send);
			$clause_IN		=	implode("','", $tab_ips_send);
			$clause_IN 		= 	rtrim($clause_IN, ",");
			$clause_IN='\''.$clause_IN.'\'';
			//echo $clause_IN;
			$requete 		= 	$bdd->prepare
			("
				SELECT 	count(*) as founded
				FROM 	ip I
				WHERE 	I.id_Server_IP	=	?
				AND		I.IP_IP in ($clause_IN)
			");
			$requete->execute(array($p_id_server));
			$row	=	$requete->fetch();
			if($row)
			{
				$is_founded	=	$row['founded'];
				if($is_founded>0)
					$result	=	'selected';
				else
					$result	=	'';
			}
			else
			{
				$result	=	'';
			}
		}
		
		echo $result;
	}
    include('../Includes/css.php');
//include('../Includes/js.php');?>
	<script type="text/javascript" src="../assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="../assets/js/core/app.js"></script>

<div class="page-header">
	<div class="page-header-content">
		<div class="heading-elements">
			<span class="heading-text text-bold">
				<span class="badge bg-primary">
					<?php echo $id_Send;?>
				</span>  
				
				<span class="label bg-danger">
					<?php echo $name_Offer;?>
				</span>
			</span>
			
			
			
		</div>
	</div>
</div>
<form class="form-horizontal" accept-charset="utf-8" id="updateSend" method="POST" action="updateSend_POST.php">
<input type="hidden" id="id_Send" name="id_Send" value="<?php echo $id_Send;?>"/>
<div class="row">
	<div class="col-lg-12">
		<div class="col-lg-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h4 class="panel-title" align="left">Offer Details</h4>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a data-action="collapse"></a></li>
						</ul>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label">From</label>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-md-12">
											<select name="cmbFroms" id="cmbFroms" class="form-control" data-placeholder="Select From">
											 <?php
												$requete = $bdd->prepare('select * from froms where id_Offer_From = ?');
												$requete->execute(array($id_Offer_Send));
												while($row = $requete->fetch())
												{
												   ?> <option value="<?php echo $row['id_From'];?>" <?php echo ($row['id_From'] == $id_From_Send) ? 'selected' : '';?>><?php echo $row['text_From'];?></option><?php
												} 
											 ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label">Subject</label>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-md-12">
											<select name="cmbSubjects" id="cmbSubjects" class="form-control" data-placeholder="Select Subject">
											 <?php
												$requete = $bdd->prepare('select * from subjects where id_Offer_Subject = ?');
												$requete->execute(array($id_Offer_Send));
												while($row = $requete->fetch())
												{
												   ?> <option value="<?php echo $row['id_Subject'];?>" <?php echo ($row['id_Subject'] == $id_Subject_Send) ? 'selected' : '';?>><?php echo $row['text_Subject'];?></option><?php
												} 
											 ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label">Return Path</label>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-md-12">
											<input type="text text-bold" class="form-control" name="txtReturnPath" id="txtReturnPath" value="<?php echo $returnPath_Send;?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label">Negative</label>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-md-12">
											<div class="input-group">
												<select name="cmbNegative" id="cmbNegative" class="form-control" data-placeholder="Select Negative">
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
												<div class="input-group-btn">
													
													<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action 
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li><a id="reloadNegatives"><i class=" icon-reload-alt"></i>Refresh</a></li>
														<li><a href="../Negative/uploadNegative.php" target="_blank"><i class="icon-add"></i>Upload Negative</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label">Start From</label>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-md-12">
											<input type="number" class="form-control" name="txtStartFrom" id="txtStartFrom" value="<?php echo $startFrom_Send;?>"  />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label">Email(s) Test</label>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-md-12">
											<textarea placeholder="one email test per line" class="form-control" name="txtEmailTest" id="txtEmailTest" rows="2" style="resize: none;"><?php echo $emailTest_Send;?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h4 class="panel-title" align="left">Servers</h4>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a data-action="collapse"></a></li>
						</ul>
					</div>
				</div>
				<div class="panel-body">
					<?php
						$id_Isp = $_SESSION['id_Isp_Employer'];
						$requete = $bdd->query('select id_Server,alias_Server from server where isActive_Server = 1');
					?>	
					<div class="row">	
						<div class="col-lg-4">
							<div class="form-group">
								<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags">
									<span class="label bg-primary-400">Server</span>
								</label>
								<input type="text" id="txtServerFilterUpdate" name="txtServerFilter"  class="form-control" placeholder="Filter server">
							</div>
						</div>
						<div class="col-lg-8">
							<div class="form-group">
								<label style="cursor:pointer;" data-toggle="modal" data-target="#modal_tags">
									<span class="label bg-primary-400">VMTA</span>
								</label>
								<input type="text" id="txtVmtaFilterUpdate" name="txtVmtaFilter"  class="form-control" placeholder="Filter vmta">
							</div>
						</div>
					</div>	
					<div class="row">		
						<div class="col-lg-4">
							<div class="form-group">
								<select id="cmbServersUpdate" name="cmbServers[]" multiple="multiple" class="form-control" size="15">
								  <?php
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
												
												?>
												<option <?php is_selected_server($id_Send,$row['id_Server']); ?> value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>
												<?php
											}
										}
										else
										{
											
											$requeteMailer = $bdd->prepare('select id_Server from servermailer where id_Server = ? and id_Mailer = ? and is_Autorised = 1');
											$requeteMailer->execute(array($row['id_Server'],$_SESSION['id_Employer']));
											$SubrowMailer = $requeteMailer->fetch();
											if($SubrowMailer)
											{
												
												?>
												<option <?php is_selected_server($id_Send,$row['id_Server']); ?> value="<?php echo $row['id_Server']?>"><?php echo $row['alias_Server'];?></option>
												<?php
											}
											
										}
									  
									}
								  ?>
								</select>
							</div>
						</div>
						<div class="col-lg-8">
							<div class="form-group">
								<select id="cmbIPsUpdate" name="cmbIPs[]" multiple="multiple" class="form-control"  size="15">
									<?php
										fill_combo_ips_send($id_Send);
									?>
								</select>
							</div>
						</div>
						
					</div>	
					<div class="row">
						<div class="col-lg-4">
							<div class="form-group">
								<span class="label label-block label-primary text-left" id="txtServerCountUpdate">No Server selected</span>
							</div>
						</div>

						<div class="col-lg-8">
							<div class="form-group">
								<span class="label label-block label-primary text-left" id="txtVMTACountUpdate" >No VMTA Selected</span>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="col-lg-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h4 class="panel-title" align="left">Header</h4>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a data-action="collapse"></a></li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-md-12">
										<textarea class="form-control" name="txtHeader" id="txtHeader" style="resize:none;" rows="10"><?php echo $header_Send;?></textarea>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-lg-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h4 class="panel-title" align="left">Body</h4>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a data-action="collapse"></a></li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-md-12">
										<textarea class="form-control" name="txtBody" id="txtBody" style="resize:none;" rows="10"><?php echo $body_Send;?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

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
	$('#cmbServersUpdate').change(function()
	{
		$('#cmbIPsUpdate').empty();
		var server = $('#cmbServersUpdate').val();
		$.post
		(
			'getIPS2.php',
			{cmbServers : server},
			function(data)
			{
				$('#cmbIPsUpdate').html(data);
			}
		);
		selctedServersCount();
		selctedVMTAsCount();
	});
	
	
	//VMTAs :
	$('#cmbIPsUpdate').change(function()
	{
		selctedVMTAsCount();
		$('#txtBody').keyup();
	});
	
	
	//Filter Server :
	$('#txtServerFilterUpdate').keyup(function()
	{
		var	target_server	=	$('#txtServerFilterUpdate').val();
		var cptFoundedServers		=	0;
		var cptNotFoundedServers	=	0;
		$("#cmbServersUpdate option").each(function()
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
			$("#cmbServersUpdate").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbServersUpdate option[value='-1']").remove();
	
		
	});
	
	
	
	//Filter VMTA :
	$('#txtVmtaFilterUpdate').keyup(function()
	{
		var	target_vmta	=	$('#txtVmtaFilterUpdate').val();
		var cptFoundedVMTAs		=	0;
		var cptNotFoundedVMTAs	=	0;
		$("#cmbIPsUpdate option").each(function()
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
			$("#cmbIPsUpdate").append('<option value=-1 disabled>No results</option>');
		else 
			$("#cmbIPsUpdate option[value='-1']").remove();
		
		
	});
	
	//Server Count :
	function selctedServersCount()
	{
		var nbr_servers	=	$('#cmbServersUpdate option:selected').length;
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
		$("#txtServerCountUpdate").text(str);
	}
	
	
	//VMTA Count :
	function selctedVMTAsCount()
	{
		var nbr_VMTAs	=	$('#cmbIPsUpdate option:selected').length;
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
		$("#txtVMTACountUpdate").text(str);
	}

	

	</script>

<?php 
	
	

?>
