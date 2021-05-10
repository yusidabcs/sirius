<?php
namespace iow\app\classes\module_base;

/**
 * Abstract module_model_input class.
 * 
 * @abstract
 * @package 	module_router
 *�@author		Martin O'Dee�<martin@iow.com.au>
 *�@copyright	Martin O'Dee�11 October 2014
 */
abstract class module_model_input {
	
	//abstract functions
	abstract protected function processPost();
	
	protected $system_register; //we should have access to the regsiter
		
	protected $input = array(); //an array of the inputed data
	protected $errors = array(); //an array of the errors
	
	//redirect or nextModel
	protected $redirect = false; //if the model want to redirect it just has to set this
	protected $nextModel = false; //if the model want to execute another model it just has to set this
	
	protected $inputClass = ''; //set by class
	protected $model_name = ''; //set by the class
	
	protected $myURL = ''; //the exact url that was being used
	protected $modelURL = ''; //the start of this model
	protected $baseURL = ''; //the base URL for this module
	protected $page_options; //the list of GET options
	
	protected $isAdmin = false; //set by construct
	
	protected $sanitizeInput = true; //over-ride if you don't want this
	
	protected $orig_post = null; //the orginal post if it is sanitized
	
	protected $catchaRequired = false; //if set to true the system will redirect to $baseURL immediately if there is no catcha set.
	
	protected $catchaOK = false; //always presume it is bad until set otherwise

	public function __construct()
	{	
		if($this->sanitizeInput)
		{
			$this->orig_post = $_POST;
			
			//sanitize the GET and POST just to clean everything up
			$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
			$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		}
	
		//we are going to need the system register
		$this->system_register = \iow\app\classes\system_register\system_register::getInstance();
		$this->page_info = \iow\app\classes\page_info\page_info::getInstance();
		
		$this->_setIsAdmin();
		$this->setModelURL();
		$this->setBaseURL();
		
		//see if there are other options
		$this->setPageOptions();
		
		//double check that Post is empty
		if(empty($_POST))
		{
			$msg = "The {$this->inputClass} was called but POST was empty!";
			throw new \RuntimeException($msg);
		} else {
			//check catacha
			$catchaOK = $this->_processCATCHA();
			//process the post
			$this->processPost();
		}
		
		if(!empty($_GET))
		
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
	
	private function _processCATCHA()
	{		
		if($this->catchaRequired)
		{
			if(isset($_SESSION['Md5_GenCode_CATCHA']))
			{
				$submitedCatcha = isset($_POST['catchaAnswer']) ? $_POST['catchaAnswer'] : 'It can not be blank';
			
				$catcha = md5($submitedCatcha);
				
				if( $catcha != $_SESSION['Md5_GenCode_CATCHA'] )
				{
					$this->addError($this->system_register->site_term('CATCHA_ERROR_TITLE'),$this->system_register->site_term('CATCHA_ERROR_ERROR'));
					$this->catchaOK = false;
				} else {
					$this->catchaOK = true;
				}

			} else {
				//To stop people submitting from an external point where the catcha is required
				// and also to stop idiots "reloading" forms and having them submit
				header("Location: $this->baseURL");
				exit();
			}
		} else {
			unset($_SESSION['Md5_GenCode_CATCHA']);
			$this->catchaOK = true;
		}
		
		return;
	}
	
	protected function setModelURL()
	{
		//we need to interact with view variables
		$this->modelURL = '/'.$this->page_info->getCurrentLinkId().'/'.$this->model_name;
		
		return;
	}
	
	protected function setBaseURL()
	{
		//we need to interact with view variables
		$this->baseURL = '/'.$this->page_info->getCurrentLinkId();
		return;
	}
	
	protected function setPageOptions()
	{
		$this->page_options = $this->page_info->getOptions();
		//remove the first level as it is the model and not an option of the model
		array_shift($this->page_options);
		return;
	}
	
	protected function addInput($name,$values)
	{
		$this->inputs[$name] = $values;
	}
	
	public function hasInputs()
	{
		if(empty($this->inputs))
		{
			return false;
		}
		return true;
	}
	
	public function getInput($name)
	{
		if(isset($this->inputs[$name]))
		{
			return $this->inputs[$name];
		}
		return;
	}
	
	public function getInputs()
	{
		if(isset($this->inputs))
		{
			return $this->inputs;
		}
		return;
	}
	
	protected function addError($name,$error)
	{
		$this->errors[$name] = $error;
	}
	
	public function hasErrors()
	{
		if(empty($this->errors))
		{
			return false;
		}
		return true;
	}
	
	public function getErrors()
	{
		return $this->errors;
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