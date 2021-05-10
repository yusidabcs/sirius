<?php
namespace core\app\classes\module_base;
 
/**
 * Abstract module_conroller class.
 * 
 * Is the base class for all module controller.
 *
 * @abstract
 * @package 	module_controller
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 August 2019
 */

abstract class module_controller {
	
	//set by the actual module controller class if used
	protected $commonNav = false;
	
	//set by this base controller
	protected $defaultModel; 			//the name of the default model
	protected $model_a = array();		//an array of the available models
	protected $model;
	
	public function __construct()
	{
		//we need system regsiter in multiple methods
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$this->system_register = $system_register_ns::getInstance();
		
		//we need page information in multiple methods
		$page_info_ns = NS_APP_CLASSES.'\\page_info\\page_info';
		$this->page_info = $page_info_ns::getInstance();
		$this->page_info_link = $this->page_info->getLink();
		$this->page_info_options = $this->page_info->getOptions();
		
		$middleware = NS_APP_CLASSES.'\\middleware\\authmiddleware';
		$this->auth_middleware = $middleware::getInstance();
		
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
		    
		//make sure there is a default model and set it
		if($this->defaultModel=='') {
			if(!empty($module_ini_a['config']['defaultModel']))
			{
				$this->defaultModel = $module_ini_a['config']['defaultModel'];
			} else {
				$msg = MODULE." module ini file does not contain a default model!\n";
				throw new \RuntimeException($msg);
			}
		}
	    //put all possible models into an array
	    if(!empty($module_ini_a['models']))
	    {
		    
		    foreach($module_ini_a['models'] as $model => $value)
		    {
			    $this->model_a[$model] = $value;
		    }
		    
		} else {
			$msg = MODULE." module ini file does not contain a list of models!\n";
		    throw new \RuntimeException($msg);
		}
	    	    
	    //check the default is one of the models
	    if(!array_key_exists($this->defaultModel, $this->model_a))
	    {
		    $msg = MODULE." ini files says the {$this->defaultModel} model is needed but it does not exists!\n";
		    throw new \RuntimeException($msg);
		}

		//put commonNavs models into an array
	    if(!empty($module_ini_a['commonNavs']))
	    {
		    
		    foreach($module_ini_a['commonNavs'] as $model => $value)
		    {
			    $this->commonNav_a[$model] = $value;
		    }
		    
		}
		//clean up a little room
		unset($module_ini_a);
		
		return;
	}
	
	private function _processTermsINI()
	{
		$local_terms_ini = DIR_SECURE_TERMS.'/'.MODULE.'_terms.ini';
		
		//If there is a local terms file then it supersedes the system default one for the module
		if(is_file($local_terms_ini))
		{
			$term_ini_a = @parse_ini_file($local_terms_ini,true);
		} else {
			
			//load the default terms for the module
			$module_terms_ini = DIR_MODULE.'/terms.ini';
			
		    if( is_file($module_terms_ini) )
		    {
			    $term_ini_a = @parse_ini_file($module_terms_ini,true);
		    } else {
			    $msg = MODULE." module term ini file it does not exists!\n";
			    throw new \RuntimeException($msg);
			}
		}
	    
	    //add the terms to the system register
	    if(!empty($term_ini_a))
	    {
		    foreach($term_ini_a as $index => $term)
		    {
			   	$this->system_register->addSiteTerm($index,$term);
		    }
	    }
	    
		return;
	}
		
	/**
	 * setModelId function.
	 * 
	 * Uses page info option 0 as the model request - can be overloaded to allow the use of other options
	 *
	 * @access protected
	 * @return void
	 */
	protected function setModelId() 
	{
		//check Options	
		if(empty($this->page_info_options))
		{
			$this->model = $this->defaultModel;
		} else {
			$this->model = $this->page_info_options[0];
		}
		//check the default is one of the models
	    if(!array_key_exists($this->model, $this->model_a))
	    {
		    $html_ns = NS_HTML.'\\htmlpage';
	    	$htmlpage = new $html_ns(404);
			exit();
		}
		return;
	}
	
	private function _confirmGeneralSecurity()
	{	
		//no need to redirect if this is the security module
		if( MODULE != 'security' )
		{
	    	
			$min_security_level = $this->system_register->getModuleSecurityLevel(MODULE,'security_access');
	    	//check the security against the module
	    	if($min_security_level > 1)
	    	{
		    	if( !isset($_SESSION['user_security_level']) )
		    	{
			    	//get the correct security link to log in
	    			$_SESSION['system_security_redirect'] = 1;
	    			$_SESSION['system_security_point'] = 'module control';
					$_SESSION['system_security_reason'] = 'You do not have the security level needed to access the requested model';
					
					//handle the page information
					$_SESSION['system_original_page_info_link'] = $this->page_info_link;		
					$_SESSION['system_original_page_info_options'] = $this->page_info_options;
					$_SESSION['system_original_page_info_home'] = $this->page_info->getHome();
									
					//need the security link
					$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
					$menu_register = $menu_register_ns::getInstance();
					
					//check if we have the right security access
					$security_link = $menu_register->getModuleLink('security');
					
		    		header("Location: /$security_link");
					exit();
			    	
		    	}
	    	}
	    }
	
		return;
	}
	
	
	/**
	 * _runCommonNav function.
	 * 
	 * Used when you want a common navigation in one module
	 *
	 * e.g. admin module
	 *
	 * @access private
	 * @return void
	 */
	private function _runCommonNav()
	{	
		if(isset($this->commonNav_a[$this->model]) && ($this->commonNav_a[$this->model] == 0 || $this->commonNav_a[$this->model] == '')){
			return;
		}
		if($this->commonNav)
		{
			//set up information for the navigation
			
			$link = '/'.$this->page_info_link.'/'.$this->defaultModel;	
			$title_term = 'ADMIN_SIDE_NAV_'.strtoupper($this->defaultModel);
			$title = $this->system_register->site_term($title_term);
			$a_class = $this->model == $this->defaultModel ? "active" : '';
			
			$nav_a[] = array('link' => $link, 'title' => $title, 'a_class' => $a_class);
			
			//order the array by key
			//ksort($this->model_a);
			foreach($this->model_a as $key => $value)
			{
				$security = false;
				//check security
				if ($this->auth_middleware->checkPermission(MODULE . '.' . $key . '.index') && $value === 'access') {
					$security = true;
				}

				$cmnNav = true;
				if(isset($this->commonNav_a[$key]) && ($this->commonNav_a[$key] == 0 || $this->commonNav_a[$key] == '')){
					$cmnNav = false;
				}
				if($key != $this->defaultModel && $cmnNav && $security)
				{
					$link = '/'.$this->page_info_link.'/'.$key;
					$title_term = 'ADMIN_SIDE_NAV_'.strtoupper($key);
					$title = $this->system_register->site_term($title_term);
					$a_class = $this->model == $key ? 'active' : '';
					
					//check side nav if set on terms.ini
					if(strpos($title,"TERM_NOT_SET")===FALSE) {
						$nav_a[] = array('link' => $link, 'title' => $title, 'a_class' => $a_class);
					}
				}
			}
			
			//we need the view variables object class
			$view_variables_obj_ns = NS_APP_CLASSES.'\\page_view\\page_view_variables';
			$view_variables_obj = $view_variables_obj_ns::getInstance();
			
			$view_variables_obj->addViewVariables('commonNavHeading',$this->system_register->site_term('ADMIN_SIDE_NAV_HEADING'));
			$view_variables_obj->addViewVariables('commonNavArray',$nav_a);
			
		}
		return;
	}
	    
    /**
     * runModel function.
     * 
     * This is the method that runs the actual model class
     *
     * @access private
     * @return void
     */
    private function _runModel()
    {
    	//set the model
    	$model_name = NS_MODULE.'\\models\\'.$this->model.'\\model'; 	
    	$model_obj = new $model_name();
    	
    	//this needs to be in every model so it will run
    	$model_obj->runModel();
    	
		//clear the model just in case it should not be needed again
		$this->model = '';
		
		/**
		*	Sometimes we run a model and we immediately want to redirect to another page and
		*	sometimes we want to run another model inside the same module.  The latter it does
		*	without reloading the page.  So all post, session data etc. is preserved
		**/
		
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
    
    
    /**
     * _runView function.
     * 
     * @access private
     * @return void
     */
    private function _runView()
    {
	    //define the model view directory
	    define('DIR_MODULE_VIEWS',DIR_MODULE.'/views');
	    define('NS_MODULE_VIEWS',NS_MODULE.'\\views');
	    
	    $page_view_ns = NS_APP_CLASSES.'\\page_view\\page_view';
	    
	    $page_view = new $page_view_ns();
	    $page_view->outputHTML5();
    }
    	
}
?>