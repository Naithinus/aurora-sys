<?php

include_once('../Includes/sessionVerificationMailer.php');
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);

include('../Includes/bdd.php');

$idServer = $_POST['idServer'];

$requete = $bdd->prepare('select * from vmta where id_Server = ? and id_Mailer = ?');
$requete->execute(array($idServer,$_SESSION['id_Employer']));

echo '
<table class="table datatable-basic">
							<thead>
								<tr>
									<th>IP</th>
									<th>DOMAIN</th>
									<th>Actions</th>
									
									
								</tr>
							</thead>
							
							<tbody>
';							



							   while($row = $requete->fetch())
							   {

									echo
									'
										<tr>
											<td>'.$row["ip"].'</td>
											<td>'.$row["domain"].'</td>
											<td>
											<a href="U_VMTA.php?ip='.$row["ip"].'&m='.$_SESSION['id_Employer'].'" class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update"><i class="icon-pencil"></i></a>
											</td>
										</tr>
									';
							   }
								
								
							echo '</tbody></table>';
?>
						
<script type="text/javascript" src="datatables_basic.js"></script>						

