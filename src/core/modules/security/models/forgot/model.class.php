<?php
namespace core\modules\security\models\forgot;

/**
 * Final model class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 06 Nov 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'forgot';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		//check if there is a user module and if not get out
		if(!$this->system_register->getModuleIsInstalled('user'))
		{
			$this->redirect = '/';
			return;
		}
		//option one is a onetime access to reset the password

        //url = /reset/reset_code
		$this->checksum = '';
		if(isset($this->page_options[0]))
		{
			$this->_processReset($this->page_options[0]);
		}
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		//Setup the template
		if($this->checksum)
		{
			$this->view_variables_obj->setViewTemplate('passchange');
		} else {
			$this->view_variables_obj->setViewTemplate('forgot');
		}
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//overwrite the page title
		$this->view_variables_obj->setPageTitle('Password Recovery');

		//Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);

		$this->view_variables_obj->addViewVariables('recaptcha',$this->system_register->site_info('SITE_RECAPTCHA_KEY'));
		
		if($this->checksum)
		{
			$this->view_variables_obj->addViewVariables('checksum',$this->checksum);
			$this->view_variables_obj->addViewVariables('user_id',$this->user_id);
			$this->view_variables_obj->addViewVariables('username',$this->username);
		}
		
		//set default values
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$errors = $this->input_obj->getErrors();

				if(array_key_exists('reCAPTCHA', $errors_array))
				{
					$this->view_variables_obj->addViewVariables('use_captcha',true);
				}

				$this->view_variables_obj->addViewVariables('errors', $errors);
			}
		}
		return;
	}
	
	//process a reset code like 5bc03a6c8eda944e5e41f7ee52d3d546
	private function _processReset($resetCode)
	{
		$security_db_ns = NS_MODULES.'\\security\\models\\common\\security_db';
		$security_db = new $security_db_ns();
		
		$this->checksum = $security_db->setCheckSum($resetCode);
		
		//email will be false if the checksum has expired
		$email = $security_db->getEmailFromResetCode($resetCode);
		
		if($email)
		{
			$user_db_ns = NS_MODULES.'\\user\\models\\common\\user_db';
			$user_db = new $user_db_ns();
		
			$userInfo = $user_db->getUserInfoFromEmail($email);
			
			$this->user_id = $userInfo['user_id'];
			$this->username = $userInfo['username'];
		} else {
			$this->checksum = false;
		}
		return;
	}
		
}
?>