<?php

	if( isset($_GET['id']) && !empty($_GET['id']) )
	{
		require_once 'main.php';
		
		if(is_file(DIR_SECURE_INI.'/site_config.ini'))
	    {
	    	$site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');     
	    } elseif (is_file(DIR_IOW_APP_INI.'/site_config.ini')) {
	    	$site_a = parse_ini_file(DIR_IOW_APP_INI.'/site_config.ini');     
	    } else {
	    	$msg = 'The INI file site_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
	 
		$md5_id = md5($site_a['SALT'].SITE_WWW);
		
		//only if the id is correct will it do anything at all
		if( $_GET['id'] == $md5_id )
		{	
			//update fileman
		    $log = '<h1>Start Daily</h1>';
		    
		    //search submit
			if($site_a['SEARCH_SUBMIT'])
			{
				$menu_common_ns = NS.MODULES.'\\menu\\models\\common\\common';
				$menu_common = $menu_common_ns::getInstance();
				$log .= '<h4>Submitting Sitemaps</h4><p>'."\n";
				$log .= $menu_common->submitSitemap();
				$log .= "</p>\n";
			}
		
			//get the modules
		    if(is_file(DIR_SECURE_INI.'/site_module_config.ini')) 
		    {
			    try
			    {			    
				    //get a list of the modules being used
			    	$site_modules_a = array_keys(parse_ini_file(DIR_SECURE_INI.'/site_module_config.ini',true));
			    	
			    } catch (Exception $e) {
			        //process the error
			        $html_msg_ns = NS_APP_CLASSES.'\\html\\htmlmsg';
			        $htmlOutput = new $html_msg_ns($e,DEBUG);
			        header("HTTP/1.0 500 Internal Error");
			        echo $htmlOutput->getHtmlOutput();
			        exit();
	    		}
			    
		    } else {
		    	$log .= '<h3><style="red">No local site_module_config.ini file found</style></h3>'."\n";
		    }
		    
		    //run updates on resume if required
		    if(in_array('personal', $site_modules_a))
		    {
				$log .= '<h3>Updating Personal</h3><p>'."\n";
				$resume_common_ns = NS.MODULES.'\\personal\\models\\common\\common';
				$resume_common = new $resume_common_ns();
				$log .= $resume_common->deactivateAll();
				$log .= "</p>\n";
		    }
	
		    //Done 
		   	$log .= '<h2>- - - DONE - - -</h1>';
			echo $log;
			
		} else {
			die('WRONG');
		}
	
	} else {	
		die('WRONG!! Very wrong!');
	}
?>