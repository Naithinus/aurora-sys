<?php
date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - Show Servers</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>

	
	
	
	
	
</head>

<body>
    


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Servers</span> - Install</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Install</a></li>
							<li>Servers</li>
							<li class="active">Show</li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
						<?php
						if(isset($_POST["servers"])){
							$servers=$_POST["servers"];
							$emailtest=$_POST["emailtest"];
							$servers=explode(PHP_EOL,$servers);
							$date = date(DATE_RFC2822);
							$header = "";
							$body="This is an email test from [ip]";
							echo "Testing servers";
							for($i=0;$i<5;$i++){
								echo ".";
								flush();
								ob_flush();
								usleep(500000);
							}
							echo "<br>";
							foreach($servers as $ip){
								$headersmtp=$header;
								@$fp = fsockopen($ip,25,$errno, $errstr, 5);
								if (!$fp)
								{
									echo "<span style=color:red Failed to connect to $ip</span><br>";
									continue;   
								}
								flush();
								ob_flush();
								$headersmtp.="X-virtual-MTA: mta-$ip\r\nx-job:test\n\n$body\n.\n";
								$headersmtp = preg_replace('#\[ip\]#',$ip,$headersmtp);
								$headersmtp = htmlspecialchars($headersmtp);
								echo $headersmtp;
								fputs($fp, "telnet $ip\r\n");
								fputs($fp, "EHLO $ip\r\n");
								fputs($fp, "MAIL FROM:test@carlasmir.com\r\n");
								fputs($fp, "RCPT TO:$emailtest\r\n");
								fputs($fp, "DATA\r\n");
								fputs($fp, $headersmtp);
								$smtpOutput=fgets($fp);
								$g=substr($smtpOutput, 0, 3);
								if (!(($g == "220") || ($g == "250") || ($g == "354")|| ($g == "500"))) 
								{
									echo "<span style=color:orange>telnet to $ip failed | error: $g</span><br>";
									return false; 
								}else{
									echo "<span style=color:green>Email test sent from $ip</span><br>";
								}
								
							}
						}else
						{?>
								<form method="post" action="TestServer.php"  autocomplete="off">
								<div class="row">
									<div class="col col-lg-5">
										Email<br>
										<input type="text" class="form-control" placeholder="Email test" name="emailtest"><br>
									</div>
								</div>
								<div class="row">
									<div class="col col-lg-10">
										Server IPs<br>
										<textarea name="servers" style="width:100%;resize:none" rows="10"  placeholder="One IP per line .."></textarea>
									</div>
								</div>
								<div class="row">
									<div align="right">
										<input type="submit" class="btn btn-success" value="Send test">
									</div>
								</div>
								</form><?php
						}?>
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
