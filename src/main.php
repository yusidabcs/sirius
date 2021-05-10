<?php
	define('APP_VERSION','20210427.01');
	/**
	* This is the main settings and error management file it is essentially the first thing that is run! 
	*/

	/**
	* Cover off on the GET and POST filtering right at the start
	**/
	
	//This is a 'special' one for sending WYSIWYG text fields
	if(isset($_POST['section_text']))
	{
		define('SECTION_TEXT',$_POST['section_text']);
	}
    if(isset($_POST['page_text']))
    {
        define('PAGE_TEXT',$_POST['page_text']);
    }
	
	if(isset($_POST['submitted_text']))
	{
		define('SUBMITTED_TEXT',$_POST['submitted_text']);
	}

    if(isset($_POST['min_requirement']))
    {
        define('MIN_REQUIREMENT',$_POST['min_requirement']);
    }
	
	//sanitize the GET and POST just to clean everything up
	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	
	/**
	* All the required definitions for the system
	**/
	
	//define all the amin things
	define('DIR_BASE',dirname(__FILE__)); //base site directory
	
	set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/');
	
	//core directories and name spaces
	
	//app
	define('DIR_APP',DIR_BASE.'/core/app');
	define('DIR_APP_CLASSES',DIR_BASE.'/core/app/classes');
	define('DIR_APP_DRIVERS',DIR_BASE.'/core/app/drivers');
	define('DIR_APP_INI',DIR_BASE.'/core/app/ini');
	define('DIR_APP_SQLXML',DIR_BASE.'/core/app/sqlxml');
	define('DIR_APP_UPDATES',DIR_BASE.'/core/app/updates');
	
	define('NS_APP_CLASSES','\\core\\app\\classes');
	define('NS_APP_DRIVERS','\\core\\app\\drivers');
	define('NS_HTML','\\core\\app\\classes\\html');
	define('NS_UPDATES','\\core\\app\\updates');
	
	//modules
	define('DIR_MODULES',DIR_BASE.'/core/modules');
	define('WWW_MODULES','/core/modules');
	define('NS_MODULES','\\core\\modules');
	
	//page_view
	define('DIR_PAGEVIEWS',DIR_BASE.'/core/view_template');
	define('WWW_PAGEVIEWS','/core/view_template');
	define('NS_PAGEVIEWS','\\core\\view_template');
	
	//library
	define('DIR_LIB',DIR_BASE.'/lib');
	
	//local directories and names spaces
	define('DIR_LOCAL',DIR_BASE.'/local');
	define('DIR_LOCAL_HTML_ERROR',DIR_BASE.'/local/html-error-page');
	define('DIR_LOCAL_UPLOADS',DIR_BASE.'/local/uploads');
	
	//secure directory and name
	define('DIR_SECURE',DIR_BASE.'/secure');
	define('DIR_SECURE_CACHE',DIR_BASE.'/secure/cache');
	define('DIR_SECURE_INI',DIR_BASE.'/secure/ini');
	define('DIR_SECURE_TERMS',DIR_BASE.'/secure/terms');
	define('DIR_SECURE_FILES',DIR_BASE.'/secure/files');	//used to put files for processing ... 298 of page_view.class.php

	//define site web information
	define('SITE_WWW',$_SERVER['SERVER_NAME']);		//more 'secure' because it is from our config but will only use name not alias
	
	//define my specific request informtion
	define('MY_HOST',$_SERVER['HTTP_HOST']);		//the exact host (including alias or ip) that the client used to request
	
	//need to know if we are http or https
	if(isset($_SERVER['HTTPS']))
	{
		define('HTTP_TYPE',"https://");
	} else {
		define('HTTP_TYPE',"http://");
	}
	
	//File Manager Local Storage Directory
	define('FILE_MANAGER_STORAGE_LOCAL', DIR_LOCAL_UPLOADS.'/file_manager');
	
	/**
	* Define system information from the webroot/secure/system_config.ini
	* this file holds major system switches for this site
	*
	*	DEBUG = "1"
	*	LOCAL_INCLUDES = "0"
	*	PAGINATION_NUMBER = "30"
	*	SYSADMIN_NAME = "IOW System Administrator"
	*	SYSADMIN_EMAIL = "sysadmin@iow.com.au"
	*	SYSADMIN_BCC_NEW_USERS = "1"
	*	WEBSERVER_EMAIL = "web.server@iow.com.au"
	*
	*	The indexes become defined names and are given the values stated
	*/
	
	//load the ini file
    if(is_file(DIR_SECURE_INI.'/system_config.ini'))
    {
    	$system_ini_a = parse_ini_file(DIR_SECURE_INI.'/system_config.ini');     
    } elseif (is_file(DIR_APP_INI.'/system_config.ini')) {
    	$system_ini_a = parse_ini_file(DIR_APP_INI.'/system_config.ini');     
    } else {
    	die('WOW is this really screwed up. No System Config File at all!');
    }
    
    if(!empty($system_ini_a))
    {
	    //define all system variables globally
	    foreach($system_ini_a as $key => $value)
	    {
	    	define($key,$value);
	    }
	        
	    unset($system_ini_a);
	    
    } else {
    	die('WOW is this really screwed up. The System Config File is empty!');
    }
    
    /**
	* Define the module defaults if the local ini exists
	*
	* e.g.
	*
	*	ADDRESS_BOOK_ADDRESS_DETAILS = 2
	*	ADDRESS_BOOK_ADDRESS_DETAILS_FIRST_ENTRY = "PHY"
	*	ADDRESS_BOOK_ADDRESS_TYPE = "ent"
	*	ADDRESS_BOOK_MAIN_REQUIRE_EMAIL = 0
	*	ADDRESS_BOOK_CONTACT_ALLOWED = 1
	*	ADDRESS_BOOK_ADD_NEW_USER = 1
	*	ADDRESS_BOOK_SEND_USER_EMAIL = 1
	*	ADDRESS_BOOK_DEFAULT_COUNTRY_CODE = "AU"
	*	ADDRESS_BOOK_ADDRESS_DOB_MIN_AGE = 18
	*	ADDRESS_BOOK_ADDRESS_DOB_MAX_AGE = 80
	*	ADDRESS_BOOK_OUTPUT_PER_NAME = "FFCC"
	*	ADDRESS_BOOK_OUTPUT_ENT_NAME = "ENO"
	*	ADDRESS_BOOK_DEFAULT_POTS_TYPE = "mobile"
	*	ADDRESS_BOOK_DEFAULT_INTERNET_TYPE = "skype"
	*	ADDRESS_BOOK_ALLOW_AVATAR = 1
	*	PAGES_REGISTER_USE = 1
	*	PAGES_REGISTER_URL = "/register"
	**/

	if(is_file(DIR_SECURE_INI.'/site_module_local_defaults.ini'))
    {
	    $local_defaults = array();
	    
    	$local_defaults = parse_ini_file(DIR_SECURE_INI.'/site_module_local_defaults.ini',true);
    	
    	//define 
	    foreach($local_defaults as $key => $definition)
	    {
		    define($key, $definition);
	    }
    }

	/**
	* Handle all errors - if DEBUG to screen else email it
	**/

    //catch the fatal errors from now on
	function fatal_handler() 
	{
		$error = error_get_last();
		if(!empty($error)) //throw to screen
		{
			$errfile = "unknown file";
			$errstr  = "shutdown";
			$errline = 0;
				
			//gathering information
			$errno   = E_CORE_ERROR;
			$trace = print_r( debug_backtrace( false ), true );
			$url = $_SERVER['REQUEST_URI'];
			$get = print_r($_GET,true);
			$post = print_r($_POST,true);
			
			//setting variables from intormation
			$errno   = $error["type"];
			$errfile = $error["file"];
			$errline = $error["line"];
			$errstr  = $error["message"];
				
			if(DEBUG) //throw to screen
			{
				echo "<pre>";
				echo "<strong>URL</strong>\n";
				echo $url."\n";
				echo "<strong>GET</strong>\n";
				echo $get;
				echo "<strong>POST</strong>\n";
				echo $post;
				echo "<strong>ERROR</strong>\n";
				print_r($error);
				echo "<strong>TRACE</strong>\n";
				print_r($trace);
				echo "</pre>";

			} else {
				$message = format_error( $errno, $errstr, $errfile, $errline, $get, $url, $trace, $post );
				
				$to      = SYSADMIN_EMAIL;
				$subject = 'SYSTEM ERROR - '.SITE_WWW;
				
				$headers  = 'From: '.SYSADMIN_EMAIL."\r\n";
				$headers .= 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				
				$server_email = '-f '.WEBSERVER_EMAIL;

				mail($to, $subject, $message, $headers, $server_email);
			}
		}
		exit();
	}
	
	function format_error( $errno, $errstr, $errfile, $errline, $get, $url, $trace, $post ) 
	{	
		$content  = '<html><head><title>Error In Site></title></head><body>';
		$content .= "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
		$content .= "<tr valign='top'><td><b>URL</b></td><td><pre>$url</pre></td></tr>";
		$content .= "<tr valign='top'><td><b>GET</b></td><td><pre>$get</pre></td></tr>";
		$content .= "<tr valign='top'><td><b>POST</b></td><td><pre>$post</pre></td></tr>";
		$content .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
		$content .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
		$content .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
		$content .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
		$content .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
		$content .= '</tbody></table>';
		$content .= '</body></html>';
		
		return $content;
	}
	
	register_shutdown_function( "fatal_handler" );
    

	/**
	* Setup the autoloader because we are using namespace we will use spl_autoload
	* If you call a class eg. \iow\app\classes\system_register\system_register::getInstance();
	* then it will look for the system_register.class.php file in webroot/core/app/classes/system_register
	*/
	spl_autoload_extensions(spl_autoload_extensions().",.class.php");
	spl_autoload_register();

?>