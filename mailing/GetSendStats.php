<?php
	 include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 $idSend = $_POST['id_Send'];
	   $idSend = $_GET['id_Send'];
	   include('../Includes/bdd.php');
	   $requete = $bdd->prepare('select cptReceived , cptDelivered,cptHardBounce,cptSoftBounce from send where id_Send = ?');
	   $requete->execute(array($idSend));
	   $row = $requete->fetch();
		
	   $cptReceived   = $row['cptReceived'];
	   $cptDelivered  = $row['cptDelivered'];
	   $cptHardBounce = $row['cptHardBounce'];
	   $cptSoftBounce = $row['cptSoftBounce'];
	   
	   $requete = $bdd->prepare('select count(*) from openers where id_Send_Open = ?');
	   $requete->execute(array($idSend));
	   $row     = $requete->fetch();
	   $cptOpen = $row[0];
	   
	   
	   $requete = $bdd->prepare('select count(*) from clickers where id_Send_Click = ?');
	   $requete->execute(array($idSend));
	   $row     = $requete->fetch();
	   $cptClick = $row[0];
	   
	   
	   $requete = $bdd->prepare('select count(*) from unsubscribers where id_Send_Unsub = ?');
	   $requete->execute(array($idSend));
	   $row     = $requete->fetch();
	   $cptUnsub = $row[0];
	   
	?>
				<center>
				<div style="">
				<div class="row" style="width:100%">
								<div class="col-lg-3">

									<!-- Members online -->
									<div class="panel bg-blue-400">
										<div class="panel-body">
											

											<h3 class="no-margin"><?php echo $cptReceived;?></h3>
											Received
											<br/><i class="icon-arrow-up16"></i>
										
									      </div>
									<!-- /members online -->

								    </div>
								</div>
								
								
								<div class="col-lg-3">

									<!-- Members online -->
									<div class="panel bg-success">
										<div class="panel-body">
											

											<h3 class="no-margin"><?php echo $cptDelivered;?></h3>
											Delivered
											<br/><i class="icon-split"></i>
										
									      </div>
									<!-- /members online -->

								    </div>
								</div>
								
								
								<div class="col-lg-3">

									<!-- Members online -->
									<div class="panel bg-warning-400">
										<div class="panel-body">
											

											<h3 class="no-margin"><?php echo $cptHardBounce;?></h3>
											Hard Bounce
											<br/><i class="icon-person"></i>
										
									      </div>
									<!-- /members online -->

								    </div>
								</div>
								
								
								<div class="col-lg-3">

									<!-- Members online -->
									<div class="panel bg-danger">
										<div class="panel-body">
											

											<h3 class="no-margin"><?php echo $cptSoftBounce;?></h3>
											Soft Bounce
											<br/><i class="icon-alert"></i>
										
									      </div>
									<!-- /members online -->

								    </div>
								</div>
								
								
							</div>
							</center>
							
						<center>
							<div class="panel panel-flat" style="width:100%;">
								<div class="panel-heading">
									<h6 class="panel-title"></h6>
									<div class="heading-elements">
										<button type="button" class="btn btn-link daterange-ranges heading-btn text-semibold">
											
										</button>
				                	</div>
								<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

								<div class="table-responsive">
									<table class="table table-xlg text-nowrap">
										<tbody>
											<tr>
												

												<td class="col-md-1">
													<div class="media-left media-middle">
														<a href="#" class="btn border-grey-400 text-grey-400 btn-flat btn-rounded btn-xs btn-icon"><i class="icon-eye"></i></a>
													</div>

													<div class="media-left">
														<h5 class="text-semibold no-margin">
															<?php echo $cptOpen;?> <small class="display-block no-margin">Total Opens</small>
														</h5>
													</div>
												</td>

												
												<td class="col-md-1">
													<div class="media-left media-middle">
														<a href="#" class="btn border-success text-success btn-flat btn-rounded btn-xs btn-icon"><i class="icon-touch"></i></a>
													</div>

													<div class="media-left">
														<h5 class="text-semibold no-margin">
															<?php echo $cptClick;?> <small class="display-block no-margin">Total Click</small>
														</h5>
													</div>
												</td>
												
												
												<td class="col-md-1">
													<div class="media-left media-middle">
														<a href="#" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-xs btn-icon"><i class="icon-exit"></i></a>
													</div>

													<div class="media-left">
														<h5 class="text-semibold no-margin">
															<?php echo $cptUnsub;?> <small class="display-block no-margin">Total Unsub</small>
														</h5>
													</div>
												</td>
												
												
												
												
											</tr>
										</tbody>
									</table>	
								</div>

								
							</div>	
							
							
							
							<div class="panel panel-flat" style="width:100%;">
							  <legend class="text-bold">Mixed Lists</legend>
							  
							  <table>
							        <?php
							        $subRequete = $bdd->prepare('select l.name_List,tl.abr_TypeList from list l , sendlist sl , typelist tl where l.id_List = sl.id_List and sl.id_Send = ? and l.id_Type_List = tl.id_TypeList');
									$subRequete->execute(array($idSend));
									$concat='';
									while($subRow = $subRequete->fetch())
									{
										?>
										<tr>
										  <td><h3><i class="icon-checkmark3 text-success"></i><?php echo $subRow['name_List'].'-'.$subRow['abr_TypeList'];?></h3></td>
										</tr>
										<?php
									}
									?>
							  </table>

								
							</div>	
							
				
				
			
		
				<!-- /content area -->

			</div>
			
			
			
			</div>
			
			
	
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	