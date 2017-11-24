<?php
		
	 
	//include_once('http://79.143.189.72/exactarget/Includes/sessionVerificationMailer.php'); 
	//$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	//verify($monUrl);

?>

	<div class="sidebar sidebar-main">
				<div class="sidebar-content">

					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Main -->
								
								<?php 
								
								
								/***************************************  DOMAINS MANAGER  ************************************************/
								
								if($_SESSION['type_Employer']=="Domains Manager")
								{
								?>
									
									<li>
									    <a href="#"><i class="icon-search4"></i><span>Domain</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Domain/IU_Domain.php">
													Add Domain
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Domain/ShowDomains.php">
													Show Domains
												</a>
											</li>
										</ul>
									</li>
								
									<li>
										<a href="#"><i class="icon-search4"></i> <span>Servers-IPS-Domains</span>  </a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/getServerDomains.php">
													Filter
												</a>
											</li>
										</ul>
									</li>
								
								
								<?php
								}
								
								
								/***************************************  OFFER MANAGER  ************************************************/
								
								
								if($_SESSION['type_Employer']=="Offer Manager")
								{
								?>
									
									<li>
										<a href="#"><i class="icon-collaboration"></i> <span>Sponsor</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Sponsor/IU_Sponsor.php">
													Add Sponsor
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Sponsor/ShowSponsors.php">
													Show Sponsors
												</a>
											</li>
										</ul>
									</li>
									
									
									<li>
										<a href="#"><i class="icon-newspaper"></i> <span>Offer</span></a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Offer/IU_Offer.php">Add Offer</a></li>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Offer/ShowOffers.php">Show Offers</a></li>
										</ul>
									</li>
									
									
									<li>
										<a href="#"><i class="icon-camera"></i> <span>Image</span> </a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Offer/upload_image.php">
													Upload Image
												</a>
											</li>
										</ul>
									</li>
								
								
								<?php
								}
								
								/***************************************  MAILER  ************************************************/
								
								elseif($_SESSION['type_Employer']=="Mailer")
								{
								?>
								
									<li>
										<a href="#"><button style="all:inherit" class="tablinks" onclick="tab(event, 'PMTA1')"><i class="icon-mail5"></i></button></a>
									</li>
									<li>
										<a href="#"><i class="icon-server"></i> <span>PMTA</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/PMTA.php">
													Show PMTAS
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/PMTA/editConfig.php">
													Edit Config  
												</a>
											</li>
										</ul>
									</li>
								
									<li>
										<a href="#"><i class="icon-collaboration"></i> <span>Sponsor</span></a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Sponsor/ShowSponsors.php">Show Sponsors</a></li>
										</ul>
									</li>
									<li>
										<a href="#"><i class="icon-shrink7"></i> <span>Negative </span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Negative/uploadNegative.php">
													Add Negative
												</a>
											</li>
											
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Negative/ShowNegatives.php">
													Show Negatives
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/imap.php">
													Build Negative
												</a>
											</li>
											
									
										</ul>
									</li>
									<!--<li>
										<a href="#"><i class="icon-warning"></i> <span>Logs</span></a>
										<ul>
											<li>
												<a href="http://<?php //echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/ShowLogs.php">
													Show Logs
												</a>
											</li>
										</ul>
									</li>-->
								
								
									<!--<li>
										<a href="#"><i class="icon-camera"></i> <span>Image</span> </a>
										<ul>
											<li>
												<a href="http://<?php // echo $_SERVER['HTTP_HOST'];?>/exactarget/Offer/upload_image.php">
													Upload Image
												</a>
											</li>
										</ul>
									</li>-->
								
								
									<li>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/getServerDomains.php">
											<i class="icon-search4"></i><span>Servers</span>
										</a>
									</li>
								
								
									<li>

												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/List/IU_WarmUP.php">
													<i class="icon-add-to-list"></i> <span>Warm UP List</span></a>
									</li>
								
									
									
									<li>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/imap.php"><i class="icon-mailbox"></i> <span>IMAP</span></a>
									</li>
								
								
								
									
								
								<li>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/StatsOffer.php"><i class="icon-stats-growth"></i> <span>Stats Offer</span></a>
										
									</li>
								
									<li>
										<a href="#"><i class="icon-drawer-out"></i> <span>Tools</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/reporting.php">
													Reporting tool
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/spfChecker.php">
													SPF CHECKER
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/mremailer/changelog.php"><i class="glyphicon-info-sign"></i> <span>Changelog</span></a>
										
									</li>
									
							
								
								
								
									

							
							
									<!--<li>
										<a href="#"><i class="icon-ticket"></i> <span>Tickets</span></a>
										<ul>
											<li>
												<a href="http://<?php //echo $_SERVER['HTTP_HOST'];?>/exactarget/ticket/list_ticket.php">
													My Tickets
												</a>
											</li>
											
											<li>
												<a href="http://<?php //echo $_SERVER['HTTP_HOST'];?>/exactarget/ticket/ui_ticket.php">
													Open new ticket
												</a>
											</li>
										</ul>
									</li>-->
									
									
								<?php
								}
								
								
								/***************************************  ADMIN  ************************************************/
								
								elseif($_SESSION['type_Employer']=="ADMIN")
								{
								?>
									
									<li>
										<a href="#"><i class="icon-ticket"></i> <span>Tickets</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/ticket/list_ticket.php">
													My Tickets
												</a>
											</li>
											
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/ticket/ui_ticket.php">
													Open new ticket
												</a>
											</li>
										</ul>
									</li>
									
									
									
									<li>
										<a href="#"><i class="icon-bubble-notification"></i> <span>Notifications</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Notification/IU_Notification.php">
													Add Notification
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Notification/ShowNotifications.php">
													Show Notifications
												</a>
											</li>
										</ul>
									</li>
									
									<li>
										<a href="#"><i class="icon-wrench"></i> <span>Tools</span></a>
										<ul>
										   <li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/tools/uploader.php">
													FTP Tool
												</a>
											</li>
										</ul>
									</li>
									
									
									<li>
										<a href="#"><i class="icon-person"></i> <span>Employers</span></a>
										<ul>
											<li>
											   <a href="#">Employers</a>
												<ul>
													<li>
														<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Employer/IU_Employer.php">
															Add Employer
														</a>
													</li>
													<li>
														<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Employer/ShowEmployers.php">Show Employers
														</a>
													</li>
												</ul>
											</li>
											<li>
												<a href="#"><span>Types Employers</a>
												<ul>
													<li>
														<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Type_Employer/IU_Type_Employer.php">
															Add Type
														</a>
													</li>
													<li>
														<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Type_Employer/ShowTypes.php">
															Show Types
														</a>
													</li>
												</ul>
								            </li>
										</ul>
									</li>
								
									<li>
										<a href="#"><i class="icon-collaboration"></i> <span>Sponsor</span></a>
										<ul>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Sponsor/IU_Sponsor.php">
													Add Sponsor
												</a>
											</li>
											<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Sponsor/ShowSponsors.php">
													Show Sponsors
												</a>
											</li>
										</ul>
									</li>
								
								
									<li>
										<a href="#"><i class="icon-mention"></i> <span>ISPS</span></a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/ISP/IU_ISP.php">Add ISP</a></li>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/ISP/ShowISPS.php">Show ISPS</a></li>
										</ul>
									</li>
								
									<li>
										<a href="#"><i class="icon-IE"></i> <span>Domain</span></a>
										<ul>
										
										<li>
											<a href="#"><span>Domain Provider</span></a>
											<ul>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/DomainProvider/IU_DomainProvider.php">Add Domain Provider</a></li>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/DomainProvider/ShowDomainProviders.php">Show Domain Provider</a></li>
											</ul>
										</li>
									
										  <li>
											 <a href="#"><span>Domain</span></a>
											 <ul>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Domain/IU_Domain.php">Add Domain</a></li>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Domain/ShowDomains.php">Show Domains</a></li>
											 </ul>
											 
										  </li>
										</ul>
									</li>
								
								
									<li>
										<a href="#"><i class="icon-server"></i> <span>Server</span></a>
										<ul>
										
											<li>
												<a href="#"><span>Server Provider</span></a>
												<ul>
													<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/ServerProvider/IU_ServerProvider.php">Add Server Provider</a></li>
													<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/ServerProvider/ShowServerProviders.php">Show Server Provider</a></li>
												</ul>
											</li>
										   
										   
										   <li>
												<a href="#"><span>OS</span></a>
												<ul>
													<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/OS/IU_OS.php">Add OS</a></li>
													<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/OS/ShowOS.php">Show OS</a></li>
												</ul>
										   </li>
									
											<li>
											  <a href="#"><span>Server</a>
											  <ul>
											   <li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Server/IU_Server.php">Add Server</a></li>
											   <li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Server/ShowServers.php">Show Servers</a></li>
											  </ul>
											</li>
											
										</ul>
									</li>
									
									<li>
										<a href="#"><i class="icon-flag3"></i> <span>Country</span></a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Country/IU_Country.php">Add Country</a></li>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Country/ShowCountrys.php">Show Countrys</a></li>
										</ul>
									</li>
								
								
								
									<li>
										<a href="#"><i class="icon-move-vertical"></i> <span>Vertical</span></a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Vertical/IU_Vertical.php">Add Vertical</a></li>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Vertical/ShowVerticals.php">Show Verticals</a></li>
										</ul>
									</li>
								
								
									<li>
										<a href="#"><i class="icon-newspaper"></i> <span>Offer</span></a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Offer/IU_Offer.php">Add Offer</a></li>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Offer/ShowOffers.php">Show Offers</a></li>
										</ul>
									</li>
								
								
									<li>
										<a href="#"><i class="icon-list-numbered"></i> <span>List</span></a>
										<ul>
										
										<li>
												<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/List/wrapper.php"><i class="icon-split"></i> <span>Wrapper</span></a>
												
										</li>
										
										
										<li>
											<a href="#"><span>Type List</span></a>
											<ul>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/TypeList/IU_TypeList.php">Add Type</a></li>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/TypeList/ShowTypeLists.php">Show Types</a></li>
											</ul>
										</li>
									
											<li>
											  <a href="#"><span>List</span></a>
											  <ul>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/List/IU_List.php">Add List</a></li>
												<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/List/ShowLists.php">Show Lists</a></li>
											  </ul>
											</li>
											
										</ul>
									</li>
								
									<li>
										<a href="#"><i class="icon-newspaper"></i> <span>Delete Emails</span>  </a>
										<ul>
											<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/exactarget/Send/DeleteHardBounce.php">Delete Emails</a></li>
										</ul>
									</li>
								
								
								<?php	
								}
								?>
							
							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
