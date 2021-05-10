<?php
namespace core\modules\security\models\login;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 26 October 2014
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'login';
	
	protected $redirect;
	protected $nextModel;
	
	//my variables
	private $_allowLogin = false;
	
	public function __construct()
	{
		//Security Checks
		if(isset($_SESSION['login_count']))
		{				
			//increment the session
			$_SESSION['login_count']++;
			if($_SESSION['login_count'] <= 3){
				$_SESSION['last_login'] = time();
			}
			
		} else {
			$msg = "Session Error!";
			throw new \Exception($msg);
			exit();
		}
		
		parent::__construct();
		
		return;
	}

    /**
     * Handle the login post request
     * @throws \Exception
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
				$reCAPTCHA_Login_Token = isset($_POST['reCAPTCHA_Login_Token']) ? $_POST['reCAPTCHA_Login_Token'] : '';
				
				$reCAPTCHA_score = '';
				
				if(!empty($reCAPTCHA_Login_Token))
				{
					$reCAPTCHA_ns = NS_APP_CLASSES.'\\recaptcha\\recaptcha';
					$reCAPTCHA = new $reCAPTCHA_ns($reCAPTCHA_Login_Token);
					
					if($reCAPTCHA->getSucess())
					{
						if($reCAPTCHA->getAction() == 'login')
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
								//throw new \Exception($msg);
								//exit();
							}
							
						} else {
							$msg = "Google reCAPTACHA wrong action!";
							//throw new \Exception($msg);
							//exit();
                            $this->addError('reCAPTCHA',$msg);
						}
						
					} else {
						
						$this->addError('reCAPTCHA','Sorry Google reCAPTCHA error. Please confirm with the secure image.');
						/*
						foreach($reCAPTCHA->getErrorArray() as $key => $error)
						{
							$this->errors['reCAPTCHA Error '.$key] = $error;
						}
						*/
					}
				} else {
					$this->addError('reCAPTCHA','Sorry Google reCAPTCHA error. Please confirm with the secure image.');
				}
			}
		}
		
		$username = trim($_POST['username']);
		$this->addInput('username',$username);
		
		$password = trim($_POST['password']);
		$this->addInput('password',$password);
			
		//both must be set
		if(empty($username) || empty($password))
		{
			if(empty($username)) $this->addError('username',$this->system_register->site_term('SECURITY_LOGIN_USERNAME_BLANK_ERROR'));
			if(empty($password)) $this->addError('password',$this->system_register->site_term('SECURITY_LOGIN_PASSWORD_BLANK_ERROR'));
		}
		
		//get out if we have errors
		if(!empty($this->errors))
		{
			return;
		}
			
		//ok md5 the password
		$pMd5 = $this->_makePassMd5($password,$this->system_register->site_info('SALT'));
		
		//see if we have the sys-admin and if it is don't worry about the database
		if($username == $this->system_register->site_info('USERNAME'))
		{
			$this->_processSysAdmin($pMd5);
			return;
		}
		
		//we need the user module if we are going further and the catcha must also be set
		if(in_array('user', $this->system_register->getModuleNamesArray()) )
		{
			//we need the database now
			$this->db = \core\app\drivers\db\db_mysql::getInstance('local');
			
			//convert email to username if needed
			if($email = filter_var($username, FILTER_VALIDATE_EMAIL))
			{
				$this->_processEmail($email,$pMd5);
			} else { 
				$this->_processUser($username,$pMd5);
			}
		} else {
			$this->addError('SYSTEM','"User" module not set, use the system administrator username only!');
		}
					
		return;
	}
	
	private function _processEmail($email,$password)
	{	
		//for some reason we can not bind param .. I suspect it is the email being sanitized earlier!
		
		$goodToGo = false;
		
		$qry =	"SELECT
						`user_id`, 
						`username`,
						`security_level_id`, 
						`group_id`
				FROM 
						`user`
				WHERE 
						`email` = '$email'
					AND
						`status` = 1
				";
				
		$stmt = $this->db->prepare($qry);
		$stmt->bind_result($user_id, $username, $security_level_id, $group_id);
		
		$stmt->execute();
		if($stmt->fetch())
		{
			
			$stmt->free_result();
			$stmt->close();
			$qry2 =	"SELECT
						`user_id`, 
						`username`,
						`security_level_id`, 
						`group_id`
				FROM 
						`user`
				WHERE 
						`email` = '$email'
					AND
						`password` = '$password'
					AND
						`status` = 1
				";
			
			$stmt2 = $this->db->prepare($qry2);
			$stmt2->bind_result($user_id, $username, $security_level_id, $group_id);
			
			$stmt2->execute();
			if($stmt2->fetch())
			{
				//Set the SESSION for the user
				$_SESSION['user_id'] = $user_id;
				$_SESSION['user_name'] = $username;
				$_SESSION['user_email'] = $email; 
						
				$user_security_level = $this->system_register->getSecurityLevel($security_level_id);
				$_SESSION['user_security_level'] = $user_security_level;
			
				//insert security ids allowed in a form that will allow a SQL IN statement
				$_SESSION['user_security_id_allowed'] = $this->_makeSecurityIdAllowed($user_security_level);
				
				$_SESSION['user_group'] = $group_id;
							
				//insert group names
				$_SESSION['user_group_id_allowed'] = $this->_makeGroupIdAllowed($user_security_level,$group_id);
				
				$goodToGo = true;
				
			}else{
				$this->addError('Password',"Your password is incorrect. Please try again!");
			}
			
    		$stmt2->free_result();
    		$stmt2->close();
			
		} else {
			$this->addError('Email',"Your Email is not found! Please use the correct email address.");
		}

		if($goodToGo)
		{
			//update login date
			$this->_updateLogin($user_id);

			$this->_checkEntity($email);
			
			//set permission in session
			$user_db = new \core\modules\user\models\common\user_db;
			$role = $user_db->getUserRole($_SESSION['user_id']);
			$permissions = json_decode($role['permission'], true);
			$_SESSION['role_id'] = $role['role_id'];
			$_SESSION['role_name'] = $role['role_name'];
			$_SESSION['permissions'] = $permissions;

			//lets get out of here
			$this->_goodToGo();
		}	
		return;
	}

	private function _processUser($username,$password)
	{	
		$goodToGo = false;
		
		$qry =	"SELECT
						`user_id`,
						`username`, 
						`email`,
						`security_level_id`, 
						`group_id`
				FROM 
						`user`
				WHERE 
						`username` = ?
					AND
						`password` = ?
					AND
						`status` = 1
				";
				
		$stmt = $this->db->prepare($qry);
		$stmt->bind_param("ss",$username,$password);
		
		$stmt->execute();
		$stmt->bind_result($user_id, $db_username, $email, $security_level_id, $group_id);
		
		if($stmt->fetch())
		{
			//Set the SESSION for the user
			$_SESSION['user_id'] = $user_id;
			$_SESSION['user_name'] = $db_username;
			$_SESSION['user_email'] = $email; 
			
			$user_security_level = $this->system_register->getSecurityLevel($security_level_id);
			$_SESSION['user_security_level'] = $user_security_level;
			
			//insert security ids allowed in a form that will allow a SQL IN statement
			$_SESSION['user_security_id_allowed'] = $this->_makeSecurityIdAllowed($user_security_level);
			
			$_SESSION['user_group'] = $group_id;
			
			//insert group names
			$_SESSION['user_group_id_allowed'] = $this->_makeGroupIdAllowed($user_security_level,$group_id);
			
			if($this->catchaOK)
			{
				$goodToGo = true;
			} else {
				$goodToGo = false;
			}
			
		} else {
			$this->addError('general',$this->system_register->site_term('SECURITY_LOGIN_GENERAL_ERROR'));
		}
		$stmt->free_result();
		$stmt->close();

		if($goodToGo)
		{
			//update login date
			$this->_updateLogin($user_id);

			$this->_checkEntity($email);
			
			//lets get out of here
			$this->_goodToGo();
		}
		return;
	}

	private function _checkEntity($email){
        //get address book
        $sql = "SELECT
					`address_book`.`address_book_id`
				FROM
					`address_book`
				WHERE
					`address_book`.`main_email` = '{$email}'
                AND `address_book`.`type` = 'per'
				";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($address_book_id);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();

        if($address_book_id > 0){
            //get the link
            $sql = "SELECT
					`address_book_ent_link`.`address_book_ent_id`,
					`address_book_ent_link`.`person_type`,
					`address_book_ent_link`.`security_level_id`
				FROM
					`address_book_ent_link`
				WHERE
					`address_book_ent_link`.`address_book_per_id` = '{$address_book_id}'
				";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_result($address_book_ent_id,$person_type,$security_level_id);
            $stmt->execute();
            while($stmt->fetch())
            {
            	$user_security_level = $this->system_register->getSecurityLevel($security_level_id);
				$_SESSION['entity']['user_security_level'] = $user_security_level;
                $_SESSION['entity']['address_book_ent_id'] = $address_book_ent_id;
                $_SESSION['entity']['user_security_id'] = $security_level_id;
                $_SESSION['entity']['person_type'] = $person_type;

            }
			$stmt->close();
			
        }


    }
	
	private function _processSysAdmin($pMd5)
	{
		
		if($pMd5 == $this->system_register->site_info('PASSWORD') && $this->catchaOK)
		{
			
			//Set the SESSION for the SYS-ADMIN
			$_SESSION['user_id'] = 1;
			$_SESSION['user_name'] = $this->system_register->site_info('USERNAME');
			$_SESSION['user_email'] = $this->system_register->site_info('SITE_EMAIL_ADD'); //!need to pull this from config!
			
			$user_security_level = $this->system_register->getSecurityLevel('SYSADMIN');
			$_SESSION['user_security_level'] = $user_security_level;
			
			//insert security ids allowed in a form that will allow a SQL IN statement
			$_SESSION['user_security_id_allowed'] = $this->_makeSecurityIdAllowed($user_security_level);
			
			$group_id = 'IOW';
			$_SESSION['user_group'] = $group_id; //!need to pull this from config!
			
			//insert group names
			$_SESSION['user_group_id_allowed'] = $this->_makeGroupIdAllowed($user_security_level,$group_id);
			// var_dump($_SESSION);
			$this->_goodToGo();
			
		} else {
			$this->addError('general',$this->system_register->site_term('SECURITY_LOGIN_GENERAL_ERROR'));
		}
		return;
	}
	
	private function _makePassMd5($pass,$salt)
	{
		return md5($pass.$salt);
	}
	
	private function _makeSecurityIdAllowed($user_security_level)
	{
		$security_items = array();
		
		$security_array = $this->system_register->getSecurityArray();
			
		foreach($security_array as $security_name => $security_name_info_array)
		{
			if($security_name_info_array['level'] <= $user_security_level)
			{
				$security_items[] = $security_name;
			}
		}
		
		return $security_items;
	}
	
	private function _makeGroupIdAllowed($user_security_level,$group_id)
	{
		//Groups array to set groups allowed
		$group_array = $this->system_register->getGroupArray();
		
		$group_items[] = 'ALL'; //Everyone can see ALL
	
		foreach ($group_array as $group_name => $group_name_info_array)
		{
			if($group_name == 'ALL') continue; //not needed as we already have it
			
			if($user_security_level == 100)
			{ 
				$group_items[] = $group_name;
				
			} else if( $group_name == $group_id ) {
				
				$group_items[] = $group_id;
				
				$group_members = explode("|", $group_name_info_array['members']);
				
				foreach($group_members as $group_members_name)
				{
					if($group_members_name == 'ALL' || $group_members_name == $group_id) continue; //not needed as we already have it
					
					$group_items[] = $group_members_name;
				}
				
				break; //it can only match one
			}
		}
		
		return $group_items;
	}
		
	private function _updateLogin($user_id)
	{
		$qry = "UPDATE `user` SET `last_login` = CURRENT_TIMESTAMP WHERE `user_id` = {$user_id}";
		$this->db->query($qry);
		return;
	}
		
	private function _goodToGo()
	{
		unset($_SESSION['login_count']);
		unset($_SESSION['captcha_code']);
		
		//first lets reset the session for security :-)
		session_regenerate_id() ; //regenerates a new session and deletes the old one (true)
		
		if(isset($_SESSION['system_security_redirect']) && $_SESSION['system_security_redirect'] == 1)
		{
			
			//if it is redirect then we can redirect now
			$this->redirect = '/'.$_SESSION['system_original_page_info_link'];
		} else {
			//clear all the $_SESSION stuff that really should not be set anyway
			unset($_SESSION['system_security_redirect']);
			unset($_SESSION['system_security_point']);
			unset($_SESSION['system_security_reason']);
			
			unset($_SESSION['system_original_page_info_link']);
			unset($_SESSION['system_original_page_info_options']);
			unset($_SESSION['system_original_page_info_home']);

			if($this->system_register->getModuleIsInstalled('profile')){
                //set up menu_regsiter for all models
                $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
                $this->menu_register = $menu_register_ns::getInstance();
                $this->redirect = '/'.$this->menu_register->getModuleLink('profile');
            }else{
                $this->redirect = '/';
            }
			
		}
		return;
	}
	
}
?>