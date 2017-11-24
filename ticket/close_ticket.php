<?php

	if(!(isset($_GET)))
	{
		exit;
	}
	else
	{
		if( (isset($_GET['id_ticket']))  and  (is_numeric($_GET['id_ticket']))  and   ($_GET['id_ticket']>0)  )
		{
			//Update Status :
			$id_ticket	=	$_GET['id_ticket'];
			update_ticket_status($id_ticket,4);
			
			//Redirection :
			header('location:list_ticket.php');
		}
	}
	
	function	update_ticket_status($p_id_ticket,$p_id_status)
	{
		include('../Includes/bdd.php');
		$cmdUpdateTicketStatus = $bdd->prepare('update ticket set id_ticket_status = ? where id_ticket = ?');
		$cmdUpdateTicketStatus->execute(array($p_id_status,$p_id_ticket));
		$cmdUpdateTicketStatus->closeCursor();
	}

?>