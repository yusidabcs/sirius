<?php
namespace core\modules\register\ajax;

/**
 * Final default class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 29 January 2017
 */
final class home extends \core\app\classes\module_base\module_ajax {

	protected $captchaRequired;
	
	public function __construct($fileOptions)
	{
		parent::__construct($fileOptions);
		return;	
	}
	
	public function run()
	{	
		

		//security check to make sure it is our form doing the testing
		if(empty($_POST['register_ajax']) || empty($_SESSION['register_ajax']) || $_POST['register_ajax'] != $_SESSION['register_ajax']) return;
		
		//email check
		if($this->option == 'emailCheck')
		{
			$recaptcha_key = $this->system_register->site_info('SITE_RECAPTCHA_KEY');
			$captcha_success = false;

			if($recaptcha_key)
			{
				//recaptcha
				$reCAPTCHA_Register_Token = isset($_POST['captcha']) ? $_POST['captcha'] : '';
				
				$reCAPTCHA_score = '';
				
				if(!empty($reCAPTCHA_Register_Token))
				{
					$reCAPTCHA_ns = NS_APP_CLASSES.'\\recaptcha\\recaptcha';
					$reCAPTCHA = new $reCAPTCHA_ns($reCAPTCHA_Register_Token);
					
					if($reCAPTCHA->getSucess())
					{
						if($reCAPTCHA->getAction() == 'emailCheck')
						{
							$reCAPTCHA_score = $reCAPTCHA->getScore();
						
							/* 
								$this->errors['reCAPTCHA Score'] = $reCAPTCHA->getScore();
							*/
							
							if($reCAPTCHA_score <= 0.7)
							{
								$out['level'] = 'error';
								$out['heading'] = 'Recaptcha';
								$out['message'] = 'Sorry your Google reCAPTCHA Score was low. Please confirm with the secure image.';
							} else {

								$captcha_success = true;
							}

							
						} else {
							$out['level'] = 'error';
							$out['heading'] = 'Recaptcha';
							$out['message'] = 'Google reCAPTACHA wrong action!';
						}
						
					} else {
						$out['level'] = 'error';
						$out['heading'] = 'Recaptcha';
						$out['message'] = 'Sorry Google reCAPTCHA error. Please confirm with the secure image.';
					}
				} else {
					$out['level'] = 'error';
					$out['heading'] = 'Recaptcha';
					$out['message'] = 'Sorry Google reCAPTCHA error. Please confirm with the secure image.';
				}

			if ($captcha_success) {
				if($_SESSION['email_check_count'] == 3)
				{
					$out['level'] = 'warning';
					$out['heading'] = 'Too Many Attempts';
					$out['message'] = 'You have changed your email too often - reload the page!';
					
				} else {
					
					//limit the number of times they can check their email address
					$_SESSION['email_check_count']++;
					if (!is_file(DIR_SECURE_INI.'/site_config.ini')) {
						$out['level'] = 'error';
						$out['message'] = 'Cannot find site_config.ini file';
						$out['heading'] = 'Config file error';
	
						header('Content-Type: application/json; charset=utf-8');
						return json_encode($out);
						
					}
	
					$site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');
					
					
					if(filter_var($_POST['main_email'], FILTER_VALIDATE_EMAIL))
					{
							if(isset($site_a['VALIDATE_EMAIL_MX']) && $site_a['VALIDATE_EMAIL_MX'] == 1) {
							//ok now do an mx check on the domain
								list($name,$domain) = explode('@',$_POST['main_email']);
								if(!checkdnsrr($domain, 'MX'))
								{
									$out['level'] = 'error';
									$out['heading'] = 'Bad Email Address';
									$out['message'] = 'The email address you entered has no valid MX!';
								}
							} else {
	
								$user_db_ns = NS_MODULES.'\user\models\common\user_db';
								$user_db = new $user_db_ns;
								$user_id = $user_db->checkEmailInUse($_POST['main_email']);
								
								//check if it is already a user
								if($user_id)
								{
									$out['level'] = 'error';
									$out['heading'] = 'Email is in use!';
									$out['message'] = 'The email address you entered is already in our system!  If you have forgotten your password then use the reset option on the login page.';
								} else {
									$out['level'] = 'success';
									$out['heading'] = 'Email is OK';
									$out['message'] = 'The email address you entered seems good!';
								}
							}
	
						} else {
							$out['level'] = 'error';
							$out['heading'] = 'Bad Email Address';
							$out['message'] = 'The email address you entered is not validating!';
						}
					}
			}
				
		
		}

			if($this->option == 'userNameCheck')
			{
				$family_name = $_POST['family_name'];
				$given_name = $_POST['given_name'];
				$middle_names = $_POST['middle_names'];
				$dob = $_POST['dob'];
				$sex = $_POST['sex'];
				$main_email = $_POST['main_email'];
				
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$out = $address_book_common->checkMainData('per',$family_name,$given_name,$middle_names,$dob,$sex,0,$main_email);
				
			}else if ($this->option == 'getPartnerListByCountry'){

				$country_code = $_POST['country_code'];
				$register_db = new \core\modules\register\models\common\register_db;
				$out = $register_db->getPartnerListByCountry($country_code);
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
	
}
?>