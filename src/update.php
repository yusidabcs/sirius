<?php
	
	//sanitize the GET and POST just to clean everything up
	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	if(empty($_GET['id']))
	{
		die('WRONG!! Very wrong!');
	}

	require_once 'main.php';
	
	if(is_file(DIR_SECURE_INI.'/site_config.ini'))
    {
    	$site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');     
    } elseif (is_file(DIR_IOW_APP_INI.'/site_config.ini')) {
    	$site_a = parse_ini_file(DIR_IOW_APP_INI.'/site_config.ini');     
    } else {
    	$msg = 'The INI file site_config can not be found anywhere!';
    	die($msg); 
    }
   
	$md5_id = md5($site_a['SALT'].SITE_WWW);
		
	//only if the id is correct will it do anything at all
	if( $_GET['id'] == $md5_id )
	{	
		$log = '<h4>Running Local Files Update</h4>'."\n";
		
		if( is_file('/export/script/gin/ginUpdateSite.sh') )
		{
			$command = 	"/usr/local/bin/sudo /export/script/gin/ginUpdateSite.sh ".SITE_WWW;
			
			$log .= "<pre>\n";
			
			exec($command,$log_array);
			
			foreach($log_array as $line)
			{
				$log .= $line."\n";
			}
			
			$log .= "</pre>\n";
			
		} else {
			
			$log .= '<p style="color:red">/export/script/gin/ginUpdateSite.sh was not readable!<p>';			
		}
		
		/**
		 * Update is a class that runs addition update code (which should be removed after updating)
		 */
		$log .= '<h4>Running Update File</h4>'."\n";
		$updates_ns = NS_UPDATES.'\\update';
		$updates = new $updates_ns();
		$updates->runUpdate();
		$log .= $updates->getLog();
		
		//sync the ini files
		$log .= '<h4>INI Update</h4><p>'."\n";
		$syncIni_ns = NS_APP_CLASSES.'\\ini\\sync_ini';
		$syncIni = new $syncIni_ns();
		$log .= $syncIni->sync_ini_files();
		$log .= "</p>\n";
		
		//get the modules
	    if(is_file(DIR_SECURE_INI.'/site_module_config.ini')) 
	    {
			$db_update_ns = NS_APP_CLASSES.'\\db_update\\db_update';
			$db_update = new $db_update_ns();
			
		    //update fileman
		    $log .= "<h4>Updating System Tables</h4>\n";
			$log .= "<p>\n";
		    $log .= $db_update->updateAPPTables();
		    $log .= '</p>';
		    
		    //update the specific tables for this site based on modules
	    	$site_modules_info_a = parse_ini_file(DIR_SECURE_INI.'/site_module_config.ini',true); 
	    	$site_modules_a = array_keys($site_modules_info_a);
	
	    	foreach($site_modules_a as $module)
	    	{
		    	//site_down, admin and admin don't have setup files
		    	if($module == 'site_down' || $module == 'admin') continue;
			    
			    $log .= "<p> === Checking Installed Module <strong style=\"color:blue;\">{$module}</strong> ===</p>\n";
			    
			    $setupClass = NS_MODULES.'\\'.$module.'\setup\setup';
				$setup = new $setupClass(); //automatically runs updateModuleTables();
				$log .= $setup->getLog();
	    	}

	    } else {
	    	$log .= '<p style="color: red;">No local site_module_config.ini file found</style><p>'."\n";
	    }

	    /**
	    *
	    * Removed as I don't think we need it
	    * - it will look to update when we run daily.php (all going well)
	    * 
	    
	    //rebuild sitemaps
		$log .= '<h4>Rebuild Sitemaps</h4><p>'."\n";
		$menu_common = \iow\modules\menu\models\common\common::getInstance();
		$log .= $menu_common->updateSitemapFiles();
		$log .= "</p>\n";
		
		//search submit
		if($site_a['SEARCH_SUBMIT'])
		{
			$menu_common_ns = NS.MODULES.'\\menu\\models\\common\\common';
			$menu_common = $menu_common_ns::getInstance();
			$log .= '<h4>Submitting Sitemaps</h4><p>'."\n";
			$log .= $menu_common->submitSitemap();
			$log .= "</p>\n";
		}
		
		//delete system register
		$log .= '<h4>Deleting System Register Cache</h4>'."\n";
		$log .= system('rm '.DIR_SECURE_CACHE.'/*',$retval);
		$log .= '<p>Delete Return Value: '.$retval."</p>\n";
		
		 - - REMOVE THIS SOME TIME 28 Jan 2019 - - 
		
		//rebuild sitemaps
		$log .= '<h4>Building Menu TREE</h4><p>'."\n";
		$menu_common = \iow\modules\menu\models\common\common::getInstance();
		$log .= $menu_common->move2MenuTree();
		$log .= "</p>\n";
		
		//rebuild sitemaps
		$log .= '<h4>Building Sitemap Status</h4><p>'."\n";
		$menu_db = \iow\modules\menu\models\common\db::getInstance();
		$log .= $menu_db->updateSitemapStatus();
		$log .= "</p>\n";
		
		*
		**/
		
		//delete all registers
		$log .= '<h4>Deleting All Register Caches</h4>'."\n";
		$log .= system('rm '.DIR_SECURE_CACHE.'/*',$retval);
		$log .= '<p>Delete Return Value: '.$retval."</p>\n";
		
		//rebuild robot
		$log .= '<h4>Updating Robot</h4><p>'."\n";
		$robots_ns = NS_APP_CLASSES.'\\robots\\robots';
		$robots = new $robots_ns();
		$log .= $robots->updateRobotFile();
		$log .= "</p>\n";
		
		echo $log;
		
	} else {
		die('WRONG');
	}
	
?>