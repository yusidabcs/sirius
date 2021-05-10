<?php
namespace core\app\classes\module_base;
use \core\app\classes\middleware\authmiddleware;

/**
 * Abstract  module_admin class.
 * 
 * Is the base class for all module menu delete options
 *
 * @abstract
 * @package 	module_setup
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 August 2019
 */

abstract class module_ajax {
	
	abstract public function run();
	
	protected $optionRequired = false; //we must have an option to work
	protected $page_options = array(); //the file options from the url (GET)
	protected $option = ''; //the first file option
		
	protected $errors = array(); //an array of the errors
	
	protected $system_register; //we should have access to the regsiter
	
	protected $captchaRequired = false; //normally we don't need the captcha
	protected $captchaOK = false; //normally captcha is false before processing
	protected $useEntity = false; //check if entity available
	protected $entity = false; //check if entity available
	
	public function __construct($fileOptions)
	{
        //need system register
        $system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
        $this->system_register = $system_register_ns::getInstance();

		//process the captcha
		$this->_processCAPTCHA();

		//load up the page_options (if any)
		$this->page_options = $fileOptions;
		
		//set the first option (most ajax only uses one option)
		$this->option = isset($fileOptions[0]) ? $fileOptions[0] : '';
		
		if($this->optionRequired)
		{
			$this->_checkOption();
		}

		
        $this->_processTermsINI();
		
		$this->_checkEntity();
		
		$this->_setMiddleware();

		if($this->useEntity){
			$this->_checkEntitySecurity();
		}
				
		return;
	}
		
	private function _processCAPTCHA()
	{
		if($this->captchaRequired)
		{
			if(isset($_POST['captcha']))
			{
				$captcha = trim($_POST['captcha']);
				if($captcha != $_SESSION['captcha_code'])
				{
		
					$this->addError('captcha',$this->system_register->site_term('SECURITY_LOGIN_CAPTCHA_ERROR'));
					$this->captchaOK = false;
				} else {
					$this->captchaOK = true;
				}
				
			} else {
				//To stop people submitting from an external point where the captcha is required
				// and also to stop idiots "reloading" forms and having them submit
				header("Location: /");
				exit();
			}
		} else {

			//unset($_SESSION['captcha_code']);
			$this->captchaOK = true;
		}
		return;
	}
		
	private function _checkOption()
	{
		if( empty($this->option) )
		{
			die('Hmmm .. that does not look right ... not an option in sight!');
		}
		return;
	}
	
	protected function addError($name,$error)
	{
		$this->errors[$name] = $error;
	}

	protected function response($data, $status = 200){
        header('Content-Type: application/json; charset=utf-8');
        header('HTTP/1.1 '.$status);
        return json_encode($data);
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

	private function _checkEntity(){

		if(isset($_SESSION['entity'])){
			$this->useEntity = true;
			$this->entity = $_SESSION['entity'];
		}

	}

	private function _checkEntitySecurity(){

	}

	public function authorizeAjax($filename = '')
	{
		if (empty($filename)) {
			echo $this->response([
				'message' => 'Ajax filename not provided',
				'status' => 'error'
			], 500);
			exit(0);
		}

		$this->permissionKey .= $filename . '.';
		
		if (!empty($this->option)) {

			if (is_numeric($this->option)) {
				$this->permissionKey .= 'index';
			} else {
				$this->permissionKey .= $this->option;
			}
		} else {
			$this->permissionKey .= 'index';
		}

		$authorize = $this->middleware->checkPermission($this->permissionKey);

		if (!$authorize) {
			echo $this->response([
				'message' => 'This action is forbidden!',
				'status' => 'error'
			], 403);
			exit(0);
		}
	}

	private function _setMiddleware()
	{
		$this->permissionKey = MODULE.'.ajax.';

		$this->middleware = authmiddleware::getInstance();
	}
}
?>