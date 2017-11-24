
<?php




$url_request	=	'https://mailer_api.optizmo.net/accesskey/download/sm-ntpf-h82-984206d2c3b86927ab19bed7f144db57?token=8GxNp0cyzN1Pm1HPPPydL1Y5v5sey2Vr&format=md5&deltas=0';
$result  =	makeGetCurlRequest($url_request);
echo $result;



function	makeGetCurlRequest($p_url_request)
{
	$result =       null;
	if(empty($p_url_request))
	{
			//return 'je suis dans if';
			return $result;
	}
	else
	{
		
		//return 'Je suis dans else';
		
		//2-1 : Set curl Options :
		$options        =
		array
		(
				CURLOPT_URL            => $p_url_request, 	// Url cible (l'url la page que vous voulez download)
				CURLOPT_RETURNTRANSFER => true, 			// Retourner le contenu download dans une chaine (au lieu de l'afficher directement)
				CURLOPT_HEADER         => false, 			// Ne pas inclure l'entete de reponse du serveur dans la chaine retournee
				CURLOPT_SSL_VERIFYHOST => false, 			//
				CURLOPT_SSL_VERIFYPEER => false
		);


		// 2-2 : Get cURL resource
		$curl           =       curl_init();
		if(empty($curl))
		{
				$result =       "ERREUR curl_init : Il semble que cURL ne soit pas disponible.";
		}
		
		
		// 2-3 : We are passing options to curl instance
		curl_setopt_array($curl, $options);

		// 2-4 : Send the request & save response to $resp
		$result = curl_exec($curl);

		// 2-5 : Si il s'est produit une erreur lors du download
		if(curl_errno($curl))
		{
				$result =       "ERREUR curl_exec : ".curl_error($curl);
		}
		
		// 2-6 : Close request to clear up some resources
		curl_close($curl);
		$curl   =       null;
		
		return $result;
		
	}
} 



?>