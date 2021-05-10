<?php
namespace iow\app\classes\module_base;

/**
 * Abstract module_model class.
 * 
 * @abstract
 * @package 	module_router
 *Ê@author		Martin O'DeeÊ<martin@iow.com.au>
 *Ê@copyright	Martin O'DeeÊ5 October 2014
 */
abstract class module_model {
	
	//abstract functions required in model class
	abstract protected function main();
	abstract protected function setViewVariables();

	//should be set by the actual model class
	protected $model_name = '';	//set by the MODULE!
	protected $processPost = false; //tells us if POST is even worth worrying about
	
	protected $link_id = ''; //my actual Link_id
	protected $myURL = ''; //is the url with all options
	protected $modelURL = ''; //is the url to the model only (link_id/model)
	protected $baseURL = ''; //is the url to the link_id only
	
	protected $isAdmin = false; //this tells the model if the person can see the admin
	
	//we should have access to the system register
	protected $system_register;
	protected $page_info;
	protected $menu_register;
	
	//model variables
	protected $view_variables_obj; //object within view that contains the variables
	protected $page_options; //the list of GET options
	protected $input_obj = false; //object for input variables
	protected $redirect = false; //if the model want to redirect it just has to set this
	protected $nextModel = false; //if the model want to execute another model it just has to set this

	public function __construct()
	{	
		//define the models directory if it was not defined by the previous model (in the next case).
		if(!defined ( 'DIR_MODULES_MODELS' ))
		{
			define('DIR_MODULES_MODELS',DIR_MODULE.'/models');
			define('NS_MODULES_MODELS',NS_MODULE.'\\models');
		}
		
		return;
	}
	
	public function runModel()
	{
		//check the input if POST is allowed
		if($this->processPost)
		{
			$this->_checkPost();
			//if redirect or next is set then return back now
			if($this->redirect || $this->nextModel)
			{
				return;
			}
	    } else {
		    //clear these if there is no post
		    unset($_SESSION['Md5_GenCode_CATCHA']);
		    unset($_SESSION['register_ajax']);
		    unset($_SESSION['email_check_count']);
	    }
		
		//we need page info for all models
		$this->page_info = \iow\app\classes\page_info\page_info::getInstance();
		
		//get our link_id
		$this->link_id = $this->page_info->getCurrentLinkId();
		
		//set up menu_regsiter for all models
		$this->menu_register = \iow\app\classes\menu_register\menu_register::getInstance();
		
		//we are going to need the system register
		$this->system_register = \iow\app\classes\system_register\system_register::getInstance();

		//set up to handle view Variables
		$this->_setViewVariablesObject();
		
		//see if there are other options
		$this->setPageOptions();
		
		//set the page title
		$this->setPageTitle();
		
		//set my url
		$this->setMyURL();
		
		//set my url
		$this->setModelURL();
		
		//set my url
		$this->setBaseURL();
		
		//set is Admin
		$this->_setIsAdmin();
		
		//run the main function
		$this->main();
		
		//set up the view variables
		$this->setViewVariables();
		
		//set the page heading in the browser
		$this->setHeadPageTitle();
		
		return;
	}
	
	/**
	 * setViewVariablesObject function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _setViewVariablesObject()
	{
		//we need to interact with view variables
		$this->view_variables_obj = \iow\app\classes\page_view\page_view_variables::getInstance();
		return;
	}
	
	protected function setPageOptions()
	{
		$this->page_options = $this->page_info->getOptions();
		//remove the first level as it is the model and not an option of the model
		array_shift($this->page_options);
		return;
	}
	
	protected function setPageTitle()
	{
		$pageTitle = $this->menu_register->getPageTitle($this->link_id);
		//set the page title
		$this->view_variables_obj->setPageTitle($pageTitle);
		return;
	}
	
	protected function setHeadPageTitle()
	{
		$siteTitle = $this->system_register->siteTitle();
			
		//set the page title in the heading of the browser window
		$this->view_variables_obj->setHeadPageTitle($siteTitle);
		return;
	}
	
	protected function setMyURL()
	{
		//we need to interact with view variables
		$this->myURL = '/'.$this->link_id.'/'.$this->model_name;
		
		foreach($this->page_options as $option)
		{
			$this->myURL .= '/'.$option;
		}
		
		return;
	}
	
	protected function setModelURL()
	{
		//we need to interact with view variables
		$this->modelURL = '/'.$this->link_id.'/'.$this->model_name;
		
		return;
	}
	
	protected function setBaseURL()
	{
		//we need to interact with view variables
		$this->baseURL = '/'.$this->link_id;
		return;
	}
	
	private function _setIsAdmin()
	{
		if( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] )
		{
			$this->isAdmin = true;
		} else {
			$this->isAdmin = false;
		}
		return;
	}

	
	/**
	 * _checkPost function.
	 * 
	 * It will checks if Post is not empty and runs the processPost function which is
	 * constituted in the actual module class.
	 *
	 * @access private
	 * @return void
	 */
	private function _checkPost()
	{
		if(!empty($_POST))
		{
			$theInputClass = NS_MODULES_MODELS.'\\'.$this->model_name.'\\model_input';
			$file = DIR_MODULES_MODELS.'/'.$this->model_name.'/model_input.class.php';
			if(!$this->input_obj = new $theInputClass())
			{
				$msg = "Information was posted but there is no $inputClassName file!";
				throw new \RuntimeException($msg);
			}

			//set redirect or next if the model input handle has done that
			$this->redirect = $this->input_obj->getRedirect();
			$this->nextModel = $this->input_obj->getNextModel();
		} else {
			//clear all the possible session variables that should no long exist
			unset($_SESSION['Md5_GenCode_CATCHA']);
		    unset($_SESSION['register_ajax']);
		    unset($_SESSION['email_check_count']);
		}
		return;
	}
	
	public function getRedirect()
	{
		return $this->redirect;
	}
	
	public function getNextModel()
	{
		return $this->nextModel;
	}

}
?>