<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if(isset($_SESSION['login']) && isset($_SESSION['pass'])){
$log=$_SESSION['login'];
session_write_close();
date_default_timezone_set('America/New_York');
$today=date('Y-m-d');
$yesterday = date('Y-m-d', time()-86400);




							#{Parameteres}
							$mysql_login="root";
							$mysql_passwd="root@@!";
							$mysql_base_db="cj";
							$Cn=mysql_connect("localhost",$mysql_login,$mysql_passwd) or die("Can't connect to Mysqld");
							mysql_select_db($mysql_base_db,$Cn);
							#Summary

								 if(!empty($_POST)){
											$key = array_search('stop', $_POST);
											 $key=explode("_",$key);
											$ids=$key[1];
											 $del="update  message set status = 'finish' where id=$ids ;";
											  $R=mysql_query($del,$Cn);
											 }

?>

<!DOCTYPE html>
<html lang="en">
<head>        
        <!-- META SECTION -->
        <title>Eagle App</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->                                     
    </head>
    <body onload="getVmtaIpsDom()">
        <!-- START PAGE CONTAINER -->
        <div class="page-container page-navigation-top">            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                
                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal">
                    <li class="xn-logo">
                        <a href="index.php">Eagle App</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>					
                    <li class="xn-openable">
                        <a href="allmessages.php" target="_blank"><span class="fa fa-files-o"></span> <span class="xn-text">All Messages</span></a>
                    </li>
                    <li class="xn-openable">
                        <a href="send_status.php" target="_blank"><span class="fa fa-file-text-o"></span> <span class="xn-text">Pmta</span></a>
                    </li>                  
					<li class="xn-openable">
                        <a href="managePmta.php" target="_blank"><span class="fa fa-cogs"></span> <span class="xn-text">Manage - Pmta</span></a> 
                    </li>
					<li class="xn-openable">
                        <a href="#"><span class="fa fa-tasks"></span> <span class="xn-text">Sponsors</span></a>                        
                        <ul class="animated zoomIn">
                            <li><a href="sponsors/b2direct.php" target="_blank"><span class="fa fa-dollar"></span> b2direct</a></li>                            

                        </ul>
                    </li>
					<li class="xn-openable">
                        <a href="#"><span class="fa fa-wrench"></span> <span class="xn-text">Settings</span></a>                        
                        <ul class="animated zoomIn">                                             
                            <li><a href="reporting.php" target="_blank"><span class="fa fa-level-up"></span> Reporting</a></li>
							<li><a href="collectnegative.php" target="_blank"><span class="fa fa-level-up"></span> Collect Negative</a></li>
							<li><a href="upload.php" target="_blank"><span class="fa fa-upload"></span> Upload images</a></li>
                        </ul>
                    </li>
                    <!-- POWER OFF -->
					 <li class="xn-icon-button pull-right last">
                        <a href="#"><span class="fa fa-power-off"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>
                        </ul>                        
                    </li>
					
					<li class="xn-icon-button pull-right">
                        <a href="#" style="width: 250px;background: rgb(59, 78, 95);"><span>Welcome <?php echo $_SESSION['login'].', id: '.$_SESSION['id']; ?></span></a>                                                
                    </li>
					 
                    <!-- END POWER OFF -->                                       
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->                     
                
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Home - Reporting</a></li>                                      
                </ul>
                <!-- END BREADCRUMB -->                
                
                                 
                
                <!-- PAGE CONTENT WRAPPER -->
			<div class="page-content-wrap">                
											 
				<div class="builder-holder-removable"></div>

					<div class="row">
						<div class="col-md-12">
								<div class="panel-heading">
                                    <h3 class="panel-title">CollectNegative</h3>
                                </div>
                            
                            <form class="form-horizontal" name="frm_reporting" method="POST" action="collect_D02.php" target="frame" >
                                                            
                                <div class="panel">                            
                                    
                                    <div class="panel-body tab-content">
                                        <div class="tab-pane active" id="tab-first">
											<br/>
                                            <div class="form-group">                                        
                                                <label class="col-md-3 col-xs-12 control-label">ISP</label>
                                                <div class="col-md-2">
                                                    <select class="form-control" name="isp">
														<option value="no_command" selected="">Choose a Isp</option>
														<option value="hotmail">Hotmail</option>
														<option value="yahoo">Yahoo</option>
                                                    </select>
												</div>
                                              
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Mailbox:Password</label>
                                                <div class="col-md-6 col-xs-12">                                            
													<textarea  class="form-control" name="boxes" style="font-size: 12px; display: inline; resize: none; height: 250px !important;"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Report</label>
                                                <div class="col-md-6 col-xs-12">  											
												<iframe name="frame"  id="frame" scrolling="yes" frameborder="0" style="width:100%;height:185px;background-color:#F9F9F9;"></iframe>
                                                </div>
                                            </div>                                        
										</div>
                                    </div>
                                    <div class="panel-footer">                                                                        
                                        <input type="submit" class="btn btn-primary pull-right" name="exec" style="width: 130px;" value="Execute">
                                    </div>
									
                                </div>                                
                            
                            </form>
                            
                        </div>
                    </div>
					               
            </div>
                <!-- PAGE CONTENT WRAPPER -->                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->

        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>                    
                        <p>Press No if you want to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="index.php?id=destroy" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->
		
        <!-- END PRELOADS -->               

    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- THIS PAGE PLUGINS -->
        <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <!-- END PAGE PLUGINS -->       

        <!-- START TEMPLATE -->
        
        <script type="text/javascript" src="js/plugins.js"></script>        
        <script type="text/javascript" src="js/actions.js"></script>        
        <!-- END TEMPLATE -->

    <!-- END SCRIPTS -->         
    </body>

</html>

<?php
}else header('Location: index.php');
?>




