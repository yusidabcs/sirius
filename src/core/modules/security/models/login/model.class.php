<?php
namespace core\modules\security\models\login;

/**
 * Final model class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'login';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		//Setup redirect variable for view
		if( isset($_SESSION['system_security_redirect']) && $_SESSION['system_security_redirect'] == 1)
		{
			$this->view_variables_obj->addViewVariables('redirect',true);
			$this->view_variables_obj->addViewVariables('redirectReason',$_SESSION['system_security_reason']);
			$this->view_variables_obj->addViewVariables('redirectPoint',$_SESSION['system_security_point']);
			$this->view_variables_obj->addViewVariables('redirectLink',$_SESSION['system_original_page_info_link']);
		} else {
			$this->view_variables_obj->addViewVariables('redirect',false);
		}

		$this->view_variables_obj->setViewTemplate('login');
		return;
	}
		
	//required function
	protected function setViewVariables()
	{	
		//security restricting login attempts
		if(isset($_SESSION['login_count']))
		{	
			if( $_SESSION['login_count'] > 3 )
			{
				$inactive = 600;
				$session_life = time() - $_SESSION['last_login'];
				if($session_life > $inactive){
					session_destroy();
					$_SESSION['login_count'] = 0;
					header("Location: /security");
				}else{
					header("Location: /security/blocked");
				}
				
			} 
			
		} else {
			//increment the session
			$_SESSION['login_count'] = 0;
		}
		
		//overwrite the page title
		$this->view_variables_obj->setPageTitle('Log In');
		
		//set links
		$this->view_variables_obj->addViewVariables('post',"/{$this->link_id}/login");
		$this->view_variables_obj->addViewVariables('forgot_url',"/{$this->link_id}/forgot");
		
		if($this->system_register->getModuleIsInstalled('register'))
		{
			$this->view_variables_obj->addViewVariables('register_use',true);
			$this->view_variables_obj->addViewVariables('register_url','/'.$this->menu_register->getModuleLink('register'));
		} else {
			$this->view_variables_obj->addViewVariables('register_use',false);
		}

		$this->view_variables_obj->addViewVariables('recaptcha',$this->system_register->site_info('SITE_RECAPTCHA_KEY'));
		
		if($_SESSION['login_count'] < 2)
		{
			$this->view_variables_obj->addViewVariables('use_captcha',false); //can be overwritten below to force the securimage to show
		} else {
			$this->view_variables_obj->addViewVariables('use_captcha',true);
		}
				
		//set default values
		if($this->input_obj && $this->input_obj->hasErrors())
		{
			$errors_array = $this->input_obj->getErrors();
			
			if(array_key_exists('reCAPTCHA', $errors_array))
			{
				$this->view_variables_obj->addViewVariables('use_captcha',true);
			}
			
			//we ran through the input and we have output
			$this->view_variables_obj->addViewVariables('errors',$errors_array);
			$this->view_variables_obj->addViewVariables('username',$this->input_obj->getInput('username'));
			$this->view_variables_obj->addViewVariables('password',$this->input_obj->getInput('password'));	
		} else {
			$this->view_variables_obj->addViewVariables('username','');
			$this->view_variables_obj->addViewVariables('password','');
		}
		return;
	}
		
}
?>