<?php
namespace core\modules\security\ajax;

/**
 * Final pass class.
 * 
 * An ajax extension that allows ADMIN users to change passwords of
 * any other user.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 06 Nov 2019
 */
final class resetpass extends \core\app\classes\module_base\module_ajax {
	
	
	public function run()
	{
		//let's fix up the captcha first
		$recaptcha_key = $this->system_register->site_info('SITE_RECAPTCHA_KEY');
	
		if($recaptcha_key)
		{
			//recaptcha
			$reCAPTCHA_Forgot_Token = isset($_POST['reCaptcha']) ? $_POST['reCaptcha'] : '';
			
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
						
						if($reCAPTCHA_score <= 0.7)
						{
							$out['show'] = 'Sorry your Google reCAPTCHA Score was low. Please try again.';
							return json_encode($out);
						}
						
						if($reCAPTCHA_score <= 0.3)
						{
							$out['show'] = "Google reCAPTACHA score too low!";
							return json_encode($out);
						}

						$this->captchaOK = true;
						
					} else {
						$out['show'] = "Google reCAPTACHA wrong action!";
						return json_encode($out);
					}
					
				} else {
					
					$out['show'] = 'Sorry Google reCAPTCHA error. Please try again 2.';
					return json_encode($out);
				}
			} else {
				$out['show'] = 'Sorry Google reCAPTCHA error. Please try again.';
				return json_encode($out);
			}
		}

		if( isset($_POST['user']) )
		{
			//check captcha first
			if($this->captchaOK)
			{
				$user_db_ns = NS_MODULES.'\\user\\models\\common\\user_db';
				$user_db = new $user_db_ns();

				$to_email = false; //default to fail

                //get the actual email if not already given
                if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL))
                {               
                    if($user_db->checkEmailInUse($_POST['user']))
                    {
                        $to_name = '';
                        $to_email = $_POST['user'];
                    }
                } else {
                    //get email for the id with user db
                    $to_name = $_POST['user'];
                    $to_email = $user_db->getEmailFromUsername($_POST['user']);                 
				}
					
				if($to_email)
				{
					//from email
                    $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
                    $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
					
					$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
					$menu_register = $menu_register_ns::getInstance();
					
					$security_link_id = $menu_register->getModuleLink('security');
					
					$security_base = new \core\modules\security\models\common\security_base;
					$security_base->sendPasswordRestEmail($to_name,$to_email,$from_name,$from_email,SITE_WWW,$security_link_id.'/forgot');
					
					//ok so it did not pass muster
					$out['show'] = 'confirm_sent';
					$out['email'] = $to_email;
				} else {
					//ok so it did not pass muster
					$out['show'] = 'confirm_failed';
					$out['info'] = $to_email;
				}	
			} else {
				//ok so it did not pass muster
				$out['show'] = 'captcha_failed';
			}
			return json_encode($out);
		} else {
			$out = 'Well, we seem to have a Gremlin!';
		}
		
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}
	}
}
?>