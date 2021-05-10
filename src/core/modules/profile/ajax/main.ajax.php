<?php
namespace core\modules\profile\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 10 July 2017
 */
use Dompdf\Dompdf;
use Dompdf\Options;
final class main extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$this->authorizeAjax('main');
		$out = null;
		
		//we always need a Session
		switch($this->option) 
		{	
			case 'updateUserDetails':			
				
				$user_id = $_SESSION['user_id'];
				$username = trim($_POST['username']);
				$email = trim(strtolower($_POST['email']));
				
				$out['msg_a'] = $this->_processDetails($user_id,$username,$email);
				
				$out['ok'] = empty($out['msg_a']) ? true : false;
				
				if ($out['ok'])
				{
					$out['message'] = 'Successfully update user detail.';
				}else{

					if (isset($out['msg_a']['email']))
					{
						$msg = $out['msg_a']['email'];	
					}

					if (isset($out['msg_a']['username']))
					{
						$msg .= ','.$out['msg_a']['username'];	
					}

					$out['message'] = $msg;
				}
				break;
				
			case 'updateUserPassword':
				
				$user_id = $_SESSION['user_id'];
				$current = trim($_POST['password_current']);
				$new = trim($_POST['password_new']);
				$confirm = trim($_POST['password_confirm']);
				
				$out['msg_a'] = $this->_processPassword($user_id,$current,$new,$confirm);
				
				$out['ok'] = empty($out['msg_a']) ? true : false;
				if ($out['ok']){
					$out['message'] = 'Successfully update user password.';
				}else{
					$out['message'] = $out['msg_a']['password'];
				}
				

				
				break;
		
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
		}

		if(!empty($out))
		{
				header('Content-Type: application/json; charset=utf-8');
				return json_encode($out);
		} else {
			return ;
		}				
	}

	
	private function _processDetails($user_id,$username,$email)
	{	
		
		$user_db = new \core\modules\user\models\common\user_db;
		
		$out = array();
		
		//username can not be blank
		if(empty($username))
		{
			$out['username'] = $this->system_register->site_term('USER_USERNAME_BAD_ERROR');
		} else {
			$username_id = $user_db->checkUserNameInUse($username);
			if( $username_id > 0 && $username_id != $user_id ) $out['username'] = $this->system_register->site_term('USER_USERNAME_BAD_ERROR');
		}
	
		//check email
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			//ok now do an mx check on the domain
			list($email_name,$domain) = explode('@',$email);
			if(!checkdnsrr($domain,'MX'))
			{
				$out['email'] = $this->system_register->site_term('USER_EMAIL_BAD_MX_ERROR');
			} else {
				$email_id = $user_db->checkEmailInUse($email);
				if( $email_id > 0 && $email_id != $user_id ) $out['email'] = $this->system_register->site_term('USER_EMAIL_BAD_ERROR');
			} 
		} else {
			$out['email'] = $this->system_register->site_term('USER_EMAIL_BAD_ERROR');
		}
		
		if($_SESSION['user_email'] != $email)
		{
			//check to make sure it is not in the adddress book
			if($this->system_register->getModuleIsInstalled('address_book'))
			{
				//get address book db
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				
				//run checks
				$address_id_1 = $address_book_db->checkPersonEmail($_SESSION['user_email']);
				
				$address_id_2 = $address_book_db->checkPersonEmail($email);
				
				//you can not proceed if there is an id for both
				if($address_id_1 && $address_id_2)
				{
					$out['email'] = $this->system_register->site_term('USER_EMAIL_ABOOK_DUPLICATE_ERROR');
				}
			}
		}
		
		//if there are no errors - update as required.
		if(empty($out))
		{
			//commit off
			$user_db->commitOff();
			
			if($username != $_SESSION['user_name'])
			{
				$user_db->updateUser($user_id,'username',$username);
				$_SESSION['user_name'] = $username;
				
				//special case for user_id 1 (system administrator username)
				if($user_id == 1)
				{
					//update the Site Config File!
					$iniFile = DIR_SECURE_INI.'/site_config.ini';
					$site_ini_a = parse_ini_file($iniFile);
				    $site_ini_a['USERNAME'] = $username;
				    $writeIni = new \core\app\classes\ini\write_ini();
					$writeIni->write_php_ini($site_ini_a, $iniFile);	
				}
			}
			
			if($email != $_SESSION['user_email'])
			{
				$user_db->updateUser($user_id,'email',$email);
				
				//check to make sure it is not in the adddress book
				if($this->system_register->getModuleIsInstalled('address_book'))
				{
					$address_book_db->updateMainAddressBookPerEmail($_SESSION['user_email'],$email);
				}
				$_SESSION['user_email'] = $email;
				
				//special case for user_id 1 (system administrator email)
				if($user_id == 1)
				{
					//update the Site Config File!
					$iniFile = DIR_SECURE_INI.'/site_config.ini';
					$site_ini_a = parse_ini_file($iniFile);
				    $site_ini_a['SITE_EMAIL_ADD'] = $email;
				    $writeIni = new \core\app\classes\ini\write_ini();
					$writeIni->write_php_ini($site_ini_a, $iniFile);	
				}
			}
			
			//commit on
			$user_db->commit();
			$user_db->commitOn();

		}

		return $out;
	}
	
	private function _processPassword($user_id,$current,$new,$confirm)
	{
		
		$user_db = new \core\modules\user\models\common\user_db;
		
		$out = array();
		
		if(empty($new) || empty($confirm))
		{
			$out['password'] = $this->system_register->site_term('USER_PASSWORD_BLANK_ERROR');
		}
		
		if($new != $confirm)
		{
			$out['password'] = $this->system_register->site_term('USER_PASSWORD_NO_MATCH_ERROR');
		}
				
		//if there are no errors - update as required.
		if(empty($out))
		{
			//set the salt
			$salt = $this->system_register->site_info('SALT');
			
			//check current is correct
			$md5_current = md5($current.$salt);
			
			$current_password = $user_db->getCurrentPassword($user_id);
			
			if( $md5_current != $current_password ) 
			{
				$out['password'] = $this->system_register->site_term('USER_PASSWORD_NOT_CURRENT_ERROR');
			} else {
				//correct so make the change
				$md5_new = md5($new.$salt);
				
				//special case for user_id 1 (system administrator password)
				if($user_id == 1)
				{
					//update the Site Config File!
					$iniFile = DIR_SECURE_INI.'/site_config.ini';
					$site_ini_a = parse_ini_file($iniFile);
				    $site_ini_a['PASSWORD'] = $md5_new;
				    $writeIni = new \core\app\classes\ini\write_ini();
					$writeIni->write_php_ini($site_ini_a, $iniFile);	
				}
				
				//update
				$user_db->updateUser($user_id,'password',$md5_new);
			}
		} 
		
		return $out;
	}
	
}
?>