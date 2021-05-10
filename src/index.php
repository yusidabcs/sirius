<?php
	header("X-FRAME-OPTIONS: SAMEORIGIN");
	/*
	* main.php is first included because it needed to define mostly all the global variable
	*/
	require_once 'main.php';
	
	require_once('vendor/autoload.php');
	
	/**
	* Session needs to start here because the menu register and then page_process needs to know if we are logged in or not.
	*/
	session_start();
	try 
    {
		/**
		 * system_register
		 * 
		 * Register all the system INI information
		 *
		 * (default value: \core\app\classes\system_register\system_register::getInstance())
		 * 
		 * @var mixed
		 * @access public
		 */
		
		/**
		 * menu_register
		 * 
		 * (default value: \core\app\classes\menu_register\menu_register::getInstance())
		 * 
		 * @var mixed
		 * @access public
		 */
		$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
		$menu_register = $menu_register_ns::getInstance();
		
		/**
		 * page_info
		 * 
		 * Processing the $_GET and setting page information and if 'site down' it will return the site down page
		 * this must be done before menu is run because the menu needs to know what webpage it is on.
		 *
		 * (default value: \core\app\classes\page_info\page_info::getInstance())
		 * 
		 * @var mixed
		 * @access public
		 */
		

		/*
		* Page info class, the responsible to show what module is gonna loaded base on url 
		* path.
		* for example, you have domain/test-page, it will load test-page module, if there no
		* module stated, it will loaded default module that stated in site_config.ini
		*/
		$page_info_ns = NS_APP_CLASSES.'\\page_info\\page_info';
		$page_info = $page_info_ns::getInstance();
		
		//!SECURITY CHECKS NOW as we have MENU and INFO
		
		$link = $page_info->getLink();
		
	
		//if the link is open to all then don't do anything more
		//if the link not open to all, continue for security checking
		
		//now run the module	
    	$module_id = $menu_register->getModuleId($link);
    	    
    	//once here the module never changes (the model can)
    	define('DIR_MODULE',DIR_MODULES.'/'.$module_id);
		define('NS_MODULE',NS_MODULES.'\\'.$module_id);
		define('MODULE', $module_id); 
		

		/*
		runs the controller for the module which takes it from here!
		 */
		$cntrl = NS_MODULE.'\\controller';
		new $cntrl(); 
		
    } catch (Exception $e) {
        //process the error
        $html_message = NS_HTML.'\\htmlmsg';
        $htmlOutput = new $html_message($e,DEBUG);
        header("HTTP/1.0 500 Internal Error");
        echo $htmlOutput->getHtmlOutput();
        exit();
    }
    
	exit(); //and that was the end of that!	
?>