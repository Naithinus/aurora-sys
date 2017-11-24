<?php
     include_once('../Includes/sessionVerificationMailer.php'); 
	 $monUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 verify($monUrl);
	 
	 

   include('../Includes/bdd.php');
   $type = $_POST['type'];
   
   switch($type)
   {
      case 'sponsor':
	    $idSponsor = $_POST['id_Sponsor'];
	    $requete = $bdd->prepare('select * from offer where id_Sponsor_Offer = ?');
		$requete->execute(array($idSponsor));
		echo '<option value="-1">Please Select</option>';
		while($row = $requete->fetch())
		{
		   echo
		   '
		      <option value="'.$row["id_offer"].'">'.$row["name_Offer"].'</option>
		   ';
		}
	  break;
	  
	  
	   case 'vertical':
	    $idSponsor = $_POST['id_Sponsor'];
	    $idVertical = $_POST['id_Vertical'];
	    $requete = $bdd->prepare('select * from offer where id_Sponsor_Offer = ? and id_Vertical_Offer = ?');
		$requete->execute(array($idSponsor,$idVertical));
		echo '<option value="-1">Please Select</option>';
		while($row = $requete->fetch())
		{
		   echo
		   '
		      <option value="'.$row["id_offer"].'">'.$row["name_Offer"].'</option>
		   ';
		}
	  break;
	  
	  
	  
	  
	  case 'from':
	    $idOffer = $_POST['id_Offer'];

	    $requete = $bdd->prepare('select * from froms where id_Offer_From = ?');
		$requete->execute(array($idOffer));
		echo '<option value="-1">Select From</option>';
		while($row = $requete->fetch())
		{
		   echo
		   '
		      <option value="'.$row["id_From"].'">'.$row["text_From"].'</option>
		   ';
		}
	  break;
	  
	  
	  case 'subject':
	    $idOffer = $_POST['id_Offer'];

	    $requete = $bdd->prepare('select * from subjects where id_Offer_Subject = ?');
		$requete->execute(array($idOffer));
		echo '<option value="-1">Select Subject</option>';
		while($row = $requete->fetch())
		{
		   echo
		   '
		      <option value="'.$row["id_Subject"].'">'.$row["text_Subject"].'</option>
		   ';
		}
	  break;
	  
	  
	  case 'creative':
	  $idOffer = $_POST['id_Offer'];
	  $requete = $bdd->prepare('select * from creatives where id_Offer_Creative= ? and isUnsub_Creativ = 0');
	  $requete->execute(array($idOffer));
	  $cpt = $requete->rowCount();
	  echo '<ol class="carousel-indicators">';
	  for($i=0;$i<$cpt;$i++)
	  {
		  echo
		  '
			<li data-target="#carousel-example-generic" data-slide-to="'.$i.'"></li>
		  ';
	  }
	  echo '</ol>';
    
	$first = true;
	echo '<div class="carousel-inner">';
     
	 while($row = $requete->fetch())
	 {
	    if($first)
		{
		   $first = false;
		   echo
		   '
		    <div class="item active">
					<img src="../../Creatives/'.$row["name_Creative"].'" alt="..." style="width:350px;height:350px;margin:auto;" class="img" id="'.$row["id_Creative"].'">
					<div class="carousel-caption">
					<h3></h3>
					</div>
		    </div>
		   '; 
		}
		else
		{
		    echo
			 '
			<div class="item">
						<img src="../../Creatives/'.$row["name_Creative"].'" alt="..." style="width:350px;height:350px;margin:auto;" class="img" id="'.$row["id_Creative"].'">
						<div class="carousel-caption">
						<h3></h3>
						</div>
			</div>
			';
		}
    }
    echo '</div>';

    echo
	'
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev" style="background-image:none;">
		        <br/><br/><br/><br/><br/>
				 <span class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-chevron-left"></i></span>
		</a>
	 
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next" style="background-image:none;">
				 <br/><br/><br/><br/><br/>
				 <span class="btn border-teal text-teal btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i class="icon-chevron-right"></i></span>
		</a>
	';
	  break;
	  
	  
	  case 'isp':
	    $idIsp   = $_POST['id_Isp'];
		$country = $_POST['country'];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 1 and l.id_Country_List = ?');
				$requeteCountingList->execute(array($idIsp,$country));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalFresh			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 2 and l.id_Country_List = ?');
				$requeteCountingList->execute(array($idIsp,$country));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalDelivered			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 3 and l.id_Country_List = ?');
				$requeteCountingList->execute(array($idIsp,$country));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalOpeners			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 4 and l.id_Country_List = ?');
				$requeteCountingList->execute(array($idIsp,$country));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalClickers			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 5 and l.id_Country_List = ?');
				$requeteCountingList->execute(array($idIsp,$country));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalUnsubscribers			 = $rowCountingList[0];
				
				$requete = $bdd->prepare('select * from list where id_ISP_List = ? and id_Country_List = ?');
				$requete->execute(array($idIsp,$country));
				$cpt=0;
				
				echo
				'<tr>
					<th>Fresh     <span class="label bg-blue" style="float:right;position:relative;">'.$totalFresh.'</span></th>
					<th>Delivered <span class="label bg-blue" style="float:right;position:relative;">'.$totalDelivered.'</span> </th>
					<th>Openers   <span class="label bg-blue" style="float:right;position:relative;">'.$totalOpeners.'</span></th>
					<th>Clickers  <span class="label bg-blue" style="float:right;position:relative;">'.$totalClickers.'</span></th>
					<th>Unsubscribers <span class="label bg-blue" style="float:right;position:relative;">'.$totalUnsubscribers.'</span></th>
				  </tr>
				';
				echo '<tr>';
				while($row = $requete->fetch())
				{
				       
					   if( $_SESSION['id_Employer']!=1 && $_SESSION['id_Employer']!=5 && $_SESSION['id_Employer']!=36)
					   {
							   if($row["id_List"] != '400' && $row["id_List"] != '401' && $row["id_List"] != '402' && $row["id_List"] != '403' && $row["id_List"] != '404')
							   {
									   $subRequete = $bdd->prepare('select count(*) from email where id_List_Email = ?');
									   $subRequete->execute(array($row['id_List']));
									   $subRow    = $subRequete->fetch();
									   $countList = $subRow[0];
									   
									   echo '<td><div style="width:100px;display:inline-block;">'.$row["name_List"].'</div> 
									   <div align="right">
									   <span class="label bg-success-400">'.$countList.'</span>
									   <input class="chkListSelect" type="checkbox" name="chkList[]" value="'.$row["id_List"].'" style="position:relative;top:2px;left:10px;"/></td>
										</div>';
										$cpt++;
									   if($cpt==5)
									   {
										 $cpt=0;
										 echo '</tr><tr>';
									   }
							   }
							  
					   }
					   
					   else
					   {
					     
									   $subRequete = $bdd->prepare('select count(*) from email where id_List_Email = ?');
									   $subRequete->execute(array($row['id_List']));
									   $subRow    = $subRequete->fetch();
									   $countList = $subRow[0];
									   
									   echo '<td><div style="width:100px;display:inline-block;">'.$row["name_List"].'</div> <span class="label bg-success-400">'.$countList.'</span><input type="checkbox" class="chkListSelect" name="chkList[]" value="'.$row["id_List"].'" style="position:relative;top:2px;left:10px;"/></td>';
										$cpt++;
									   if($cpt==5)
									   {
										 $cpt=0;
										 echo '</tr><tr>';
									   }
									   
					   }
				}
		 
				 
		
	  break;
	  
	  
	  
	  
	  case 'ispCopy':
	    $idIsp   = $_POST['id_Isp'];
		
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 1');
				$requeteCountingList->execute(array($idIsp));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalFresh			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 2');
				$requeteCountingList->execute(array($idIsp));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalDelivered			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 3');
				$requeteCountingList->execute(array($idIsp));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalOpeners			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 4');
				$requeteCountingList->execute(array($idIsp));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalClickers			 = $rowCountingList[0];
				
				$requeteCountingList = $bdd->prepare('select count(*) from email e,list l where e.id_List_Email = l.id_List and l.id_ISP_List = ? and l.id_Type_List = 5');
				$requeteCountingList->execute(array($idIsp));
				$rowCountingList     = $requeteCountingList->fetch();
				$totalUnsubscribers			 = $rowCountingList[0];
				
				$requete = $bdd->prepare('select * from list where id_ISP_List = ?');
				$requete->execute(array($idIsp));
				$cpt=0;
				
				echo
				'<tr>
					<th>Fresh     <span class="label bg-blue" style="position:relative;left:0px;">'.$totalFresh.'</span></th>
					<th>Delivered <span class="label bg-blue" style="position:relative;left:0px;">'.$totalDelivered.'</span> </th>
					<th>Openers   <span class="label bg-blue" style="position:relative;left:0px;">'.$totalOpeners.'</span></th>
					<th>Clickers  <span class="label bg-blue" style="position:relative;left:0px;">'.$totalClickers.'</span></th>
					<th>Unsubscribers <span class="label bg-blue" style="position:relative;left:0px;">'.$totalUnsubscribers.'</span></th>
				  </tr>
				';
				echo '<tr>';
				while($row = $requete->fetch())
				{
				       
					   if( $_SESSION['id_Employer']!=1 && $_SESSION['id_Employer']!=5 && $_SESSION['id_Employer']!=36)
					   {
							   if($row["id_List"] != '400' && $row["id_List"] != '401' && $row["id_List"] != '402' && $row["id_List"] != '403' && $row["id_List"] != '404')
							   {
									   $subRequete = $bdd->prepare('select count(*) from email where id_List_Email = ?');
									   $subRequete->execute(array($row['id_List']));
									   $subRow    = $subRequete->fetch();
									   $countList = $subRow[0];
									   
									   echo '<td><div style="width:100px;display:inline-block;">'.$row["name_List"].'</div> <span class="label bg-success-400">'.$countList.'</span><input class="chkListSelect" type="checkbox" name="chkList[]" value="'.$row["id_List"].'" style="position:relative;top:2px;left:10px;"/></td>';
										$cpt++;
									   if($cpt==5)
									   {
										 $cpt=0;
										 echo '</tr><tr>';
									   }
							   }
							  
					   }
					   
					   else
					   {
					     
									   $subRequete = $bdd->prepare('select count(*) from email where id_List_Email = ?');
									   $subRequete->execute(array($row['id_List']));
									   $subRow    = $subRequete->fetch();
									   $countList = $subRow[0];
									   
									   echo '<td><div style="width:100px;display:inline-block;">'.$row["name_List"].'</div> <span class="label bg-success-400">'.$countList.'</span><input type="checkbox" class="chkListSelect" name="chkList[]" value="'.$row["id_List"].'" style="position:relative;top:2px;left:10px;"/></td>';
										$cpt++;
									   if($cpt==5)
									   {
										 $cpt=0;
										 echo '</tr><tr>';
									   }
									   
					   }
				}
		 
				 
		
	  break;
	  
	  
	  
   }
?>

<script>

</script>	