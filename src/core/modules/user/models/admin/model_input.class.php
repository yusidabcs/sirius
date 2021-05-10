<?php
namespace core\modules\user\models\admin;

/**
 * Final model_input class for user admin
 *
 * @final
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {
	
	protected $model_name = 'admin';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		$user_common_common_ns = NS_MODULES.'\\user\\models\\common\\user_common';
		$this->user_common = new $user_common_common_ns();
		
		$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
		$this->user_db = new $user_db_common_ns();
		
		parent::__construct();
	
		return;
	}
	
	protected function processPost()
	{
		$this->authorize();
		if($_POST['action'] == 'add_new_user')
		{
			$username = empty($_POST['username']) ? '' : $_POST['username'];
			$email = empty($_POST['email']) ? '' : $_POST['email'];
			$security_level_id = empty($_POST['security_level_id']) ? '' : $_POST['security_level_id'];
			$group_id = empty($_POST['group_id']) ? '' : $_POST['group_id'];
			$password = empty($_POST['password']) ? '' : $_POST['password'];
			$send_user_email = empty($_POST['send_user_email']) ? 0 : $_POST['send_user_email'];

			$add_address_book = empty($_POST['add_address_book']) ? 0 : $_POST['add_address_book'];
			$role_id = empty($_POST['role_id']) ? 0 : $_POST['role_id'];
		


		} else {
			$msg = 'Unknown user admin input!';
			throw new \RuntimeException($msg);
		}
		
		//check variables
		$this->_checkUsername($username);
		$this->_checkEmail($email);
		$this->_checkPassword($password);
		$this->_checkRole($role_id);
		
		if($this->hasErrors())
		{
			$this->addInput('username',$username);
			$this->addInput('email',$email);
			$this->addInput('security_level_id',$security_level_id);
			$this->addInput('group_id',$group_id);
			$this->addInput('password',$password);
			$this->addInput('role_id',$role_id);
		} else {
			//add the user
			$user_id = $this->user_common->addNewUser($username,$email,$password,'USER','ALL',1);
			if ($role_id > 0) {
				$this->user_common->assignRoleToUser($user_id, $role_id);
			}

			if($add_address_book){
            	$this->_insertAddressBook();
            }
			
			if($send_user_email){
                $this->user_common->sendConfirmationEmail($username,$email,$password,$security_level_id,$group_id,$send_user_email);
            }


           
			$this->redirect = $this->modelURL;
			$this->addMessage('User','Success create new user!');
		}
		
		return;
	}
	private function _insertAddressBook(){

		$email = empty($_POST['email']) ? '' : $_POST['email'];
		$title = empty($_POST['title']) ? '' : $_POST['title'];
		$family_name = empty($_POST['family_name']) ? '' : $_POST['family_name'];
		$given_name = empty($_POST['family_name']) ? '' : $_POST['given_name'];
		$middle_names = empty($_POST['middle_names']) ? '' : $_POST['middle_names'];
		$dob = empty($_POST['dob']) ? '' : $_POST['dob'];
		$sex = empty($_POST['sex']) ? '' : $_POST['sex'];
		$partner_id = empty($_POST['partner_id']) ? '' : $_POST['partner_id'];
		$country = empty($_POST['country']) ? '' : $_POST['country'];
		/** ADD A NEW PERSON to the ADDRESS BOOK **/
		
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		//first add the key contact
		$address_book_id = $address_book_db->addMainAddressBookEntry($email,'per',$family_name,$given_name,1);
		
		if($address_book_id > 0)
		{
			//add the extra info
			$affected_rows = $address_book_db->insertAddressBookPer($address_book_id,$title,$middle_names,$dob,$sex,$partner_id);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with registering extra info to person address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}

			//add them a main address section using only the country code (crude but it should do the trick ...)
			$address_book_db->insertAddressBookAddress($address_book_id,'main','physical','','','','','','',$country);
		
		} else {
			$msg = 'Failed to register New Person Address Entry.  Address book id was empty!';
			throw new \RuntimeException($msg);
		}
		
	}
			
	private function _checkUsername($username)
	{
		if($this->user_common->valueOk('username',$username))
		{
			if( $this->user_db->checkUserNameInUse($username) )
			{
				$this->addError('username',$this->system_register->site_term('USER_USERNAME_DUPLICATE_ERROR'));
			}
		} else {
			$this->addError('username',$this->system_register->site_term('USER_USERNAME_BAD_ERROR'));
		}
		return;
	}
	
	private function _checkEmail($email)
	{
		if(!$this->user_common->valueOk('email',$email))
		{
			$this->addError('email',$this->system_register->site_term('USER_EMAIL_BAD_ERROR'));
		} 
		return;
	}
	
	private function _checkSecurityLevelId($security_level_id)
	{
		if(!$this->user_common->valueOk('security_level_id',$security_level_id))
		{
			$this->addError('security level',$this->system_register->site_term('USER_SECURITY_BAD_ERROR'));
		}
		return;
	}
	
	private function _checkGroupId($group_id)
	{
		if(!$this->user_common->valueOk('group_id',$group_id))
		{
			$this->addError('group',$this->system_register->site_term('USER_GROUP_BAD_ERROR'));
		}
		return;
	}
	
	private function _checkPassword($password)
	{
		if(empty($password) || strlen($password) < 6)
		{
			$this->addError('password',$this->system_register->site_term('USER_PASSWORD_BAD_ERROR'));
		}
		return;
	}

	private function _checkRole($role)
	{
		if(empty($role) || $role  <= 0)
		{
			$this->addError('role','BAD ROLE');
		}
		return;
	}
	
}
?>