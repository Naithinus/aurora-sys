<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	include('../Includes/bdd.php');
	
	$mailerLastName = $_SESSION['lastName_Employer'];
	$nameList = $mailerLastName.'WU';
	$requete = $bdd->prepare
	('
		SELECT	E.email_Email,W.password_email 
		FROM 	email E,email_list_warmup W 
		WHERE 	E.id_List_Email = (select id_List from list where name_List = ? )
		AND		E.id_Email		=	W.id_email
	');
	$requete->execute(array($nameList));
	
	$mails = '';
	
	while($row = $requete->fetch())
	{
	    $mails.=$row['email_Email'].'/'.$row['password_email'].PHP_EOL;
	}
	$mails = trim($mails);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Update List WARM UP</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	

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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">List Warm UP</span> - Update</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>List Warm UP</li>
							<li class="active">UPDATE</li>
						</ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
					
					<form class="form-horizontal" method="POST" action="IU_WarmUP_POST.php">
					
					    <fieldset class="content-group">
							<legend class="text-bold">Update List Warm UP</legend>

							<div class="form-group">
								<label class="control-label col-lg-2">Name / XID</label>
								<div class="col-lg-10">
									<textarea class="form-control" name="txtListWarmUP" id="txtListWarmUP" cols="10" rows="10"><?php echo $mails;?></textarea>
								</div>
							</div>
						</fieldset>
								
						<div class="text-right">
							<button type="submit" class="btn btn-primary">Update<i class="icon-arrow-right14 position-right"></i></button>
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

</body>
</html>
