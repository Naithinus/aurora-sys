<?php
     include_once('./sessionVerificationMailer.php'); 	 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
?>

<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="" style="margin-left:15px" href="../"><img height="50px;width:50px"src="http://<?php echo $_SERVER['HTTP_HOST'];?>/aurora/Includes/logo.png" alt=""></a>

			<ul class="nav navbar-nav visible-xs-block" >
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>
		
		<div class="navbar-collapse collapse" id="navbar-mobile">
		<?php
		if($_SESSION['type_Employer']!="Mailer"){?>
			<ul class="nav navbar-nav">
					<li>
						<a class="sidebar-control sidebar-main-toggle hidden-xs">
							<i class="icon-paragraph-justify3"></i>
						</a>
					</li>

					
				</ul><?php
		}?>
			

			<ul class="nav navbar-nav navbar-right">
			
				<li class="dropdown" style="position:relative;top:12px;margin-left:30px;">
					<span class="label bg-blue"><?php echo $aliasServer;?></span>					
				</li>
				<li class="dropdown" style="position:relative;top:12px;margin-left:30px;">
					<span class="label bg-info-400">v2.36 Beta</span>				
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
						<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/aurora/logout.php"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	
	
	<script>
	 
	</script>
