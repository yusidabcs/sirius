<?php
namespace core\modules\user\models\common;

/**
 * Final common class.
 * 
 * @final
 */
final class user_common {

	public function __construct()
	{
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$this->system_register = $system_register_ns::getInstance();
		
		
		$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
		$this->user_db = new $user_db_common_ns();
		
		return;
	}
	
	public function valueOk($field,$value)
	{
		//default is true
		$out = true;
		
		//trim the value just in case
		$value = trim($value);
		
		//checks if the field is good or not. It DOES NOT check to see if the value is in use in the database!
		switch ($field) 
		{
			case 'username':
			
				if(empty($value))
				{
					$out = false;
				} else {
					
					if(strtolower($value) == 'username')
					{
						$out = false;
					} else {
	
						if(strtolower($value) == strtolower(trim($this->system_register->site_info('USERNAME'))))
						{
							$out = false;
						}
					}
				}
				
		        break;

		    case 'email':
		    	
		    	if(empty($value))
		    	{
			    	$out = false;
		    	} else {
			    	
			        if(filter_var($value, FILTER_VALIDATE_EMAIL))
					{
						//ok now do an mx check on the domain
                        if (isset($site_a['VALIDATE_EMAIL_MX']) && $site_a['VALIDATE_EMAIL_MX'] == 1) {
                            list($email_name,$domain) = explode('@',$value);
                            if(!checkdnsrr($domain,'MX'))
                            {
                                $out = false;
                            }
                        }

					} else {
						$out = false;
					}
					
				}
		        break;
		        
		    case 'security_level_id':
		    	if( !in_array($value, $this->system_register->permittedSecurityArray()) )
		    	{ 
			    	$out = false; 
			    }
		        break;
		        
		    case 'group_id':
		        if( !in_array($value, $this->system_register->permittedGroupArray()) )
		    	{ 
			    	$out = false; 
			    }
		        break;
		    
		    case 'password':
		        if( empty($value))
		    	{ 
			    	$out = false; 
			    }
				break;

			case 'partner':
				if( empty($value) && strlen($value) > 5 )
				{ 
					$out = false; 
				}
				break;
		}
		
		return $out;
	}
	
	public function addNewUser($username,$email,$password,$security_level_id,$group_id,$status)
	{
		//final validation just to be certain!
		if( $this->valueOk('username',$username) &&
			!$this->user_db->checkUserNameInUse($username) &&
			$this->valueOk('email',$email) &&
			!$this->user_db->checkEmailInUse($email) &&
			$this->valueOk('password',$password) &&
			$this->valueOk('security_level_id',$security_level_id) &&
			$this->valueOk('group_id',$group_id) )
		{
			$user_id = $this->user_db->addNewUserDb($username,$email,$password,$security_level_id,$group_id,$status);
		} else {
			$msg = "Very Bad! Add user did not validate! (username: {$username}, email: {$email})";
			throw new \RuntimeException($msg);
		}
		
		return $user_id;
	}
	
	
	public function addNewUserDefault($username,$email,$password,$status)
	{
	    // var_dump($this->valueOk('username',$username));
	    // var_dump(!$this->user_db->checkUserNameInUse($username));
	    // var_dump($this->valueOk('email',$email));
	    // var_dump(!$this->user_db->checkEmailInUse($email));
	    // var_dump($this->valueOk('password',$password));
	    // var_dump($email);
		if( $this->valueOk('username',$username) &&
			!$this->user_db->checkUserNameInUse($username) &&
			$this->valueOk('email',$email) &&
			!$this->user_db->checkEmailInUse($email) &&
			$this->valueOk('password',$password) )
		{
			$user_id = $this->user_db->addNewUserDb($username,$email,$password,'USER','ALL',$status);
		} else {
			$msg = "Very Bad! Add user did not validate! (username: {$username}, email: {$email})";
			throw new \RuntimeException($msg);
		}
		
		return $user_id;
	}
	
	public function sendConfirmationEmail($username,$email,$password,$security_level_id,$group_id,$send_user_email = false,$to_fullname='')
	{
		$common_email = new \core\modules\send_email\models\common\common;
		//to
		$admin_to_name = $this->system_register->site_info('SITE_EMAIL_NAME');
		$admin_to_email = $this->system_register->site_info('SITE_EMAIL_ADD');
		//from
		if(isset($_SESSION['user_name']) && isset($_SESSION['user_email']))
		{
			$from_name = $_SESSION['user_name'];
			$from_email = $_SESSION['user_email'];
		} else {
			$from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
			$from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
		}
		
		$data = array(
			'site_title' => $this->system_register->site_info('SITE_TITLE'),
			'site_email_name' => $this->system_register->site_info('SITE_EMAIL_NAME'),
			'username' => $username,
			'email' => $email,
			'password' => $password,
			'security_level_id' => '_',
			'group_id' => '_',
		);
		
		if(!$send_user_email)
		{
			$data['security_level_id'] = $security_level_id;
			$data['group_id'] = $group_id;
		}
		
		$template = $common_email->renderEmailTemplate('registration_complete', $data);
		//cc
		if($send_user_email)
		{
			$cc = $admin_to_email;
			
		} else {
			$cc = '';
		}
		
		//bcc
		if(SYSADMIN_BCC_NEW_USERS && $admin_to_email != SYSADMIN_EMAIL)
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
		
		if($send_user_email)
		{
			if(empty($to_fullname))
			{
				$generic->sendEmail($username,$email,$from_name,$from_email,$template['subject'],$template['html'],$cc,$bcc,$html,$fullhtml,$unsubscribelink);
			} else {
				$generic->sendEmail($to_fullname,$email,$from_name,$from_email,$template['subject'],$template['html'],$cc,$bcc,$html,$fullhtml,$unsubscribelink);
			}
		} else {
			$generic->sendEmail($admin_to_name,$admin_to_email,$from_name,$from_email,$template['subject'],$template['html'],$cc,$bcc,$html,$fullhtml,$unsubscribelink);
		}
		
		return;
	}

	public function getUsers(){
        return $this->user_db->getUsers();
	}

	public function assignRoleToUser($user_id, $role_id)
	{
		return $this->user_db->assignRole([$user_id], $role_id);
	}
	
	public function assignPermissionToRole($role_id, $permission)
	{
		return $this->user_db->updateRolePermission($role_id, $permission);
	}

	public function detachUserFromRole($user_id)
	{
		return $this->user_db->detachUserRole($user_id);
	}

	public function assignRoleNameToUser($user_id, $role_name)
	{
		$role = $this->user_db->getRoleByName($role_name);

		if (!$role) {
			$this->user_db->insertRole($role_name);

			$role = $this->user_db->getRoleByName($role_name);
		}

		$this->assignRoleToUser($user_id, $role['role_id']);
	}
}
?>