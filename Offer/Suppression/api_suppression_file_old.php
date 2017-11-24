<?php 





//https://mailer.optizmo.net/m-slfh-v87-b216198d5d172d1e40d6fad373967097
//https://mailer.optizmo.net/m-slfh-v87-b216198d5d172d1e40d6fad373967097&pcma=d0b909b2ee0879a268360a0552180daf&progressID=gone


if(isset($_POST['id_Offer']))
{
	
	$API_URL 			=	"api.idrivecontrol.com/pubapi.php"; 
	$API_KEY 			=	"YOUR_KEY";
	$API_FUNCTION		=	"getsuppression";
	
	
	$id_offer			=	$_POST['id_Offer'];
	if (  (is_numeric($id_offer))    and ($id_offer > 0) )
	{
		$id_sponsor	=	get_id_sponsor_by_id_offer($id_offer);
		$sid_offer	=	get_sid_offer_by_id_offer($id_offer);
		
		if
		(      
			(isset($id_sponsor)) and (is_numeric($id_sponsor))    and ($id_sponsor > 0) 	AND
			(isset($sid_offer)) and (is_numeric($sid_offer))    and ($sid_offer > 0) 
		)
		{
			$offerSuppressionFolder     =       getSuppressionFileFolderPath();  
			$sponsorSupressionFileURL	=       getSuppressionUrlFromSponsor($API_URL,$API_KEY,$API_FUNCTION,$sid_offer);
			
			
			
			if(!empty($sponsorSupressionFileURL))
			{
				$tab_result	=	explode('___',$sponsorSupressionFileURL);
				if(count($tab_result==2))
				{
					$successful_process	=	($tab_result[0]==1)?true:false;
					if($successful_process)
					{
						$sponsorSupressionFileURL	=	$tab_result[1];
						//echo "<br/>Suppression File URL : ".$sponsorSupressionFileURL."<br/>";
						$technologySuppressionFile  =	getUnsubLinkTechnology($sponsorSupressionFileURL);
						if(!empty($technologySuppressionFile))
						{
							if($technologySuppressionFile     == '[OPTIZMO]')       
							{
								$optismoAccessToken	=	getOptizmoAccessToken();
								//echo "<br/>Optizmo Access Token : ".$optismoAccessToken."<br/>";
								if($optismoAccessToken)
								{
									
									if($sponsorSupressionFileURL)
									{
										$campaignAccessKey          	=   getCampaignAccessKeyFromSupressionFileUrl($sponsorSupressionFileURL);
										//echo 'Campaign Access Key :'.$campaignAccessKey."<br/>";
										
										if($campaignAccessKey)
										{
											
											//echo $optismoAccessToken.'---------'.$campaignAccessKey;
											$suppressionFileLink    	=   optizmoListPreparation($optismoAccessToken,$campaignAccessKey,'md5');
											echo "<br/>Suppression File : ".$suppressionFileLink;
											
											
											if($suppressionFileLink)
											{
												$suppressionFileLinkTab	=	explode('__________',$suppressionFileLink);
												if(count($suppressionFileLinkTab	==	2))
												{
													$formatSuppressionFile	=	$suppressionFileLinkTab[0];
													$suppressionFileLink	=	$suppressionFileLinkTab[1];
													//echo 'Format : '.$formatSuppressionFile."<br/>".$suppressionFileLink;
													deleteAllExistingFiles($offerSuppressionFolder);
													optizmoDownloadSuppressionFile($suppressionFileLink,$offerSuppressionFolder,$id_offer,$sid_offer,$id_sponsor);
													$zipFileToExtract				=	$offerSuppressionFolder.'sp'.$id_sponsor.'-'.$id_offer.'-'.$sid_offer.'-MD5.zip';
													openExtractZipArchive($zipFileToExtract,$offerSuppressionFolder);
													renameSupressionFile($offerSuppressionFolder,$id_offer,$sid_offer,$id_sponsor);
													$final_suppression_file_name	=	moveSupressionFile($id_offer,$sid_offer,$id_sponsor);
													//echo 'final supp file name ==> '.$final_suppression_file_name;
													
													update_offer_suppression_filename($id_offer,$final_suppression_file_name);

													$techno       =   'OPTIZMO';
													echo $techno.'___'.$suppressionFileLink;
													
													//Crypter les emails en MD5 :
													if($formatSuppressionFile=='plain')
													{
														crypt_plain_to_md5('sp'.$id_sponsor.'-'.$id_offer.'-'.$sid_offer.'-MD5.txt');
													}
												}
											}
											else
											{
												echo '0 -> optizmoListPreparation()';
											}
										}
										else
										{
											echo '0 -> getCampaignAccessKeyFromSupressionFileUrl()';
										}
										
										
									}
									else
									{
										echo '0 -> getSuppressionFileUrlFromSponsor()';
									}
									
								}
								else
								{
									echo '0 ->getOptizmoAccessToken()';
								}
							}
						}
					}
					else
					{
						echo "ERROR : ".$tab_result[1];
					}
				}
			}
		}
		
		
	}
	else
	{
		echo 'is_numeric($id_offer) == false ------- OR ------  $id_offer < 0';	
	}
}
else
{
	echo '$_GET[\'id_Offer\'] is not set ! ';	
}



//$_GET['id_sponsor']	=	8;
//$_GET['id_offer']	=	48;
//$_GET['sid_offer']	=	6549;



//$id_sponsor	=	$_GET['id_sponsor'];
//$id_offer	=	$_GET['id_offer'];
//$sid_offer	=	$_GET['sid_offer'];











function 	getSuppressionFileFolderPath()
{
	return "/home/exactarget/offers/suppression/TempExtraction/";
}


function	getSuppressionUrlFromSponsor($p_api_url,$p_api_key,$p_api_function,$p_campaign_id)
{
	$result	=	null;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$p_api_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,"apikey=$p_api_key&apifunc=$p_api_function&campaignid=$p_campaign_id");

	if(empty($ch))
	{
		$result =	"0___ERROR curl_init : seems like curl is not installed on this machine !";
	}
	else
	{
		$result = curl_exec($ch);
		if(curl_errno($ch))
		{
			$result =	"0___ERREUR curl_exec : ".curl_error($ch);
		}
		else
		{
			if($result=='PLEASE WAIT A FEW MINUTES BEFORE NEXT API CALL')
			{
				$result	=	'0___PLEASE WAIT A FEW MINUTES BEFORE NEXT API CALL';
			}
			else
			{
				$xmlSuppression_list_response	=	simplexml_load_string($result);
				//print_r($xmlSuppression_list_response);
				if ($xmlSuppression_list_response === false)
				{
					$result	=	'0___Error parsing Return XML';
				}
				else
				{
					$url_suppression	=   $xmlSuppression_list_response->data->suppurl;
					if(!empty($url_suppression))
					{
						$result				=	'1___'.$url_suppression;
					}
					else
					{
						$result				=	'0___$url_suppression is empty';
					}
				}
			}
		}
		curl_close($ch);
	}
	
	return $result;
}


function 	getUnsubLinkTechnology($p_url_suppression_list)
{
	$result =   null;
	if(!empty($p_url_suppression_list))
	{
		$domainUnsubTechnology  =   getParentDomain($p_url_suppression_list);
		if(!empty($domainUnsubTechnology))
		{
			switch ($domainUnsubTechnology)
			{
				case 'b2suppress.com':
						$result     =   '[STATIC_LINK]';
						break;

				case 'unsubcentral.com':
						$result     =   '[UNSUB_CENTRAL]';
						break;

				case 'unsub-pa.com':
						$result     =   '[EZEPO]';
						break;

				case 'box.com':
						$result     =   '[BOX_PLATEFORM]';
						break;

				case 'optizmo.net':
						$result     =   '[OPTIZMO]';
						break;

				default:
						$result     =   '[UNKNOWN]';
						break;
			}

			return $result;
		}
	}
}


function 	getOptizmoAccessToken()
{
	return '8GxNp0cyzN1Pm1HPPPydL1Y5v5sey2Vr';
}


function 	getCampaignAccessKeyFromSupressionFileUrl($p_supression_file_url)
{
	$result =       null;
	if(!empty($p_supression_file_url))
	{
		$tab1   =       explode('/',$p_supression_file_url);
		if(count($tab1)>=3)
		{
			$tab2   =       explode('&',$tab1[3]);
			if(count($tab2)>=1)
			{
					$result =       $tab2[0];
			}
		}
	}
	return $result;
}


function 	optizmoListPreparation($p_optismo_access_token,$p_campaign_access_key,$format)
{
	$result             =	null;
	if(!empty($p_optismo_access_token) and !empty($p_campaign_access_key))
	{
		
		$url            =	'https://mailer_api.optizmo.net/accesskey/download/'.$p_campaign_access_key.'?token='.$p_optismo_access_token.'&format='.$format.'&deltas=0';
		//$result	=	$url;
		
		$curl_result    =   makeGetCurlRequest($url);
		
		
		
		$tab_result     =   json_decode($curl_result, true);
		
		if($tab_result['result']=='success')
		{
			//$result		=	'je suis dans if';
			$result     =   $format.'__________'.$tab_result['download_link'];
		}
		elseif( ($tab_result['result']=='error')  and   ($tab_result['error']=='You do not have access to MD5 downloads') )		
		{
			//$result 'je suis dans else';
			$format		=	'plain';
			$result		=	optizmoListPreparation($p_optismo_access_token,$p_campaign_access_key,$format);
		}
	}
	return $result;
}


function 	optizmoDownloadSuppressionFile($p_optismo_final_link,$p_destination_folder,$p_id_offer,$p_sid_offer,$p_id_sponsor)
{
	set_time_limit(0);
	ini_set("memory_limit","2048M");
	ini_set("upload_max_filesize","3000M");
	ini_set("post_max_size","3000M");
	ini_set("max_execution_time","0");
	ini_set("max_input_time","0");

	$destination_folder     =	$p_destination_folder;
	$suppression_file_name  =   'sp'.$p_id_sponsor.'-'.$p_id_offer.'-'.$p_sid_offer.'-MD5.zip';
	$file_content           =   makeGetCurlRequest($p_optismo_final_link);
	$save_to                =   $destination_folder.$suppression_file_name;
	$downloaded_file        =   fopen($save_to, 'w');
	fwrite($downloaded_file, $file_content);
	fclose($downloaded_file);
}


function 	deleteAllExistingFiles($p_folder)
{
	$directory	=   $p_folder;
	$tab_files  =   array_diff(scandir($directory), array('..', '.'));
	foreach($tab_files as $file):
		unlink($p_folder.'/'.$file);
	endforeach;
}


function 	openExtractZipArchive($p_source_archive,$p_desctination_extract_archive)
{
	/* Open the Zip file */
	$zip            =       new ZipArchive;
	$extractPath    =       $p_desctination_extract_archive;
	if($zip->open($p_source_archive) != "true")
	{
					echo "Error :- Unable to open the Zip File";
	}

	/* Extract Zip File */
	$zip->extractTo($extractPath);
	$zip->close();
}


function 	renameSupressionFile($p_folder,$p_id_offer,$p_sid_offer,$p_id_sponsor)
{
	$bFounded						=		true;	
	$directory                      =       $p_folder;
	$tab_files                      =       array_diff(scandir($directory), array('..', '.'));
	foreach($tab_files as $file):
	if(strpos($file, "suppression_list--") === 0)
	{
		$supp_fine_name	=	'/home/exactarget/offers/suppression/TempExtraction/sp'.$p_id_sponsor.'-'.$p_id_offer.'-'.$p_sid_offer.'-MD5.txt';
		rename($p_folder.'/'.$file, $supp_fine_name);
	}
	elseif(strpos($file, "MD5") === 0)
	{
		$supp_fine_name	=	'/home/exactarget/offers/suppression/TempExtraction/sp'.$p_id_sponsor.'-'.$p_id_offer.'-'.$p_sid_offer.'-MD5.txt';
		rename($p_folder.'/'.$file, $supp_fine_name);
	}
	elseif(string_ends_with($file, "unsubs.txt"))
	{
		$supp_fine_name	=	'/home/exactarget/offers/suppression/TempExtraction/sp'.$p_id_sponsor.'-'.$p_id_offer.'-'.$p_sid_offer.'-MD5.txt';
		rename($p_folder.'/'.$file, $supp_fine_name);
	}
	elseif(strpos($file, "domains_list--") === 0)
	{
		unlink($p_folder.'/'.$file);
	}
	endforeach;
}


function	moveSupressionFile($p_id_offer,$p_sid_offer,$p_id_sponsor)
{
	$source_file		=	'sp'.$p_id_sponsor.'-'.$p_id_offer.'-'.$p_sid_offer.'-MD5.txt';
	$source_folder		=	'/home/exactarget/offers/suppression/TempExtraction/'.$source_file;
	$destination_folder	=	'/home/exactarget/offers/suppression/'.$source_file;
	rename($source_folder, $destination_folder);
	
	return $source_file;
}


function 	getDomain($Address)
{
	$parseUrl = parse_url(trim($Address));

	return trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2)));
}


function 	getParentDomain($url)
{
	preg_match("/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/", parse_url($url, PHP_URL_HOST), $_domain_tld);

	return $_domain_tld[0];
}


function 	string_ends_with($string, $ending)
{
	$len        =   strlen($ending);
	$string_end =   substr($string, strlen($string) - $len);

	return $string_end == $ending;
}


function	makeGetCurlRequest($p_url_request)
{
	$result =       null;
	if(empty($p_url_request))
	{
			return $result;
	}
	else
	{
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


function 	update_offer_suppression_filename($p_id_offer,$p_name_suppression_file)
{
	include('../../Includes/bdd.php');
	
	$sqlUpdateSuppressionFileName     =   "UPDATE offer SET suppressionFile_Offer = ?,TypeSuppressionFile_Offer = ? WHERE id_offer = ?";
	$cmdUpdateSuppressionFileName     =   $bdd->prepare($sqlUpdateSuppressionFileName);
	$cmdUpdateSuppressionFileName->execute(array($p_name_suppression_file,'MD5',$p_id_offer));
	$cmdUpdateSuppressionFileName->closeCursor();
}


function	crypt_plain_to_md5($source_file)
{
	$source	=	'/home/exactarget/offers/suppression/'.$source_file;
	$target	=	'/home/exactarget/offers/suppression/temp_'.$source_file;

	// copy operation
	$sh	=	fopen($source, 'r');
	$th	=	fopen($target, 'w');
	while (!feof($sh)) 
	{
		$plain_email	=	fgets($sh);
		$plain_email	=	trim($plain_email);
		$md5_email		=	md5($plain_email).PHP_EOL;
		fwrite($th, $md5_email);
	}
	fclose($sh);
	fclose($th);

	// delete old source file
	unlink($source);

	// rename target file to source file
	rename($target, $source);
}


function	get_id_sponsor_by_id_offer($p_id_offer)
{
	$id_spponsor		=	-1;
	
	include('../../Includes/bdd.php');
	$sqlGetIdSponsor    =   "SELECT id_Sponsor_Offer FROM offer WHERE id_offer = ?";
	$cmdGetIdSponsor	=   $bdd->prepare($sqlGetIdSponsor);
	$cmdGetIdSponsor->execute(array($p_id_offer));
	$row_sponsor		=	$cmdGetIdSponsor->fetch();
	if($row_sponsor)
	{
		$id_spponsor	=	$row_sponsor['id_Sponsor_Offer'];
	}
	else
	{
		$id_spponsor	=	-1;
	}
	$cmdGetIdSponsor->closeCursor();
	
	return $id_spponsor;
}


function	get_sid_offer_by_id_offer($p_id_offer)
{
	$sid_offer		=	-1;
	
	include('../../Includes/bdd.php');
	$sqlGetSIDoffer    	=   "SELECT sid_Offer FROM offer WHERE id_offer = ?";
	$cmdGetSIDoffer	=   $bdd->prepare($sqlGetSIDoffer);
	$cmdGetSIDoffer->execute(array($p_id_offer));
	$row_offer			=	$cmdGetSIDoffer->fetch();
	if($row_offer)
	{
		$sid_offer	=	$row_offer['sid_Offer'];
	}
	else
	{
		$sid_offer	=	-1;
	}
	$cmdGetSIDoffer->closeCursor();
	
	return $sid_offer;
}
