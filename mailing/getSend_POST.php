<?php
		
		include_once('../Includes/sessionVerificationMailer.php'); 
		$monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		verify($monUrl);
 
		date_default_timezone_set('UTC');
		
		if($_POST['idMailer']=="0" || $_POST['idMailer']=="-1" )
		{
			
			echo '<center><span class="label bg-success-400"><h6>Last 5 SEND</h6></span></center><br/>';
			
			echo 
					 ' 
					 <table class="table datatable-basic">
						<thead>
							<tr>
								<th>ID SEND</th>
								<th>Mailer Name</th>
								<th>Offer</th>
								<th>ISP</th>
								<th>List</th>
								<th>Fraction</th>
								<th>Seed</th>
								<th>X-Delay</th>
								<th>Count</th>
								<th>Actions</th>
							</tr>
						</thead>
													
						<tbody>
					 ';
					 
				include('../Includes/bdd.php');
				

				
				

				$requete = $bdd->prepare("select e.lastName_Employer , o.name_Offer, i.name_isp, s.* from send s , offer o , isp i , employer e where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp and e.id_Employer = s.id_Employer_Send and date(s.dateCreation) between ? and ? order by s.id_Send desc limit 5");
				$requete->execute(array(date("Y-m-d"),date("Y-m-d")));
				
				while($row = $requete->fetch())
				{
				   $mailerLastName = $row['lastName_Employer'];
				   
				   $idS            = $row['id_Send'];
				   
				   $subRequete     = $bdd->prepare('select l.name_List,tl.name_TypeList from sendlist sl , list l , typelist tl where sl.id_List = l.id_List and l.id_Type_List = tl.id_TypeList and sl.id_Send = ?');
				   $subRequete->execute(array($idS));
				   //$subRow = $subRequete->fetch();
				   
				   $data = $subRequete->fetchAll();
				   
				   if(count($data)>1)
				    $listName = 'Mixed';
					
				   else if(count($data)==1)
				    $listName = $data[0][0].'-'.$data[0][1];
					
				   else
				   {
					   if($row['id_ISP_Send'] == 3)
					    $listName = 'Sender';
					   else
					    $listName = $row['extra'];
				   }
								   
					
						
				   $tableName      = $mailerLastName.$row['id_Send'];
				   $requeteCount   = $bdd->query("select count(*) from $tableName");
								   
				   $countList      = 0;
					  
					  if($requeteCount)
					  {
						      $rowCount	   = $requeteCount->fetch();
						   
							  if(($rowCount[0] - $row['startFrom_Send']) > 0)
								$countList = $rowCount[0] - $row['startFrom_Send'];
					  }

				   echo
				   '
						<tr>
							<td>'.$row["id_Send"].'</td>
							<td>'.$row["lastName_Employer"].'</td>
							<td>'.$row["name_Offer"].'</td>
							<td>'.$row["name_isp"].'</td>
							<td>'.$listName.'</td>
							<td><input type="text" class="form-control" style="width:70px;"/></td>
							<td><input type="text" class="form-control" style="width:70px;"/></td>
							<td><input type="text" class="form-control" style="width:70px;"/></td>
							<td><span class="label bg-success-400">'.$countList.'</span></td>
							<td>				  
							  
							  <a href="Send/updateSend.php?id_Send='.$row["id_Send"].'" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
							  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="'.$row["id_Send"].'"><i class="icon-person"></i></a>
							  <a href="Send/ShowSendStats.php?id_Send='.$row["id_Send"].'" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
							  <a href="Send/send_history.php?id_Send='.$row["id_Send"].'" class="btn border-indigo-300 text-indigo-300 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="History"><i class="icon-history"></i></a>
							</td>
						</tr>
					';

				}
				
				echo
				'
					</tbody>
					</table>
				';
				
				
				
		}
		
		else
		{
			
				$startDate = $_POST['startDate'];
				$endDate   = $_POST['endDate'];
				
				echo 
					 ' 
					 <table class="table datatable-basic">
						<thead>
							<tr>
								<th>ID SEND</th>
								<th>Offer</th>
								<th>ISP</th>
								<th>List</th>
								<th>Fraction</th>
								<th>Seed</th>
								<th>X-Delay</th>
								<th>Count</th>
								<th>Actions</th>
							</tr>
						</thead>
													
						<tbody>
					 ';
					 
				include('../Includes/bdd.php');
				
				$idMailer = $_POST['idMailer']; 
				
				$requete = $bdd->prepare('select lastName_Employer from employer e where id_Employer = ?');
				$requete->execute(array($idMailer));
				$row =  $requete->fetch();
				
				$mailerLastName = $row['lastName_Employer'];

				$requete = $bdd->prepare("select o.name_Offer, i.name_isp, s.* from send s , offer o , isp i where s.id_Offer_Send = o.id_Offer and s.id_ISP_Send = i.id_Isp and s.id_Employer_Send=? and date(s.dateCreation) between ? and ? order by s.id_Send desc");
				$requete->execute(array($idMailer,$startDate,$endDate));
				
				while($row = $requete->fetch())
				{
				   $idS            = $row['id_Send'];
				   
				   $subRequete     = $bdd->prepare('select l.name_List,tl.name_TypeList from sendlist sl , list l , typelist tl where sl.id_List = l.id_List and l.id_Type_List = tl.id_TypeList and sl.id_Send = ?');
				   $subRequete->execute(array($idS));
				   //$subRow = $subRequete->fetch();
				   
				   $data = $subRequete->fetchAll();
				   
				   if(count($data)>1)
				    $listName = 'Mixed';
					
				   else if(count($data)==1)
				    $listName = $data[0][0].'-'.$data[0][1];
					
				   else
				   {
					   if($row['id_ISP_Send'] == 3)
					    $listName = 'Sender';
					   else
					    $listName = $row['extra'];
				   }
				   
					
						
				   $tableName      = $mailerLastName.$row['id_Send'];
				   $requeteCount   = $bdd->query("select count(*) from $tableName");
				   
				   $countList      = 0;
				   
				   if($requeteCount)
				   {
				        $rowCount	   = $requeteCount->fetch();
				     
				  	  if(($rowCount[0] - $row['startFrom_Send']) > 0)
				  		$countList = $rowCount[0] - $row['startFrom_Send'];
				   }

				   echo
				   '
						<tr>
							<td>'.$row["id_Send"].'</td>
							<td>'.$row["name_Offer"].'</td>
							<td>'.$row["name_isp"].'</td>
							<td>'.$listName.'</td>
							<td><input type="text" class="form-control" style="width:70px;"/></td>
							<td><input type="text" class="form-control" style="width:70px;"/></td>
							<td><input type="text" class="form-control" style="width:70px;"/></td>
							<td><span class="label bg-success-400">'.$countList.'</span></td>
							<td>				  
							  
							  <a href="Send/updateSend.php?id_Send='.$row["id_Send"].'" class="btn border-warning-400 text-warning-400 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Update Send"><i class=" icon-pencil"></i></a>
							  <a class="btn border-blue text-blue btn-flat btn-rounded btn-icon btn-xs valign-text-bottom btnTestSend" title="Test" id="'.$row["id_Send"].'"><i class="icon-person"></i></a>
							  <a href="Send/ShowSendStats.php?id_Send='.$row["id_Send"].'" class="btn border-pink text-pink btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="Stats"><i class="icon-stats-dots"></i></a>
							  <a href="Send/send_history.php?id_Send='.$row["id_Send"].'" class="btn border-indigo-300 text-indigo-300 btn-flat btn-rounded btn-icon btn-xs valign-text-bottom" title="History"><i class="icon-history"></i></a>
							  
							</td>
						</tr>
					';

				}
				
				echo
				'
					</tbody>
					</table>
				';
		}		
													
?>
<script type="text/javascript" src="Send/datatables3_basic.js"></script>