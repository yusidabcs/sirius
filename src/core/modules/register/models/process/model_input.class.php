<?php
namespace core\modules\register\models\process;

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

	protected $model_name = 'process';
	
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
		//if they have not attempted to give a hash then redirect to base url
		if(isset($this->page_options[0]))
		{
			$hash = $this->page_options[0];
		} else {
			header("Location: $this->baseURL");
			exit();
		}
		
		//hash needs to be exactly 32 characters or it is not my hash
		if(strlen($hash) != 32)
		{
			header("Location: $this->baseURL");
			exit();
		}
		
		$register_db = new \core\modules\register\models\common\register_db;
		
		//check it has the right posting
		if($_POST['submit_button'] != 'go_process')
		{
			//delete the hash ... 
			$register_db->deleteHash($hash);
			
			//go to registration
			header("Location: $this->baseURL");
			exit();
		}
		
		//make sure this hash is real
		$register_info = $register_db->getRegistrationInfo($hash);

		// put collection
		$this->_moveCollection($register_info['main_email'], 'profile_not_complete');
		
		//add the person
		$this->_processRegistration($register_info['main_email'],$register_info['family_name'],$register_info['given_name'],$register_info['title'],$register_info['middle_names'],$register_info['dob'],$register_info['sex'],$register_info['country'],$register_info['partner_id']);
		
		//delete the hash!
		$register_db->deleteHash($hash);
		
		$this->addInput('registered',true);
		
		return;
		
	}

	public function _moveCollection($email, $collection_name)
	{
		$mailing_common = new \core\modules\send_email\models\common\common;

		$mailing_common->moveSubscriberToCollection('registration_submission', $collection_name, $email);
		
	}
	
	private function _processRegistration($main_email,$family_name,$given_name,$title,$middle_names,$dob,$sex,$country,$partner_id)
	{
		//don't do anything unless there is a main email .. but there really always should be!
		if(empty($main_email))
		{
			$msg = 'Told to register new email to users but no email exists in main!';
			throw new \RuntimeException($msg);
		}
		
		/** ADD A NEW PERSON to the ADDRESS BOOK **/
		
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		//first add the key contact
		$address_book_id = $address_book_db->addMainAddressBookEntry($main_email,'per',$family_name,$given_name,1);
		
		if($address_book_id > 0)
		{
			//add the extra info
			$affected_rows = $address_book_db->insertAddressBookPer($address_book_id,$title,$middle_names,$dob,$sex);

			if($partner_id){
                $affected_rows = $address_book_db->insertAddressBookConnection($address_book_id,$partner_id);
            }

			if($affected_rows != 1)
			{
				$msg = "There was a major issue with registering extra info to person address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
		
		} else {
			$msg = 'Failed to register New Person Address Entry.  Address book id was empty!';
			throw new \RuntimeException($msg);
		}
		
		//add them a main address section using only the country code (crude but it should do the trick ...)
		$address_book_db->insertAddressBookAddress($address_book_id,'main','physical','','','','','','',$country);
		
		/** ADD USER **/
		
		$this->generic = \core\app\classes\generic\generic::getInstance();
		$user_common = new \core\modules\user\models\common\user_common;
		$user_db = new \core\modules\user\models\common\user_db;
		
		//add user 
		$gname = $this->generic->safeUserId($given_name);
		$fname = $this->generic->safeUserId($family_name);
		
		$base = trim(substr($gname, 0, 3));
		$base2 = trim(substr($fname, 0, 3));

		$name = $base.$base2;		
		
		$name = str_pad($name, 6, "x", STR_PAD_RIGHT);
		
		$count = 1;
				
		do 
		{
			$id = str_pad($count, 2, "0", STR_PAD_LEFT);
			
			$username = $name.$id;
			
			if($user_common->valueOk('username',$username) && !$user_db->checkUserNameInUse($username)) 
			{
				$newNameOk = false;
			} else {
				$newNameOk = true;
			}
			
			$count++;
			
		} while ($newNameOk);
		
		//need a password
		$password = $this->generic->generateRandomPassword(10);
		
		//full name
		$to_fullname = $this->generic->getName('per', $family_name, $given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
		
		//add the new user
		$user_id = $user_common->addNewUserDefault($username,$main_email,$password,1); //Default forces level 'USER' and group 'ALL'

		$user_common->assignRoleNameToUser($user_id, 'member');
		
		//send the new user an email confirming
		$user_common->sendConfirmationEmail($username,$main_email,$password,'USER','ALL',1,$to_fullname);
					
		return;
	}

}
?>