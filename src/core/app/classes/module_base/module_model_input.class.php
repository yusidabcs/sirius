<?php
namespace core\app\classes\module_base;
use \core\app\classes\middleware\authmiddleware;
/**
 * Abstract module_model_input class.
 * 
 * @abstract
 * @package 	module_router
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 August 2019
 */
abstract class module_model_input {
	
	//abstract functions
	abstract protected function processPost();
	
	protected $system_register; //we should have access to the regsiter
		
	protected $input = array(); //an array of the inputed data
    protected $errors = array(); //an array of the errors
    protected $messages = array(); //an array of the errors

	//redirect or nextModel
	protected $redirect = false; //if the model want to redirect it just has to set this
	protected $nextModel = false; //if the model want to execute another model it just has to set this
	
	protected $inputClass = ''; //set by class
	protected $model_name = ''; //set by the class
	
	protected $myURL = ''; //the exact url that was being used
	protected $modelURL = ''; //the start of this model
	protected $baseURL = ''; //the base URL for this module
	protected $permissionKey = '';
	protected $page_options; //the list of GET options
	
	protected $isAdmin = false; //set by construct
	
	protected $sanitizeInput = true; //over-ride if you don't want this
	
	protected $orig_post = null; //the orginal post if it is sanitized
	
	protected $catchaRequired = false; //if set to true the system will redirect to $baseURL immediately if there is no catcha set.
	
	protected $catchaOK = false; //always presume it is bad until set otherwise

	protected $useEntity = false;
	
	protected $middleware;

	public function __construct()
	{	
		//we are going to need the system register
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$this->system_register = $system_register_ns::getInstance();
		
		$page_info_ns = NS_APP_CLASSES.'\\page_info\\page_info';
		$this->page_info = $page_info_ns::getInstance();
		
		$this->_setIsAdmin();
		$this->setModelURL();
		$this->setBaseURL();
		$this->_checkEntity();
		
		//see if there are other options
		$this->setPageOptions();
		
		$this->_setMiddleware();
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
		    //before Md5_GenCode_CATCHA, the session is never exist
			if(isset($_SESSION['captcha_code']))
			{
                $catcha = isset($_POST['captcha']) ? $_POST['captcha'] : 'It can not be blank';
				//$catcha = md5($catcha);
				if( $catcha != $_SESSION['captcha_code'] )
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
			//unset($_SESSION['captcha_code']);
			$this->catchaOK = true;
		}
		return;
	}
	
	protected function setModelURL()
	{
		//we need to interact with view variables
		$this->modelURL = '/'.$this->page_info->getLink().'/'.$this->model_name;
		
		return;
	}
	
	protected function setBaseURL()
	{
		//we need to interact with view variables
		$this->baseURL = '/'.$this->page_info->getLink();
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

    protected function addMessage($name,$message)
    {
        $this->messages[$name] = $message;
    }

    public function hasMessages()
    {
        if(empty($this->messages))
        {
            return false;
        }
        return true;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    private function _checkEntity(){
        if(isset($_SESSION['entity'])){
            $this->useEntity = true;
            $this->entity = $_SESSION['entity'];
        }

	}
	
	public function authorize($ability = '')
	{
		$this->middleware->can((empty($ability)) ? $this->permissionKey : $ability);
	}

	private function _setMiddleware()
	{
		$this->permissionKey = MODULE.'.'.$this->model_name.'.';

		if (isset($this->page_options[0])) {
			$this->permissionKey .= 'update';	
		} else {
			$this->permissionKey .= 'store';
		}

		$this->middleware = authmiddleware::getInstance();
	}
	
}
?>