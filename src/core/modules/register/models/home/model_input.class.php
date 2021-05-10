<?php
namespace core\modules\register\models\home;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'home';
	protected $catchaRequired;
	
	//my variables
	protected $redirect;
	protected $nextModel;

	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		$this->register_db = new \core\modules\register\models\common\register_db;

		$recaptcha_key = $this->system_register->site_info('SITE_RECAPTCHA_KEY');

		if(isset($_POST['captcha']))
		{
			$captcha = trim($_POST['captcha']);

			if($captcha != $_SESSION['captcha_code'])
			{
				$this->addError('captcha',$this->system_register->site_term('SECURITY_LOGIN_CAPTCHA_ERROR'));
			}
			
		} else {

			if($recaptcha_key)
			{
				//recaptcha
				$reCAPTCHA_Register_Token = isset($_POST['reCAPTCHA_Register_Token']) ? $_POST['reCAPTCHA_Register_Token'] : '';
				$reCAPTCHA_score = '';
				
				if(!empty($reCAPTCHA_Register_Token))
				{
					$reCAPTCHA_ns = NS_APP_CLASSES.'\\recaptcha\\recaptcha';
					$reCAPTCHA = new $reCAPTCHA_ns($reCAPTCHA_Register_Token);
					
					if($reCAPTCHA->getSucess())
					{
						if($reCAPTCHA->getAction() == 'register')
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
	
		/*
			[country] => AG
			[main_email] => harry@smith.com
		    [title] => Mr
		    [family_name] => Smith
		    [given_name] => Harry
		    [middle_names] => Jerry
		    [dob] => 2000-09-13
		    [sex] => male
		    [accurate] => 1
		    [english] => 1
		    [register] => 1
		    [catchaAnswer] => 594ca7
		*/
		
		$country = $_POST['country'];
		$main_email = strtolower(trim($_POST['main_email']));
		$title = trim($_POST['title']);
		$family_name = trim($_POST['family_name']);
		$given_name = trim($_POST['given_name']);
		$middle_names = trim($_POST['middle_names']);
		$local_partner = (isset($_POST['partner_id']))? trim($_POST['partner_id']) : 0;
		//$dob = $_POST['dob'];
		$dob=date('Y-m-d', strtotime($_POST['dob'])); 

		$sex = $_POST['sex'];
		$accurate = empty($_POST['accurate']) ? false : true;
		$english = empty($_POST['english']) ? false : true;
		$register = empty($_POST['register']) ? false : true;
		
		if (!is_file(DIR_SECURE_INI.'/site_config.ini')) {
			$this->addError('file_error', 'Cannot find site_config.ini file');

			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out); 
		}
		
		if(empty($country) || $country == 'not specified')
		{
			$this->addError('country','You must specify a country!');
		}

		//check email
		$user_db = new \core\modules\user\models\common\user_db;
		$user_id = $user_db->checkEmailInUse($_POST['main_email']);
		if($user_id)
		{
			$this->addError('email','We have a user with exactly this email already! Use the login section to reset your password.');
		}
		
		if(filter_var($main_email, FILTER_VALIDATE_EMAIL))
		{
			//ok now do an mx check on the domain
			list($name,$domain) = explode('@',$_POST['main_email']);

			if (isset($site_a['VALIDATE_EMAIL_MX']) && $site_a['VALIDATE_EMAIL_MX'] == 1) {
				if(!checkdnsrr($domain, 'MX'))
				{
					$this->addError('email','The email address you entered has no valid MX.');
				}
			}

			$user_db = new \core\modules\user\models\common\user_db;
			$user_id = $user_db->checkEmailInUse($_POST['main_email']);
			//check if it is already a user
			if($user_id)
			{
				$this->addError('email','We have a user with exactly this email already! Use the login section to reset your password.');
			}
		} else {
			$this->addError('email','The email address used is not valid.');
		}
		
		if(empty($given_name))
		{
			$this->addError('given_name','You give us at least a given name!');
		} else {
			//check user details
			$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
			$address_details = $address_book_common->checkMainData('per',$family_name,$given_name,$middle_names,$dob,$sex,0,$main_email);
			if($address_details['level'] == 'error')
			{
				$this->addError('name','We have a person with exactly these details already!');
			}
		}
		
		if(empty($dob) || $country == 'not specified')
		{
			$this->addError('dob','You must specify a dob!');
		}
	
		if(empty($sex) || $country == 'not specified')
		{
			$this->addError('sex','You must specify a sex!');
		}
		
		if(!$accurate || !$english || !$register)
		{
			$this->addError('acknowledge','You must acknowledge all items!');
		}
		
		//need these one regardless
		$this->addInput('country',$country);
		$this->addInput('title',$title);
		$this->addInput('family_name',$family_name);
		$this->addInput('given_name',$given_name);
		$this->addInput('middle_names',$middle_names);
		$this->addInput('dob',$dob);
		$this->addInput('sex',$sex);
		$this->addInput('main_email',$main_email);
		
		//check the hash is used already
		if(!$this->hasErrors())
		{
			//clean out any older ones
			$this->register_db->cleanRegistrationInfo();
			
			//make the hash and check
			$hash = md5($main_email.$title.$family_name.$given_name.$middle_names.$dob.$sex.$country.$local_partner);
		
			if($this->register_db->hasHash($hash))
			{
				$this->addError('Already Submitted','These details are already submitted. Please follow the link in the email that was sent or wait for 24 hours to re-apply!');
			}
		}

		if($this->hasErrors())
		{
			$this->addInput('accurate',$accurate);
			$this->addInput('english',$english);
			$this->addInput('register',$register);
		} else {
			
			//process the submission

			$this->_putToCollection($given_name . ' ' . $family_name, $main_email);
			$this->_processSubmission($hash,$country,$title,$family_name,$given_name,$middle_names,$dob,$sex,$main_email,$local_partner);
			$_SESSION['register_hash'] = $hash;
			$this->redirect = $this->baseURL.'/submitted';
		}
		
		return;
		
	}
	
	private function _processSubmission($hash,$country,$title,$family_name,$given_name,$middle_names,$dob,$sex,$main_email,$local_partner)
	{

        //ok now send an email
        $rs = $this->_sendRegisterEmail($hash,$family_name,$given_name,$main_email);
        if($rs == true)
        {
            $affected_rows = $this->register_db->insertRegister($hash,$title,$family_name,$given_name,$middle_names,$dob,$sex,$main_email,$country,$local_partner);

            if($affected_rows != 1)
            {
                $msg = 'The details did not submit successfully.  Reload and try again.';
                throw new \RuntimeException($msg);
            }

        }else{
            $msg = $rs['message'];
            throw new \RuntimeException($msg);
        }

			
		return true;
	}

	private function _putToCollection($name, $email)
	{
		$common_email = new \core\modules\send_email\models\common\common;

		$common_email->putEmailToCollection($email, $name, 'registration_submission');
	}
	
	private function _sendRegisterEmail($hash,$family_name,$given_name,$main_email)
	{
		$common_email = new \core\modules\send_email\models\common\common;
		$to_name = empty($family_name) ? $given_name : $given_name.' '.$family_name;
		$to_email = $main_email;
		
		//from the system info
		$from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
		
		//template
		$template = $common_email->renderEmailTemplate('registration', array('hash_url' => HTTP_TYPE.SITE_WWW.$this->baseURL.'/process/'.$hash));
		
		//message
		$subject = $template['subject'];
		$message = $template['html'];
		
		//cc
		$cc ='';
		
		//bcc
		if(SYSADMIN_BCC_NEW_USERS)
		{
			$bcc = SYSADMIN_EMAIL;
		} else {
			$bcc = '';
		}
		
		//html
		$html = true;
		$fullhtml = false;
		
		//unsubscribe link
		$unsubscribelink = false;
				
		//generic for the sendmail
		$generic = \core\app\classes\generic\generic::getInstance();
		$rs = $generic->sendEmailSES($to_name,$to_email,$subject,$message,$cc,$bcc,$unsubscribelink);
		
		return $rs;
	}
		
	
}
?>