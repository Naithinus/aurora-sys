<?php

include_once('../Includes/sessionVerificationMailer.php'); 
$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
verify($monUrl);
	 
	 
include('../Includes/bdd.php');
$idOffer = $_POST['cmbOffer'];
$requete = $bdd->prepare('select * from froms where id_Offer_From = ?');
$requete->execute(array($idOffer));
echo
'
<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
						
					        
							 <legend class="text-bold">Stats Froms</legend> 
							
							
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>ID FROM</th>
									<th>FROM</th>
									<th>TOTAL OPEN</th>
									<th>TOTAL CLICK</th>
									<th>TOTAL UNSUB</th>									
								</tr>
							</thead>
							
							<tbody>
							
							
								
							 


';
while($row = $requete->fetch())
{
       echo
	   '
	      <tr>
		    <td>'.$row['id_From'].'</td>
			<td>'.$row['text_From'].'</td>
			<td>'.$row['cptOpen_From'].'</td>
			<td>'.$row['cptClick_From'].'</td>
			<td>'.$row['cptUnsub_From'].'</td>
		  </tr>
	   ';
}

echo
'
 </tbody>
						     </table>
					</div>
					    </div>
						</div>
';


////



$requete = $bdd->prepare('select * from subjects where id_Offer_Subject = ?');
$requete->execute(array($idOffer));
echo
'
<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
						
					        
							 <legend class="text-bold">Stats Subjects</legend> 
							
							
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>ID Subject</th>
									<th>Subject</th>
									<th>TOTAL OPEN</th>
									<th>TOTAL CLICK</th>
									<th>TOTAL UNSUB</th>									
								</tr>
							</thead>
							
							<tbody>
							
							
								
							 


';
while($row = $requete->fetch())
{
       echo
	   '
	      <tr>
		    <td>'.$row['id_Subject'].'</td>
			<td>'.$row['text_Subject'].'</td>
			<td>'.$row['cptOpen_Subject'].'</td>
			<td>'.$row['cptClick_Subject'].'</td>
			<td>'.$row['cptUnsub_Subject'].'</td>
		  </tr>
	   ';
}

echo
'
 </tbody>
						     </table>
					</div>
					    </div>
						</div>
';





////




$requete = $bdd->prepare('select * from creatives where id_Offer_Creative = ?');
$requete->execute(array($idOffer));
echo
'
<div class="content">

					<!-- Form horizontal -->
					<div class="panel panel-flat">
					    <div class="panel-body">
						
					        
							 <legend class="text-bold">Stats Creatives</legend> 
							
							
							<table class="table datatable-basic">
							<thead>
								<tr>
									<th>ID Creative</th>
									<th>Creative</th>
									<th>TOTAL CLICK</th>
									<th>TOTAL UNSUB</th>									
								</tr>
							</thead>
							
							<tbody>
							
							
								
							 


';
while($row = $requete->fetch())
{
       echo
	   '
	      <tr>
		    <td>'.$row['id_Creative'].'</td>
			<td><img src="../Offer/Creatives/'.$row['name_Creative'].'" style="width:150px;height:150px;"/></td>
			<td>'.$row['cptClick_Creative'].'</td>
			<td>'.$row['cptUnsub_Creative'].'</td>
		  </tr>
	   ';
}

echo
'
 </tbody>
						     </table>
					</div>
					    </div>
						</div>
';
?>
<script type="text/javascript" src="datatables_basicStats.js"></script>