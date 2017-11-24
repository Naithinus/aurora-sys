<?php
	header("Access-Control-Allow-Origin: *");
	if(isset($_POST))
	{
		$id_sponsor		=	(isset($_POST['id_sponsor']))?$_POST['id_sponsor']:null;	
		$id_vertical	=	(isset($_POST['id_vertical']))?$_POST['id_vertical']:null;
		if( ($id_sponsor>0)  and  ($id_vertical>0) )
		{
			include('../Includes/bdd.php');
			$requete = $bdd->prepare('select * from offer where id_Sponsor_Offer = ? and id_Vertical_Offer = ?');
			$requete->execute(array($id_sponsor,$id_vertical));
			while($row = $requete->fetch())
			{
			   echo
			   '
				  <option value="'.$row["id_offer"].'">'.$row["name_Offer"].'</option>
			   ';
			}
		}
	}
	else
	{
		exit;
	}
?>