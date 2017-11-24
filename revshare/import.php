<?php
	
	
	ini_set("memory_limit","10000000000M");
	ini_set('display_errors', 1); 
	error_reporting(E_ALL);
	session_write_close();
	set_time_limit(0);
	ignore_user_abort(true);
	
	
	include('../Includes/bdd.php');
	
	try
    {
				
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$bdd->setAttribute(PDO::MYSQL_ATTR_DIRECT_QUERY,false);
		
		$data				=	fopen("/var/www/exactarget/revshare/data_inboxus.csv", "r");
		$data_UK_AOL		=	fopen("/var/www/exactarget/revshare/uk_aol.csv", "a+");
		$data_AU_AOL		=	fopen("/var/www/exactarget/revshare/au_aol.csv", "a+");
		$data_UK_HOTMAIL	=	fopen("/var/www/exactarget/revshare/uk_hotmail.csv", "a+");
		$data_AU_HOTMAIL	=	fopen("/var/www/exactarget/revshare/au_hotmail.csv", "a+");
		$data_others		=	fopen("/var/www/exactarget/revshare/data_others.csv", "a+");
		if($data)
		{
			$cpt				=	0;
			$cpt_valid_insert	=	0;
			while(!feof($data))
			{
				$line 		= 	fgets($data);
				if ($line != "")
				{
                    $tab_line	=	explode(',',$line);
					if(count($tab_line)>=0)
					{
						$email		=	trim($tab_line[0]);
						$country	=	trim($tab_line[5]);
						
						if(!filter_var($email, FILTER_VALIDATE_EMAIL))
						{
							echo "Line  : Invalid Email Format <b style='color:red;'> $email </b> <br/>";
						}
						else
						{
							if($country=='GB')
							{
								$isp_current_email	=	get_isp_by_email_address($email);
								if($isp_current_email == 'hotmail')
								{
									fputs($data_UK_HOTMAIL,$email."\n");
								}
								elseif($isp_current_email == 'aol')
								{
									fputs($data_UK_AOL,$email."\n");
								}
								else
								{
									fputs($data_others,$email."\n");
								}
							}
							elseif($country=='AU')
							{
								$isp_current_email	=	get_isp_by_email_address($email);
								if($isp_current_email == 'hotmail')
								{
									fputs($data_AU_HOTMAIL,$email."\n");
								}
								elseif($isp_current_email == 'aol')
								{
									fputs($data_AU_AOL,$email."\n");
								}
								else
								{
									fputs($data_others,$email."\n");
								}
							}
							else
							{
								echo "$email-------$country<br/>";
							}
						}
					}
					else
					{
						echo "Line have a count of columns <5 <br/>";
					}
				}
				else
				{
					echo "Line is empty <br/>";
				}
				
				//ob_flush();
                //flush();
			}
			echo "end of file";
			
		}
		else
		{
			echo "error opening the file";
		}	
		fclose($data);
		fclose($data_UK_AOL);	
		fclose($data_AU_AOL);
		fclose($data_UK_HOTMAIL);
		fclose($data_AU_HOTMAIL);
		fclose($data_others);	
	}
    catch (Exception $e)
    {
        //die('Erreur : ' . $e->getMessage());
		throw $e;
    }
	catch(PDOException $e) 
	{
		//die('Could not connect to the database:<br/>' . $e);
		throw $e;
	}		
	
	

	
	//echo get_isp_by_email_address('soniaoliveira152@aim.com');
	
	
	
	function get_isp_by_email_address($p_email)
	{
		$result	=	null;
		if(!empty($p_email))
		{
			if
			(  
				stristr($p_email,"@hotmail.")   	or      
				stristr($p_email,"@live.")    	or    
				stristr($p_email,"@outlook.")     or   
				stristr($p_email,"@msn.")         
			)
			{
				$result	=	'hotmail';
			}
			elseif
			(
				stristr($p_email,"@aol.")   		or      
				stristr($p_email,"@aim.")    		or    
				stristr($p_email,"@netscape.")    or   
				stristr($p_email,"@wmconnect.")   or
				stristr($p_email,"@cs.")   		or
				stristr($p_email,"@compuserve.")  or
				stristr($p_email,"@luckymail.")   or
				stristr($p_email,"@icqmail.")
			)
			{
				$result	=	'aol';
			}
			else
			{
				$result	=	'others';
			}
		}
		
		return $result;
	}
	
?>


