<?php
namespace iow\app\classes\module_base;
 
/**
 * Abstract module_conroller class.
 * 
 * Is the base class for all module controller.
 *
 * @abstract
 * @package 	module_controller
 *�@author		Martin O'Dee�<martin@iow.com.au>
 *�@copyright	Martin O'Dee�5 October 2014
 */

abstract class module_controller {
	
	//set by the actual module controller class
	protected $commonNav = false;
	
	//set by this base controller
	protected $defaultModel;
	protected $model_a = array();
	protected $model;
	
	
	public function __construct()
	{
		//we need system regsiter
		$this->system_register = \iow\app\classes\system_register\system_register::getInstance();
		
		//now lets get processing
		$this->_processINI();
		$this->_processTermsINI();
		$this->setModelId(); //if option 0 is the model no need to overload in control
		$this->_confirmGeneralSecurity(); //check general module security in case menu is fucked up
		$this->_runCommonNav();
		$this->_runModel();
		$this->_runView();
		return;
	}
	
	private function _processINI()
	{
	    //load the ini file
	    if(!$module_ini_a = @parse_ini_file(DIR_MODULE.'/module.ini',true))
	    {
		    $msg = MODULE." module ini file it does not exists or is empty!\n";
		    throw new \RuntimeException($msg);
		}
	    
	    //make sure there is a default Model and set it
	    $this->defaultModel = $module_ini_a['config']['defaultModel'];
	    		    
	    //check the models exist
	    foreach($module_ini_a['models'] as $model => $value)
	    {
		    $this->model_a[$model] = $value;
	    }
	    unset($module_ini_a['models']);
	    
	    //check the default is one of the models
	    if(!array_key_exists($this->defaultModel, $this->model_a))
	    {
		    $msg = MODULE." ini files says the {$this->defaultModel} model is needed but it does not exists!\n";
		    throw new \RuntimeException($msg);
		}
		 
		//clean up a little room
		unset($module_ini_a);
		return;
	}
	
	private function _processTermsINI()
	{
		//load the model ini file
		if(is_file(DIR_SECURE_MODULES.'/'.MODULE.'/terms.ini'))
		{
			$term_ini_a = @parse_ini_file(DIR_SECURE_MODULES.'/'.MODULE.'/terms.ini',true);
		} else {
		    if( is_file(DIR_MODULE.'/terms.ini') )
		    {
			    $term_ini_a = @parse_ini_file(DIR_MODULE.'/terms.ini',true);
		    } else {
			    $msg = MODULE." module term ini file it does not exists or is empty!\n";
			    throw new \RuntimeException($msg);
			}
		}
	    
	    if(!empty($term_ini_a))
	    {
		    foreach($term_ini_a as $index => $term)
		    {
			   	$this->system_register->addSiteTerm($index,$term);
		    }
	    }
	    
		return;
	}
		
	//use the options given to dictate what model to use
	protected function setModelId() 
	{
		//check Options
		$page_info = \iow\app\classes\page_info\page_info::getInstance();
				
		if($page_info->optionCount() > 0)
		{
			$this->model = $page_info->getOption(1);
		} else {
			$this->model = $this->defaultModel;
		}
		
		//check the default is one of the models
	    if(!array_key_exists($this->model, $this->model_a))
	    {
		    $this->_send404();
		}
		return;
	}
	
	private function _confirmGeneralSecurity()
	{
		//if this->model is empty then we need to stop now
    	if( empty($this->model) )
    	{
	    	$msg = "{MODULE} was asked to run an empty model!\n";
		    throw new \RuntimeException($msg);
    	}
	    	
		//no need to redirect if this is the security module
		if( MODULE != 'security' )
		{
	    	//find the default security level
	    	$generalSecurity = $this->model_a[$this->model];
	    	
	    	if($generalSecurity == 'access')
	    	{
		    	$min_security_level = $this->system_register->getModuleSecurityLevel(MODULE,'security_access');
	    	} else {
		    	$min_security_level = $this->system_register->getModuleSecurityLevel(MODULE,'security_admin');
	    	}
	    	
	    	//check the security against the module
	    	if($min_security_level > 1)
	    	{
		    	if( isset($_SESSION['user_security_level']) )
		    	{
			    	//you are logged in but might not have enough security level
			    	if( $_SESSION['user_security_level'] < $min_security_level )
			    	{
				    	$msg = MODULE." general security level for model {$this->model} is preventing your access!\n";
						throw new \RuntimeException($msg);
			    	}
		    	} else { //you are not logged in
			    	
			    	if(MODULE == 'security') //obviously we can not redirect security to security
		    		{
			    		$msg = "Security module is stuck in a security options loop - check security options of Security to resolve!\n";
						throw new \RuntimeException($msg);
		    		}
			    	
			    	//get the correct security link to log in
	    			$_SESSION['system_security_redirect'] = 1;
	    			$_SESSION['system_security_point'] = 'module control';
					$_SESSION['system_security_reason'] = 'You do not have the security level needed to access the requested model';
					
					//handle the page information
					$page_info = \iow\app\classes\page_info\page_info::getInstance();
					$_SESSION['system_original_page_info_link'] = $page_info->getCurrentLinkId();
					
					$options_array = $page_info->getOptions();
					
					if(!empty($options_array))
					{
						foreach($options_array as $option)
						{
							$_SESSION['system_original_page_info_link'] .= '/'.$option;
						}
					}
						
					$_SESSION['system_original_page_info_options_array'] = $options_array;
									
					//we need the menu register multiple times
					$menu_register = \iow\app\classes\menu_register\menu_register::getInstance();
					$seclink_id = '/'.$menu_register->securityModuleLinkId().'/login';
					
					sleep(2);
					
		    		header("Location: $seclink_id");
					exit();
		    	}
	    	}
	    }
    	
		return;
	}
	
	private function _runCommonNav()
	{	
		if($this->commonNav)
		{
			//set up information
			$page_info = \iow\app\classes\page_info\page_info::getInstance();
			$linkId = $page_info->getCurrentLinkId() ;
			
			$system_register = \iow\app\classes\system_register\system_register::getInstance();
			
			$link = '/'.$linkId.'/'.$this->defaultModel;
			$title_term = 'ADMIN_SIDE_NAV_'.strtoupper($this->defaultModel);
			$title = $system_register->site_term($title_term);
			$a_class = $this->model == $this->defaultModel ? "active" : '';
			$nav_a[] = array('link' => $link, 'title' => $title, 'a_class' => $a_class);
			
			//order the array by key
			ksort($this->model_a);
			
			foreach($this->model_a as $key => $value)
			{
				
				if($key != $this->defaultModel)
				{
					$link = '/'.$linkId.'/'.$key;
					$title = 'ADMIN_SIDE_NAV_'.strtoupper($key);
					$title = $system_register->site_term($title);
					$a_class = $this->model == $key ? 'active' : '';
					
					$nav_a[] = array('link' => $link, 'title' => $title, 'a_class' => $a_class);
				}
			}
			
			//we need the view variables object class
			$view_variables_obj = \iow\app\classes\page_view\page_view_variables::getInstance();
			$view_variables_obj->addViewVariables('commonNavHeading',$system_register->site_term('ADMIN_SIDE_NAV_HEADING'));
			$view_variables_obj->addViewVariables('commonNavArray',$nav_a);
		}
		return;
	}
	
	/**
     * _send404 function.
     * 
     * @access private
     * @return void
     */
    private function _send404()
    {
    	$htmlpage = new \iow\app\classes\html\htmlpage(404);
		exit();
    }
    
    /**
     * runModel function.
     * 
     * @access private
     * @return void
     */
    private function _runModel()
    {
    	//set the model
    	$model_name = NS_MODULE.'\\models\\'.$this->model.'\\model';
    	 	  	
    	$model_obj = new $model_name();
    	
    	$model_obj->runModel();
    	
		//clear the model just in case it should not be needed again
		$this->model = '';
		
	    //if redirect is set then go there it over rides nextModel 
	    if($model_obj->getRedirect())
	    {
		    header("Location: {$model_obj->getRedirect()}");
			exit();
	    } elseif ( $model_obj->getNextModel() ) { 
		    
		    //if another model is set run it
		    $this->model = $model_obj->getNextModel();
		    $this->_runModel();
	    }
	    
	    return;
    }
    
    private function _runView()
    {
	    //define the model view directory
	    define('DIR_MODULE_VIEWS',DIR_MODULE.'/views');
	    define('NS_MODULE_VIEWS',NS_MODULE.'\\views');
	    $page_view = new \iow\app\classes\page_view\page_view();
	    $page_view->outputHTML5();
    }
    	
}
?>