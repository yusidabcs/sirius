<?php
namespace core\app\classes\page_info;

/**
 * Final page_info_obj class.
 * 
 * This is the actual page information object itself.  Changes to its behaviour go here
 *
 * @final
 * @package 	page_info
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
final class page_info_obj {

	private $_link = '';
	private $_options = array();
    private $_home = false;
    
    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
	    //process normally
	    $this->_processGET();
	    
	    //check if we need to override it
	    if( isset($_SESSION['system_security_redirect']) &&  $_SESSION['system_security_redirect'] == 1)
	    {
		    $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
			$menu_register = $menu_register_ns::getInstance();
	
			//ignore the redirect if this is the security module
		    if($this->_link != $menu_register->getModuleLink('security') )
		    {
		    	$this->_overridePageInfo();
		    }
	    }
	    
        return;
    }
    
    /**
     * _processGET function.
     * 
     * Most of the time the url will have $_GET set by the htaccess file
     *
     * 	RewriteRule ^([0-9a-zA-Z_-]+)$ /index.php?l=$1 [L]
     * 	RewriteRule ^([0-9a-zA-Z_-]+)/$ /index.php?l=$1 [L]
     * 	RewriteRule ^([0-9a-zA-Z_-]+)/(.*)$ /index.php?l=$1&o=$2 [L]
     *
     * This is the overall function to process the GET component if it is there
     *
     * url/first-path/second-path
     * first-path will be $_GET['l']
     * second-path will be $_GET['0']
     *
     * @access private
     * @return void
     */
    private function _processGET()
    {
    	// set the _link variable or drop the site immediately if SITE_DOWN in the site_config.ini
        // validate the page url, first path, $_GET['l']
    	$this->_processLink();
    	
    	//set the options
        //check second path, $_GET['0']
    	$this->_processOptions();
    	
    	return;
    }
    
    /**
     * _processLink function.
     * 
     * If the SITE_DOWN was set to 1 in the site_config.ini then this function
     * will also run the _takeDown function and the processing will end there.
     *
     * @access private
     * @return sting the link name if the $_GET is set or otherwise the default
     */
    private function _processLink()
    {	
    	//need some basic information from the system register
    	$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
    	$system_register = $system_register_ns::getInstance();
    	
    	$default_link = $system_register->site_info('LINK_DEFAULT');
    	$admin_link = $system_register->site_info('LINK_ADMIN');
    	$security_link = $system_register->site_info('LINK_SECURITY');
    	
    	//let's get the link and set it if it is defined
        // check if first path is exist
    	if(isset($_GET['l']))
    	{
    		$link = $_GET['l'];
    		
    		//simple test of link name just in case
	    	if($this->_strTestOK($link))
	    	{
		    	//admin and security override site down!
	    		if($link == $admin_link || $link == $security_link )
	    		{
		    		$this->_link = $link;
		    		
	    		} else {
	    			
	    			//site down wins for everything else
		    		if( $system_register->site_info('SITE_DOWN') )
		    		{
			    		$this->_takeDown();
		    		}		
		    		
		    		$this->_link = $link;
		    		
		    		if($link == $default_link)
		    		{
			    		//it is the home
			    		$this->_home = true;
		    		}
		    		
	    		}
	    		
	    	} else {
	    		//the url has a bad character in it htaccess should stop this anyway
		    	$msg = 'Bad URL supplied!';
				throw new \RuntimeException($msg);
				exit();
	    	}
	    	
    	}
    	//if the first path is not exits
        //the possibility are site is down or it is homepage
    	else {
    		
    		//check if the site is down for a start
	    	if($system_register->site_info('SITE_DOWN'))
    		{
	    		$this->_takeDown();
    		}
    		
    		//ok so it is the default link then
    		$this->_link = $default_link;
    		$this->_home = true;
    	}
	    	
    	return;
    }
    
    /**
     * _strTestOK function.
     * 
     * Used to sanitize the $_GET['l'].  It should not have a problem passing this test
     *
     * @access private
     * @param varchar $link
     * @return bool true if ok
     */
    private function _strTestOK($string)
	{
		if( preg_match('/[^A-Za-z0-9_\-]/', $string) )
		{
			$out = false;
		} else {
			$out = true;
		}
		return $out;
	}
    
    /**
     * _takeDown function.
     * 
     * A small function that calls the htmlpage class with 999 which
     * I have defined at the 'take down' page.  There is a default page
     * that can be over-written by a local variation if needs be.
     * 
     * @access private
     * @return void outputs the 'take down' page and ends all other processing
     */
    private function _takeDown()
    {
	    $html_page_ns = NS_HTML.'\\htmlpage';
	    $htmlpage = new $html_page_ns(999);
		exit();
    }
    
    /**
     * _processOptions function.
     * 
     * Processes all the rest of the url based on '/' into an options array
     *
     * @access private
     * @return array of options
     */
    private function _processOptions()
    {	
    	//check if it an option params is exist
    	if(isset($_GET['o']))
    	{
    		$options_array = explode('/',$_GET['o']);
    		//check each option
    		foreach($options_array as $key => $option)
    		{
    			//if it is empty pop it off
    			if(empty($option))
    			{
	    			unset($options_array[$key]);
    			}
    			
	    		if(!$this->_strTestOK($option))
	    		{
		    		//the url has a bad character in it htaccess should stop this anyway
			    	$msg = 'Bad string supplied in options!';
					throw new \RuntimeException($msg);
					exit();
	    		}
    		}
    		$this->_options = array_values($options_array);
    	} else {
	    	$this->_options = array();
    	}
    	return;
    }
    
    private function _overridePageInfo()
    {
	    //over-write only if the link is the same as the original
		if( $this->_link == $_SESSION['system_original_page_info_link'] )
		{	
	    	$this->_link = $_SESSION['system_original_page_info_link'];
	    	$this->_options = $_SESSION['system_original_page_info_options'];
	    	$this->_home = $_SESSION['system_original_page_info_home'];
	    	
	    	//delete any posts, files of get data
	    	unset($_POST);
	    	unset($_FILES);
	    	unset($_GET);
		}
	
		unset($_SESSION['system_security_redirect']);
		unset($_SESSION['system_security_point']);
		unset($_SESSION['system_security_reason']);
						
		unset($_SESSION['system_original_page_info_link']);
    	unset($_SESSION['system_original_page_info_options']);
    	unset($_SESSION['system_original_page_info_home']);
			
		return;
    }
    
	//!Public Functions
	
	public function getLink()
	{
		return $this->_link;
	}
	
	public function getOptions()
	{
		return $this->_options;
	}
	
	public function getHome()
	{
		return $this->_home;
	}
	
}
?>