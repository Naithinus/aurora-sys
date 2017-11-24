<?php

 include_once('../Includes/sessionVerificationMailer.php'); 
 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 verify($monUrl);
 include('../Includes/bdd.php');
 
 $type = $_POST['type'];
 
 switch($type)
 {
	 
	        case "domains":
			
			$domains = $_POST['domains'];
			$explode = explode(PHP_EOL,$domains);

			 echo 
			 ' 
			 <table class="table datatable-basic">
				<thead>
					<tr>
						<th>Domain</th>
						<th>IP</th>
						<th>Server</th>
					</tr>
				</thead>
											
				<tbody>
			 ';
			 
			 foreach($explode as $domain)
			 {
				 
					echo '<tr>';
					
					 $requete = $bdd->prepare('select s.alias_Server,i.IP_IP from server s , ip i , domain d where i.id_Domain_IP = d.id_Domain and d.name_Domain = ? and i.id_Server_IP = s.id_Server');
					 $requete->execute(array($domain));
					 while($row = $requete->fetch())
					 {
						 
						  echo
						  '
							 <td>'.$domain.'</td>
							  <td>'.$row['IP_IP'].'</td>
							 <td>'.$row['alias_Server'].'</td>
						  ';
					 }
					 
					 echo '</tr>';
			 }
			 
			 
			 echo
			 '
			   </tbody>
			   </table>
			 ';
			 
			 break;
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 case "servers":
			 
			 
					$idServer = $_POST['idServer'];

					 echo 
					 ' 
					 <table class="table datatable-basic">
						<thead>
							<tr>
								<th>Server</th>
								<th>Domain</th>
								<th>IP</th>
							</tr>
						</thead>
													
						<tbody>
					 ';
					 
					 
							
							
							 $requete = $bdd->prepare('select s.alias_Server,d.name_Domain,i.IP_IP from server s , domain d , ip i where s.id_Server = i.id_Server_IP and s.id_Server = ? and i.id_Domain_IP = d.id_Domain');
							 $requete->execute(array($idServer));
							 while($row = $requete->fetch())
							 {
								  echo '<tr>';
								  
								  echo
								  '
									 <td>'.$row['alias_Server'].'</td>
									 <td>'.$row['name_Domain'].'</td>
									 <td>'.$row['IP_IP'].'</td>
								  ';
								  
								  echo '</tr>';
								 
							 }
							 

								  
							 
					 
					 
					 echo
					 '
					   </tbody>
					   </table>
					 ';
			 
			 break;
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
			  case "ips":
			
						$ips = $_POST['ips'];
						$explode = explode(PHP_EOL,$ips);

						 echo 
						 ' 
						 <table class="table datatable-basic">
							<thead>
								<tr>
									<th>IP</th>
									<th>Domain</th>
									<th>Server</th>
								</tr>
							</thead>
														
							<tbody>
						 ';
						 
						 foreach($explode as $ip)
						 {
							 
								echo '<tr>';
								
								 $requete = $bdd->prepare('select s.alias_Server,i.IP_IP,d.name_Domain from server s , domain d , ip i where i.id_Server_IP = s.id_Server and IP_IP = ? and i.id_Domain_IP = d.id_Domain');
								 $requete->execute(array($ip));
								 while($row = $requete->fetch())
								 {
									 
									  echo
									  '
									     <td>'.$ip.'</td>
										<td>'.$row['name_Domain'].'</td>
										 <td>'.$row['alias_Server'].'</td>
									  ';
								 }
								 
								 echo '</tr>';
						 }
						 
						 
						 echo
						 '
						   </tbody>
						   </table>
						 ';
			 
			 break;
			 
 }
 
?>

<script type="text/javascript" src="datatables3_basic.js"></script>