<?php
	include_once('../Includes/sessionVerification.php'); 
	$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	verify($monUrl);
	 
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EXT - FTP Tool</title>
	
    <?php include('../Includes/css.php');?>
	<?php include('../Includes/js.php');?>
	
	<script type="text/javascript" src="../assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/forms/selects/select2.min.js"></script>
	<script type="text/javascript" src="../assets/js/pages/form_select2.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="../assets/js/plugins/pickers/daterangepicker.js"></script>
	<script type="text/javascript" src="../assets/js/pages/picker_date.js"></script>

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
							<h4><span class="text-semibold">FTP Tool</span> </h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="../indexOfficial.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li><a href="#">Tools</a></li>
							<li class="active">FTP Tool</li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-flat">
								<div class="panel-heading">
									<h2 class="panel-title"><u>FTP Tool :</u>  File Uploader</h2>
									<div class="heading-elements">
										<ul class="icons-list">
					                		<li><a data-action="collapse"></a></li>
					                		<li><a data-action="reload"></a></li>
										</ul>
				                	</div>
								</div>

				                <div class="panel-body">
				                	<br/>
									<form class="form-horizontal" method="POST" id="frmUploadExactarget" name="frmUploadExactarget">
										
										<!-- Source Server -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Source server</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<select name="cbxServers" id="cbxServers" class="select-clear" data-placeholder="Select From">
																 <?php
																	include('../Includes/bdd.php');
																	$requete = $bdd->prepare('select id_Server,alias_Server from server where isActive_Server = ? order by alias_Server ASC');
																	$requete->execute(array(1));
																	while($row = $requete->fetch())
																	{
																?> 
																	<option value="<?php echo $row['id_Server'];?>" ><?php echo $row['alias_Server'];?></option><?php
																	} 
																 ?>
																</select>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										
										
										
										<!-- Root Folder -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">Root Folder</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<select name="cbxRootFolder" id="cbxRootFolder" class="select-clear" data-placeholder="Select From">
																	<option value="/var/www/">/var/www/</option>
																	<option value="/var/log/pmta/">/var/log/pmta/</option>
																</select>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										
										
										<!-- File path -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label class="col-lg-2 control-label">File path</label>
													<div class="col-lg-10">
														<div class="row">
															<div class="col-md-12">
																<input type="text text-bold" class="form-control" name="txtFilePath" id="txtFilePath" placeholder=""  />
															</div>
														</div>
													</div>
												</div>
												
											</div>
										</div>
										
										
										
										
										
										
										
										<!-- Button Upload -->
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<div class="col-lg-12">
														<div class="row text-center">
															<button type="submit" class="btn btn-primary" id="btnUpload" name="btnUpload" >Upload
																<i class="icon-upload position-right"></i>
															</button>
														</div>
													</div>
												</div>
												
											</div>
										</div>
										
										
										
										<!-- Output -->
										<div class="row" style="margin:auto;width:100%;margin-bottom:20px;overflow:scroll;height:350px;overflow-x:hidden;border:1px solid #ddd;background-color:#f1f1f1;zoom:1;">
										
										
										<center>
								<?php
									if
									(
										isset($_POST['btnUpload']) 		and 
										isset($_POST['txtFilePath']) 	and 
										!empty($_POST['txtFilePath']) 	and 
										isset($_POST['cbxServers']) 	and
										isset($_POST['cbxRootFolder'])
									)
									{
										//var_dump($_POST);
										$path_file_to_upload	=	$_POST['txtFilePath'];
										$source_server			=	$_POST['cbxServers'];
										$root_folder			=	$_POST['cbxRootFolder'];
										if
										( 
											(!empty($path_file_to_upload)) 	and 
											($source_server>0) 				and 
											(is_numeric($source_server))   	and
											(!empty($root_folder))
										)
										{
											$is_recursive_upload	=	(getFileName($path_file_to_upload)==='*')?true:false;
											if($is_recursive_upload)
											{
												$sqlGetServers  =   
												'
													SELECT 
														S.id_Server,
														S.alias_Server,
														I.IP_IP,
														S.username_Server,
														S.password_Server 
													FROM 
														server S,ip I 
													WHERE 
														S.isActive_Server 	=	?
													AND	
														S.id_Server 		=	?
													AND
														S.id_Server		=	I.id_Server_IP
													AND
														S.id_IP_Server	=	I.id_IP
														
												';
												$cmdGetServers  =   $bdd->prepare($sqlGetServers);
												$cmdGetServers->execute(array(1,$source_server));
												$server   		=   $cmdGetServers->fetch();
												if($server)
												{
													$server_name=	trim($server['alias_Server']);
													$main_ip    =   trim($server['IP_IP']);
													$login      =   trim($server['username_Server']);
													$password   =   trim($server['password_Server']);
												
													$conn 		= ssh2_connect($main_ip, 22);
													ssh2_auth_password($conn, $login, $password);
													$sftp 		= 	ssh2_sftp($conn);
													$folder		=	str_replace("*","",$path_file_to_upload);
													$folder		=	$root_folder.$folder;
													$files 		= 	scandir('ssh2.sftp://' . $sftp . $folder);
													if(count($files>0))
													{
														foreach($files as $file)
														{
															if( ($file	===	'.')  or (($file	===	'..')) )
																continue;
															else
															{
																$permitted_extensions	=	array('php','js','html','csv','log');
																if(in_array(pathinfo($file, PATHINFO_EXTENSION), $permitted_extensions)) 
																{
																	echo $file."<br/>";
																	
																	//Download the file from source server to the master server :
																	download_file_from_remote_server($source_server,$root_folder,get_full_path($file,$path_file_to_upload),'/var/www/exactarget/tools/DIRECTORY_UPLOAD/');
												
																	//Uploader le fichier dans tous les serveurs :
																	upload(get_full_path($file,$path_file_to_upload),$root_folder);
																}
															}
														}
													}
													else
													{
														echo "Empty folder";
													}
													ssh2_exec($conn, 'exit');
													unset($conn);
												}
											}											
											else
											{
												//Download the file from source server to the master server :
												download_file_from_remote_server($source_server,$root_folder,$path_file_to_upload,'/var/www/exactarget/tools/DIRECTORY_UPLOAD/');
										
												//Uploader le fichier dans tous les serveurs :
												upload($path_file_to_upload,$root_folder);
											}
										}
									}
								?>
								</center>
										
										
										
										
										</div>
								</div>
				            </div>
						</div>
						
					</div>
					
					
					
					
					
				
				
				
			</form>
					
					
					
					
			
			
			
			
					
					
					    
					

					
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
	function getFileName($p_path_file_to_upload)
	{
		$result	=	null;
		
		if(!empty($p_path_file_to_upload))
		{
			$tab_path		=	explode('/',$p_path_file_to_upload);
			$tab_path_count	=	count($tab_path);
			if($tab_path_count>0)
			{
				$result	=	$tab_path[$tab_path_count-1];
			}
		}
		
		return $result;
	}
	
	function upload($p_path_file_to_upload,$p_root_folder)
	{
		echo "<br/><br/><br/>";
		echo "##### START : STEP 2######################<br/>";
		echo "##### UPLOADING to all SERVERS############<br/>";
				
		$filename				=	getFileName($p_path_file_to_upload);
		if(function_exists("ssh2_connect")) 
		{
			if(file_exists('/var/www/exactarget/tools/DIRECTORY_UPLOAD/'.$filename))
			{
				
				//Connexion à la bdd :
				include('../Includes/bdd.php');
				
				//Boucler sur chaque Serveur et Uploader le fichier dans son dossier (passé en paramètre) :
				$sqlGetServers  =   
				'
					SELECT 
						S.id_Server,
						S.alias_Server,
						I.IP_IP,
						S.username_Server,
						S.password_Server 
					FROM 
						server S,ip I 
					WHERE 
						S.isActive_Server 	=	?
					AND
						S.id_Server			>	1		
					AND
						S.id_Server			=	I.id_Server_IP
					AND
						S.id_IP_Server		=	I.id_IP
				';
				$cmdGetServers  =   $bdd->prepare($sqlGetServers);
				$cmdGetServers->execute(array(1));
				while($server   =   $cmdGetServers->fetch())
				{
					$server_name=	$server['alias_Server'];
					$main_ip    =   trim($server['IP_IP']);
					$login      =   trim($server['username_Server']);
					$password   =   trim($server['password_Server']);
					$connection = 	ssh2_connect($main_ip, 22);
					echo "<b>Connecting to Server <u>$server_name</u> ...</b><br/>";
					if($connection) 
					{
						echo "<span style='color:green;'>Connected successfully</span><br/>";
						if(ssh2_auth_password($connection, $login, $password))
						{
							ssh2_scp_send($connection, '/var/www/exactarget/tools/DIRECTORY_UPLOAD/'.$filename,$p_root_folder.$p_path_file_to_upload, 0777);
							echo "<b>file : $filename was uploaded <span class='text-success'>successfully</span></b><br/><br/><br/>";
						}
						else
						{
							echo "<br/><span syle='color:red;'>Failed to authenticate to server $server_name [$main_ip]!</span><br/>";
						}
						ssh2_exec($connection, 'exit');
						unset($connection);
					}
					else
					{
						echo "<br/><span style='color:red;'>SSH validation failed for Server <b>$server_name [$main_ip]</b></span><br/>";
					}
					ob_flush();
					flush();
				}
			}
			else
			{
				echo "<br/><span style='color:red;'>The file does not exist !</span><br/>";
			}
		}
		else
		{
			echo "<br/><span style='color:red;'>ssh2_connect() doesn\'t exists. </span><br/>";
		}
		
		echo "##### END : STEP 2 #######################<br/>";
	}
	
	function download_file_from_remote_server($p_id_server,$p_root_folder,$p_remote_file,$p_local_file)
	{
		if
		( 
			($p_id_server>0) 			and 
			(!empty($p_remote_file))  	and 
			(!empty($p_local_file)) 	and 
			(!empty($p_root_folder)) 
		)
		{
			include('../Includes/bdd.php');
			$sqlGetServers  =   
			'
			SELECT 
				S.id_Server,
				S.alias_Server,
				I.IP_IP,
				S.username_Server,
				S.password_Server 
			FROM 
				server S,ip I 
			WHERE 
				S.isActive_Server 	=	? 
			AND 
				S.id_Server 		=	?
			AND
				S.id_Server			=	I.id_Server_IP
			AND
				S.id_IP_Server		=	I.id_IP
			'
			;
			$cmdGetServers  =   $bdd->prepare($sqlGetServers);
			$cmdGetServers->execute(array(1,$p_id_server));
			$server   		=   $cmdGetServers->fetch();
			if($server)
			{
				$server_name=	$server['alias_Server'];
				$main_ip    =   trim($server['IP_IP']);
				$login      =   trim($server['username_Server']);
				$password   =   trim($server['password_Server']);
				
				echo "##### START : STEP 1######################<br/>";
				echo "##### DOWNLOADING to server MASTER########<br/>";
			
				//Get filename :
				$filename	=	getFileName($p_remote_file);
			
				//Download the file from source server to the mater server :
				if(function_exists("ssh2_connect")) 
				{
					$connection = 	ssh2_connect($main_ip, 22);
					echo "<b>Connecting to Server $server_name ...</b><br/>";
					if($connection) 
					{
						echo "<span style='color:green;'>\t\t\t\tConnected successfully</span><br/>";
						if(ssh2_auth_password($connection, $login, $password))
						{
							ssh2_scp_recv($connection,$p_root_folder.$p_remote_file,$p_local_file.$filename);
							echo "\t\t\t\t<b>$filename was downloaded <span class='text-success'>successfully</span> from server <u>$server_name</u> to the <u>master</u> server </b><br/>";
						}
						else
						{
							echo "<br/><span syle='color:red;'>Failed to authenticate to server $server_name [$main_ip]!</span><br/>";
						}
						ssh2_exec($connection, 'exit');
						unset($connection);
					}
					else
					{
						echo "<br/><b><span style='color:red;'>Unable to connect to Server $server_name [$main_ip]</span></b><br/>";
					}
				}

				echo "##### END : STEP 1 #######################<br/>";
				echo "<hr/ style='background-color:black;border-top:5px solid #ccc;margin:20px 20px;'>";
			}
		}
	}
	
	function get_full_path($p_filename,$p_folder_path)
	{
		$result			=	null;
		
		if(  !empty($p_filename) and !empty($p_folder_path)  )
		{
			$p_folder_path	=	str_replace("*","",$p_folder_path);
			$result			=	$p_folder_path.$p_filename;
		}
		
		return $result;
	}	
	

?>