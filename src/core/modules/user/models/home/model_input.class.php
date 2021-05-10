<?php
namespace core\modules\user\models\home;

/**
 * Final model_input class.
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2015
 */
final class model_input extends \core\app\classes\module_base\module_model_input {
	
	protected $model_name = 'home';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		$this->user_db = new \core\modules\user\models\common\user_db;
		
		$mrClass = NS_APP_CLASSES.'\messages_register\messages_register';
		$this->messages_register = $mrClass::getInstance();
		
		parent::__construct();	
		return;
	}
	
	protected function processPost()
	{
		$this->authorize();
		if(isset($_POST['username']))
		{
			$this->_processDetails();
		}
		
		if(isset($_POST['password_current']))
		{
			$this->_processPassword();
		}
		
		return;
	}
	
	private function _processDetails()
	{
		//absolute check to make sure it is them!
		if(isset($_POST['user_id']) && isset($_SESSION['user_id']) && $_POST['user_id'] == $_SESSION['user_id'])
		{
			$user_id = $_POST['user_id'];
		} else {
			$msg = 'Failed Security Check!';
			throw new \RuntimeException($msg);
		}
		
		//check username
		$username = trim($_POST['username']);
		$this->addInput('username',$username);

		//username can not be blank
		if(empty($username))
		{
			$this->addError('username',$this->system_register->site_term('USER_USERNAME_BAD_ERROR'));
		} else {
			$username_id = $this->user_db->checkUserNameInUse($username);
			if( $username_id > 0 && $username_id != $user_id ) $this->addError('username',$this->system_register->site_term('USER_USERNAME_BAD_ERROR'));
		}

	
		//check email
		$email = trim(strtolower($_POST['email']));
		$this->addInput('email',trim($email));
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			//ok now do an mx check on the domain
			list($email_name,$domain) = explode('@',$email);
			if(!checkdnsrr($domain,'MX'))
			{
				$this->addError('email',$this->system_register->site_term('USER_EMAIL_BAD_MX_ERROR'));
			} else {
				$email_id = $this->user_db->checkEmailInUse($email);
				if( $email_id > 0 && $email_id != $user_id ) $this->addError('email',$this->system_register->site_term('USER_EMAIL_BAD_ERROR'));
			}
		} else {
			$this->addError('email',$this->system_register->site_term('USER_EMAIL_BAD_ERROR'));
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
					$this->addError('email',$this->system_register->site_term('USER_EMAIL_ABOOK_DUPLICATE_ERROR'));
				}
			}
		}
		
		$this->addInput('email',trim($email));
		
		//if there are no errors - update as required.
		if(!$this->hasErrors())
		{
			//commit off
			$this->user_db->commitOff();
			
			if($username != $_SESSION['user_name'])
			{
				$this->user_db->updateUser($user_id,'username',$username);
				$this->messages_register->addMessage("User updated {$user_id} username '{$_SESSION['user_name']}' to '{$username}'",30,'user model_input','_processDetails',93);
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
				$this->user_db->updateUser($user_id,'email',$email);
				
				$this->messages_register->addMessage("User updated {$user_id} email '{$_SESSION['user_email']}' to '{$email}'",30,'user model_input','_processDetails',99);
				
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
			$this->user_db->commit();
			$this->user_db->commitOn();
			
			$this->redirect = $this->modelURL;
		}
		
		return;
	}
	
	private function _processPassword()
	{
		//absolute check to make sure it is them!
		if(isset($_POST['user_id']) && isset($_SESSION['user_id']) && $_POST['user_id'] == $_SESSION['user_id'])
		{
			$user_id = $_POST['user_id'];
		} else {
			$msg = 'Failed Security Check!';
			throw new \RuntimeException($msg);
		}

		//process posts
		$current = trim($_POST['password_current']);
		$this->addInput('password_current',$current);
		
		$new = trim($_POST['password_new']);
		$this->addInput('password_new',$new);
		
		$confirm = trim($_POST['password_confirm']);
		$this->addInput('password_confirm',$confirm);
		
		if(empty($new) || empty($confirm))
		{
			$this->addError('password',$this->system_register->site_term('USER_PASSWORD_BLANK_ERROR'));
		}
		
		if($new != $confirm)
		{
			$this->addError('password',$this->system_register->site_term('USER_PASSWORD_NO_MATCH_ERROR'));
		}
				
		//if there are no errors - update as required.
		if(!$this->hasErrors())
		{
			//set the salt
			$salt = $this->system_register->salt();
			
			//check current is correct
			$md5_current = md5($current.$salt);
			$current_password = $this->user_db->getCurrentPassword($user_id);
			
			if( $md5_current != $current_password )  
			{
				$this->addError('password',$this->system_register->site_term('USER_PASSWORD_NOT_CURRENT_ERROR'));
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
				$this->user_db->updateUser($user_id,'password',$md5_new);
				$this->messages_register->addMessage("User updated {$user_id} password",30,'user model_input','_processDetails',153);
				$this->redirect = $this->modelURL;	
			}
		} 
		
		return;
	}
	
}
?>