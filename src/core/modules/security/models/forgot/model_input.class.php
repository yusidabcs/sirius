<?php
namespace core\modules\security\models\forgot;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 06 Nov 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'forgot';
	
	protected $catchaRequired = true;
	
	//my variables
	private $_allowLogin = false;
	
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}

    /**
     * Hadle forget password post request and sent email
     */
    protected function processPost()
	{
		if(isset($_POST['captcha']))
		{
			$captcha = trim($_POST['captcha']);

			if($captcha != $_SESSION['captcha_code'])
			{
				$this->addError('captcha',$this->system_register->site_term('SECURITY_LOGIN_CAPTCHA_ERROR'));
			}
			
		} else {
		 
			$recaptcha_key = $this->system_register->site_info('SITE_RECAPTCHA_KEY');
	
			if($recaptcha_key)
			{
				//recaptcha
				$reCAPTCHA_Forgot_Token = isset($_POST['reCAPTCHA_Forgot_Token']) ? $_POST['reCAPTCHA_Forgot_Token'] : '';
				
				$reCAPTCHA_score = '';
				
				if(!empty($reCAPTCHA_Forgot_Token))
				{
					$reCAPTCHA_ns = NS_APP_CLASSES.'\\recaptcha\\recaptcha';
					$reCAPTCHA = new $reCAPTCHA_ns($reCAPTCHA_Forgot_Token);
					
					if($reCAPTCHA->getSucess())
					{
						if($reCAPTCHA->getAction() == 'forgot')
						{
							$reCAPTCHA_score = $reCAPTCHA->getScore();
						
							/* 
								$this->errors['reCAPTCHA Score'] = $reCAPTCHA->getScore();
							*/
							
							if($reCAPTCHA_score <= 0.7)
							{
								$this->addError('reCAPTCHA','Sorry your Google reCAPTCHA Score was low. Please confirm with the secure image.');
							}
							
							if($reCAPTCHA_score <= 0.3)
							{
								$msg = "Google reCAPTACHA score too low!";
                                $this->addError('reCAPTCHA',$msg);
							}
							
						} else {
							$msg = "Google reCAPTACHA wrong action!";
                            $this->addError('reCAPTCHA',$msg);
						}
						
					} else {
						
						$this->addError('reCAPTCHA','Sorry Google reCAPTCHA error. Please confirm with the secure image.');
					}
				} else {
					$this->addError('reCAPTCHA','Sorry Google reCAPTCHA error. Please confirm with the secure image.');
				}
			}
		}
	    //check username is exist in request
		if(isset($_POST['username']))
		{
		    //check captcha
			if($this->catchaOK)
			{
				$to_name = $_POST['username'];
				
				//get email from username
				$user_db = new \core\modules\user\models\common\user_db;
				$to_email = $user_db->getEmailFromUsername($_POST['username']);
				
				if($to_email)
				{
					$from_name = $this->system_register->siteEmailName();
					$from_email = $this->system_register->siteEmailAddress();

					$security_base = new \core\modules\security\models\common\security_base;
					$security_base->sendPasswordRestEmail($to_name,$to_email,$from_name,$from_email,SITE_WWW,$this->modelURL);
				}
				
				//now we can go
				$this->redirect = '/';
			} 
		}
		return;
	}

}
?>