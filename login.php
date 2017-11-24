<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Aurora - Login</title>
	<?php include_once("_includes/libraries.php");?>
</head>
<body> <?php
if (isset($_POST["username"])){
	session_start();
	include_once("_includes/bdd.php");
	$login = $_POST['user'];
	$password = $_POST['password'];
	$query = $bdd->prepare('select user_id,user_name,user_type,user_isp_privileges from users where user_name = ? and user_password = ?');
	$query->execute(array($login,$password));
	$row = $query->fetch();
	if($row>0) {
		$_SESSION['user_id'] = $row['user_id'];
		$_SESSION['user_name'] = $row['user_name'];
		$_SESSION['user_type'] 	= $row['user_type'];
		$_SESSION['user_isp_privileges'] = $row['user_isp_privileges'];
		$query = $bdd->prepare('select user_type_name from user_type where user_type_id = ?');
		$query->execute(array($row['user_type']));
		$row = $query->fetch();
		$_SESSION['user_type'] = $row['user_type_name'];
		header('location:./');
	} else{
		echo "User doesn't exist";
		//header('location:index.php');
	}
}else{ ?>
	<!-- Page container -->
	<div class="page-container login-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Simple login form -->
					<form action="" method="POST">
						<div class="panel panel-body login-form">
							<center><h4>MreMailer</h4></center><hr>
							<div class="text-center">
								<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
								<h5 class="content-group">Login to your account <small class="display-block"></small></h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="text" class="form-control" placeholder="Username" name="txtUsername">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control" placeholder="Password" name="txtPassword">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Log in <i class="icon-circle-right2 position-right"></i></button>
							</div>
						</div>
					</form>
					<!-- /simple login form -->


					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2018. <a href="#">MreMailer</a>
					</div>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
<?php
}
?>
</body>
</html>
