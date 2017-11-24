<?php
	include_once('../Includes/sessionVerificationMailer.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	
	include('../Includes/bdd.php');
	  
	$id_ticket      	= 	-1;
    $subject_ticket		= 	'';
	$message_ticket		=	'';
	$date_ticket		=	'';	
	$id_user_ticket		= 	'';
	$id_status_ticket	=	'';
	$buttonText         = 	'Open New Support Ticket';
	  
	if(  (isset($_GET['id_ticket']))    and     (is_numeric($_GET['id_ticket']))    and    ($_GET['id_ticket']>0)    )
	{
	    $id_ticket 			= 	$_GET['id_ticket'];
		$id_connected_user	=	$_SESSION['id_Employer'];
	}
	  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - My Tickets</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_checkboxes_radios.js"></script>
	
	
	
	<!-- Theme JS files -->
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/fullcalendar/fullcalendar.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/visualization/echarts/echarts.js"></script>
	<script type="text/javascript" src="../assets/js/pages/timelines.js"></script>
	<!-- /theme JS files -->


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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tickets</span> - <?php echo $buttonText;?></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li>Tickets</li>
							<li class="active">Submit Ticket</li>
					    </ul>

			
					</div>
				</div>
				<!-- /page header -->


		<!-- Content area -->
		<div class="content">
			<!-- Form horizontal -->
			<div class="panel panel-flat">
				<div class="panel-body">
					<form class="form-horizontal" method="POST" action="ui_ticket_post.php">
						<fieldset class="content-group">
							<?php 
							if($id_ticket == -1)
							{
							?>
								<legend class="text-bold"><?php echo $buttonText;?></legend>
								<div class="form-group">
									<label class="control-label col-lg-2">Subject</label>
									<div class="col-lg-10">
										<input type="text" name="subject_ticket" id="subject_ticket" class="form-control" value="<?php echo $subject_ticket;?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-lg-2">Message</label>
									<div class="col-lg-10">
										<textarea type="text" name="message_ticket_details" id="subject_ticket" class="form-control" rows="20"><?php echo $message_ticket_details;?></textarea>
									</div>
								</div>
								
								<div class="text-right">
									<button type="submit" class="btn btn-primary">Submit<i class="icon-arrow-right14 position-right"></i></button>
								</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<!-- /content area -->
							<?php
							}
							else
							{
								
								$requete 	= $bdd->prepare
								('
									SELECT 		T.id_ticket,T.subject_ticket,S.name_ticket_status,D.date_ticket_details,T.id_user_ticket,D.message_ticket_details,D.id_ticket_details,D.is_support_answer 
									FROM 		ticket T,ticket_details D,ticket_status S 
									WHERE 		T.id_ticket			= 	?
									AND			T.id_ticket			=	D.id_ticket
									AND			T.id_ticket_status	=	S.id_ticket_status
									ORDER BY	D.id_ticket_details DESC			
								');
								$requete->execute(array($id_ticket));	
							?>
								
							
			<!-- Main content -->
			<div class="content-wrapper">
				<div class="content">
					<div class="timeline timeline-left content-group">
						<div class="timeline-container">
							
							<!-- Date stamp -->
							<div class="timeline-date text-muted">
								<i class="icon-history position-left"></i> <span class="text-semibold"><?php echo date('l \t\h\e jS');?></span>
							</div>
							<!-- /date stamp -->

							<!-- Messages -->
							<div class="timeline-row">
								<div class="timeline-icon">
									<div class="bg-info-400">
										<i class="icon-comment-discussion"></i>
									</div>
								</div>

								<div class="panel panel-flat timeline-content">
									<div class="panel-heading">
										<h6 class="panel-title"></h6>
										<div class="heading-elements">
											<ul class="icons-list">
												<li class="dropdown">
													<a href="#" class="dropdown-toggle" data-toggle="dropdown">
														<i class="icon-circle-down2"></i>
													</a>

													<ul class="dropdown-menu dropdown-menu-right">
														<li><a href="close_ticket.php?id_ticket=<?php echo $id_ticket; ?>"><i class="icon-user-lock"></i> Close ticket</a></li>
													</ul>
												</li>
						                	</ul>
					                	</div>
									</div>

									<div class="panel-body">
										<ul class="media-list chat-list content-group">
											<li class="media date-step">
												<h1>
													<span>
													<?php 
														$subject	=	get_ticket_subject_by_id($id_ticket);
														echo 'TICKET ['.$id_ticket.'] : <b>'.$subject.'</b>'; 
													?>
													</span>
												</h1>
											</li>

										<?php
											while($ticket =	$requete->fetch())
											{
												$is_support_answer	=	$ticket['is_support_answer'];
												$date_ticket		=	date_create($ticket['date_ticket_details']);
												if($is_support_answer)
												{
										?>			
													<li class="media reversed">
														<div class="media-body">
															<div class="media-content"><?php echo  $ticket['message_ticket_details'];?></div>
															<span class="media-annotation display-block mt-10"><?php echo date_format($date_ticket,"l j F Y, H:i");?><i class="icon-watch2 position-right text-muted"></i></span>
														</div>
														
														<div class="media-right">
															<img src="../assets/images/placeholder.jpg" class="img-circle" alt="">
														</div>
													</li>
											<?php	
												}
												else
												{
											?>
													<li class="media">
														<div class="media-left">
															<img src="../assets/images/placeholder.jpg" class="img-circle" alt="">
															
														</div>

														<div class="media-body">
															<div class="media-content"><?php echo  $ticket['message_ticket_details'];?></div>
															<span class="media-annotation display-block mt-10"><?php echo date_format($date_ticket,"l j F Y, H:i");?><i class="icon-watch2 position-right text-muted"></i></span>
														</div>
													</li>
											<?php		
												}
											}
											?>		
										</ul>
										<form method="POST" action="ui_ticket_post.php">
											<textarea name="message_ticket_details" id="message_ticket_details" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message...">
											</textarea>
											<input type="hidden" name="id_ticket" value="<?php echo $id_ticket;?>"/>
										</form>
										
										<div class="row">
				                    		<div class="col-xs-6">
					                        	
				                    		</div>

				                    		<div class="col-xs-6 text-right">
					                            <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-circle-right2"></i></b> Submit</button>
				                    		</div>
				                    	</div>
									</div>
								</div>
							</div>
							<!-- /messages -->
						</div>
					</div>
				</div>		
			</div>				
							<?php
							}
							?>	
						

					
					<!-- Footer -->
					<?php include'../Includes/footer.php'; ?>
					<!-- /footer -->

				

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
<?php
	function	get_ticket_subject_by_id($p_id_ticket)
	{
		include('../Includes/bdd.php');
		$cmdGetTicketSubject = $bdd->prepare('select subject_ticket from ticket where id_ticket = ?');
		$cmdGetTicketSubject->execute(array($p_id_ticket));
		$row	=	$cmdGetTicketSubject->fetch();
		$cmdGetTicketSubject->closeCursor();
		
		return $row['subject_ticket'];
	}
?>