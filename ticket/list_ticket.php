<?php
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
		
		include_once('../Includes/sessionVerificationMailer.php'); 
		$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		verify($monUrl);
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - My Tickets</title>
	
	<style>

	</style>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="datatables_basic.js"></script>

	
	
	
	
	
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tickets</span> - My Tickets</h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Ticket</li>
							<li class="active">My Tickets</li>
					    </ul>

			
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>Status</th>
									<th>Subject</th>
									<th>Department</th>
									<th>Actions</th>
								</tr>
							</thead>
							
							<tbody>
							
							<?php
								include('../Includes/bdd.php');
								if($_SESSION['type_Employer'] == "IT")
								{
								   $cmd = $bdd->query
								   ("
									SELECT 			
										T.id_ticket,
										T.subject_ticket,
										S.name_ticket_status
									FROM 		
										ticket T,ticket_status S 
									WHERE 		
										T.id_ticket_status	=	S.id_ticket_status
									ORDER 
										BY	T.id_ticket DESC
									");
									$cmd->execute(array());
								}
								else
								{
								   $cmd = $bdd->prepare
								   ("
									SELECT 			
										T.id_ticket,
										T.subject_ticket,
										S.name_ticket_status
									FROM 		
										ticket T,ticket_status S 
									WHERE 		
										T.id_user_ticket	=	?
									AND
										T.id_ticket_status	=	S.id_ticket_status
									ORDER 
										BY	T.id_ticket DESC
									");
									$cmd->execute(array($_SESSION['id_Employer']));
								}
								while($row = $cmd->fetch())
								{
							?>
									<tr>
										<td><?php echo get_status_style($row["name_ticket_status"]);?></td>
										<td><?php echo $row["subject_ticket"];?></td>
										<td>Technical</td>
										<td>
										<a href="ui_ticket.php?id_ticket=<?php echo $row["id_ticket"];?>" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update"><i class="icon-eye"></i></a>
										</td>
									</tr>
							<?php
								}
							?>
								
								
							</tbody>
						</table>
					
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

<?php 

function get_status_style($p_status)
{
	$result	=	null;
	switch($p_status)
	{
		case 'Opened':
			$result	=	'<span class="label label-success">Opened</span>';
			break;
		case 'Closed':
			$result	=	'<span class="label label-danger">Closed</span>';
			break;	
		case 'Answered':
			$result	=	'<span class="label label-info">Answered</span>';
			break;
		case 'Mailer Reply':
			$result	=	'<span class="label label-primary">Mailer Reply</span>';
			break;			
	}
	return $result;
}
?>
