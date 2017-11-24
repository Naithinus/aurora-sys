 <?php
	
    header("Access-Control-Allow-Origin: *");
	
	include('bdd.php');
	
	$idMailer = $_POST['idMailer'];
	
	$requete = $bdd->prepare('select n.* from notification n , notification_mailer nm where n.id_Notification = nm.id_notification and nm.is_Readed = 0 and nm.id_mailer = ?');
	$requete->execute(array($idMailer));
	$cpt = 0;					  
	while($row = $requete->fetch())
	{
	        $cpt++;
			echo 
			'
			
			<li class="media">
				<div class="media-left">
					<img src="../assets/images/placeholder.jpg" class="img-circle img-sm" alt="">
				</div>

				<div class="media-body">
					<a href="#" class="media-heading">
						<span class="text-semibold">Admin</span>
						<span class="media-annotation pull-right"></span>
					</a>

					<span class="text-muted">'.$row['text_Notification'].'</span>
				</div>
			</li>
			
			';
			
	}
	if($cpt>0)
	echo '*'.$cpt;