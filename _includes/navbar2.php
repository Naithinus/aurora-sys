<?php
    include_once('http://79.143.189.72/exactarget/Includes/sessionVerificationMailer.php'); 
	 include('bdd.php');
include('../Includes/css.php');
	 $domain = $_SERVER['HTTP_HOST'];
	 
	  if(preg_match("/[0-9]+.[0-9]+.[0-9]+.[0-9]+/", $domain)==1)
	  {
	     $requete = $bdd->prepare('select s.alias_Server from server s , ip i where i.IP_IP = ? and i.id_Server_IP = s.id_Server');
		 $requete->execute(array($domain));
		 $row = $requete->fetch();
		 $aliasServer = $row['alias_Server'];
	  }
	 
	 else
	 {
		 $requete = $bdd->prepare('select s.alias_Server from server s , ip i , domain d where i.id_Domain_IP = d.id_Domain and d.name_Domain = ? and i.id_Server_IP = s.id_Server');
		 $requete->execute(array($domain));
		 $row = $requete->fetch();
		 $aliasServer = $row['alias_Server'];
	 }
?>

<div class="navbar navbar-inverse">
		<div class="navbar-header" style="margin:0;padding:0">
			<a class="" style="" href="index.html"><img height="50px;width:50px;"src="http://<?php echo $_SERVER['HTTP_HOST'];?>/aurora/Includes/logo.png" alt=""></a>

			<ul class="nav navbar-nav pull-right visible-xs-block" style="margin:0;padding:0">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li>
					<a class="sidebar-control sidebar-main-toggle hidden-xs">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

				
			</ul>

			<ul class="nav navbar-nav navbar-right">
			
				<li class="dropdown" style="position:relative;top:12px;margin-left:30px;">
					<span class="label bg-blue"><?php echo $aliasServer;?></span>					
				</li>
				
				<li class="dropdown" id="divNotifications">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="icon-bubble-notification"></i>
						<span class="visible-xs-inline-block position-right">Notifications</span>
						<?php
						  $requete = $bdd->prepare('select count(*) from notification_mailer where is_Readed = 0 and id_mailer = ?');
						  $requete->execute(array($_SESSION['id_Employer']));
						  $row = $requete->fetch();
						  if($row[0]>0)
						  {
							  ?><span class="badge bg-warning-400" id="cptNotifications"><?php echo $row[0];?></span><?php
						  }
						  else
						  {
							   ?><span class="badge bg-warning-400" id="cptNotifications"></span><?php
						  }
						  ?>
						
					</a>
					
					<div class="dropdown-menu dropdown-content width-350">
						<div class="dropdown-content-heading">
							Notifications
							
						</div>

						<ul class="media-list dropdown-content-body" id="ulNotifications">
						
						  <?php
						  
						  $requete = $bdd->prepare('select n.* from notification n , notification_mailer nm where n.id_Notification = nm.id_notification and nm.is_Readed = 0 and nm.id_mailer = ?');
						  $requete->execute(array($_SESSION['id_Employer']));
						  
						  while($row = $requete->fetch())
						  {
							    ?>
								
								<li class="media">
									<div class="media-left">
										<img src="../assets/images/placeholder.jpg" class="img-circle img-sm" alt="">
									</div>

									<div class="media-body">
										<a href="#" class="media-heading">
											<span class="text-semibold">Admin</span>
											<span class="media-annotation pull-right"></span>
										</a>

										<span class="text-muted"><?php echo $row['text_Notification'];?></span>
									</div>
								</li>
								
								<?php
						}
							?>
							
							
						</ul>

						<div class="dropdown-content-footer">
							
						</div>
					</div>
				</li>
				
				
				<li class="dropdown" style="position:relative;top:12px;margin-left:30px;">
					<span class="label bg-success-400"><?php echo $_SESSION['type_Employer'];?></span>				
				</li>
				

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/assets/images/placeholder.jpg" alt="">
						<span><?php echo $_SESSION['lastName_Employer'].' - ['.$_SESSION['id_Employer'].']';?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/logout.php"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	
	
	<script>
	
	
	if($('#cptNotifications').html().length==0)
		$('#cptNotifications').hide();
	
	var id_Mailer = <?php echo $_SESSION['id_Employer'];?>;

	
         function getNewNotifications()
		 {
				 $.post('http://79.143.189.72/exactarget/Includes/getNewNotifications.php',{idMailer : id_Mailer},function(data){
					
					if(data.length>1)
					{
						var split = data.split('*');
						
						$('#cptNotifications').html(split[1]).show();
						$('#ulNotifications').html(split[0]);
					}
						
					
					
					
				 });	 
		  }
		 
	  setInterval(function(){
			getNewNotifications();
		}, 60000);
	 	 
	 $('#cptNotifications').click(function(){
		 
		
		
		$(this).hide();

		
		 $.post('http://79.143.189.72/exactarget/Includes/markReadNotifications.php',{idMailer : id_Mailer},function(){
		    
		 });
		
	 });
	 
	 
	  $('#divNotifications').click(function(){
		 
	
		$('#cptNotifications').hide();
		
		$.post('http://79.143.189.72/exactarget/Includes/markReadNotifications.php',{idMailer : id_Mailer},function(){
		    
		 });
		
	 });
	 
	 
	</script>
