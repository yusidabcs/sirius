<?php
namespace core\modules\address_book\models\common\add;

/**
 * Final core_obj class.
 *
 * Is the actual address book common class where things need to change.
 *
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 5 January 2016
 */
final class core_obj {
	
	private $_address_book_id = ''; //the address book id
	
	//used to find the view information
	private $_view_base_dir = '/core/modules/address_book/views/common/add';
	
	//acceptable content
	private $_acceptable_content_array = array('address','avatar','internet','main','pots');
	
	//array of content classes
	private $_content = array();
	
	public function __construct()
	{	
		//need user common and db for the user information to link in 
		$this->user_common = new \core\modules\user\models\common\user_common;
		$this->user_db = new \core\modules\user\models\common\user_db;
		
		//generic for the sendmail prep
		$this->generic = \core\app\classes\generic\generic::getInstance();
		
		//connect to system register object
		$this->system_register = \core\app\classes\system_register\system_register::getInstance();
		
		//set link to address book db because they all need it to add, modify and delete
		$this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		//address_book_common
		$this->address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		//load up content class from post if any
		$this->_loadContentClassArray();
				
		return;
	}
	
	/* Load up the content array with all the relevant classes */
	private function _loadContentClassArray()
	{
		if(!empty($_POST))
		{
			$contentNames = array_keys($_POST);
			
			foreach($contentNames as $contentName)
			{
				if( in_array($contentName, $this->_acceptable_content_array) )
				{
					$className = '\core\modules\address_book\models\common\add\\'.$contentName;
					$this->_content[$contentName] = new $className;
				}
			}
		}
		
		return;
	}

	/* set address book id */
	
	public function setAddressBookId($address_book_id)
	{
		if($this->address_book_db->checkAddressID($address_book_id))
		{
			$this->_address_book_id = $address_book_id;
		} else {
			$msg = "The address book id {$this->_address_book_id} does not exist!";
			throw new \RuntimeException($msg); 
		}
		return;
	}
		
	/* Check Values */
	
	public function checkVariables()
	{	
		if(empty($this->_content))
		{
			$msg = 'Call to check variables with no content classes!';
			throw new \RuntimeException($msg);
		}
		
		$errors = array();
		
		foreach($this->_content as $contentClass)
		{
			$classError_a = $contentClass->checkVariables();
			$errors = array_merge($errors,$classError_a);
		}
		
		return $errors;
	}
	
	/* Add Main Variables */
	
	public function addNewAddressBookEntry()
	{
		
		//double check all is ok
		$this->_checkAllOk();
		
		//add the core info
		$this->_addCoreInfo();
		
		//check to make sure there is an address book id
		if($this->_address_book_id > 0)
		{
			foreach($this->_content as $contentName => $contentClass)
			{
				$contentClass->addInfo($this->_address_book_id);
			}
		} else {
			$msg = 'There was no address book id set after running add core info.';
			throw new \RuntimeException($msg);
		}
		
		return $this->_address_book_id;
	}
	
	private function _addCoreInfo()
	{
		//set the required variables to make it easier to see what is happening
		$type = $this->_content['main']->getValue('type');
		$entity_family_name = $this->_content['main']->getValue('entity_family_name');
		$number_given_name = $this->_content['main']->getValue('number_given_name');
		$main_email = $this->_content['main']->getValue('main_email');
		$per_address_book_id = $this->_content['main']->getValue('per_address_book_id');
		$contact_allowed = $this->_content['main']->getValue('contact_allowed');
		$add_new_user = $this->_content['main']->getValue('add_new_user');
		$send_new_user_email = $this->_content['main']->getValue('send_new_user_email');
		
		//person details
		$title = $this->_content['main']->getValue('title');
		$middle_names = $this->_content['main']->getValue('middle_names');
		$dob = $this->_content['main']->getValue('dob');
		$sex = $this->_content['main']->getValue('sex');
		
		//handle ent_admin
		$ent_admin = $this->_content['main']->getValue('ent_admin');
		
		$ent_admin_same_email = $ent_admin['same_email'];
		$ent_admin_per_address_book_id = $ent_admin['per_address_book_id'];
		$ent_admin_email = $ent_admin['email'];
		$ent_admin_title = $ent_admin['title'];
		$ent_admin_family_name = $ent_admin['family_name'];
		$ent_admin_given_name = $ent_admin['given_name'];
		$ent_admin_middle_names = $ent_admin['middle_names'];
		$ent_admin_dob = $ent_admin['dob'];
		$ent_admin_sex = $ent_admin['sex'];
		$ent_admin_contact_allowed = $ent_admin['contact_allowed'];
		$ent_admin_add_new_user = $ent_admin['add_new_user'];
		$ent_admin_send_new_user_email = $ent_admin['send_new_user_email'];
		
		if($type == 'ent')
		{
			if(!empty($per_address_book_id))
			{
				$key_contact_id = $per_address_book_id;
				
			} else if(!empty($ent_admin_per_address_book_id)) {
				
				$key_contact_id = $ent_admin_per_address_book_id;
				
			} else {
			
				if(empty($ent_admin_given_name))
				{
					$msg = 'There was no person address book id in main or ent admin. Key Person Given Name can not be Blank also.';
					throw new \RuntimeException($msg);
				}
				
				$key_person_email = $ent_admin_same_email == 1 ? $main_email : $ent_admin_email;
				
				//first add the key contact
				$key_contact_id = $this->address_book_db->addMainAddressBookEntry($key_person_email,'per',$ent_admin_family_name,$ent_admin_given_name,$ent_admin_contact_allowed);
				
				if($key_contact_id > 0)
				{
					//add the extra info
					$affected_rows = $this->address_book_db->insertAddressBookPer($key_contact_id,$ent_admin_title,$ent_admin_middle_names,$ent_admin_dob,$ent_admin_sex);
					
					if($affected_rows != 1)
					{
						$msg = "There was a major issue with adding extra info to address id {$key_contact_id}. Affected was {$affected_rows}";
						throw new \RuntimeException($msg);
					}
				
				} else {
					$msg = 'Failed to add New Address Entry.  Address book id was empty!';
					throw new \RuntimeException($msg);
				}
				
				if($ent_admin_add_new_user)
				{
					if(empty($key_person_email))
					{
						$msg = 'Told to add new ent admin email to users but no email exists in ent admin or main!';
						throw new \RuntimeException($msg);
					}
					
					$this->addNewUser($ent_admin_given_name,$ent_admin_family_name,$key_person_email,$ent_admin_send_new_user_email);
				}
				
				//if they are an existing user then this sends them an email to say they are linked to the new profile
				if($user_id = $this->user_db->checkEmailInUse($key_person_email))
				{
					if(empty($key_person_email))
					{
						$msg = 'Told to send email to user but the email address is empty!';
						throw new \RuntimeException($msg);
					}
					
					$ent_admin_full_name = $this->generic->getName('per', $ent_admin_family_name, $ent_admin_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
					
					$entity_full_name = $this->generic->getName('ent',$entity_family_name, $number_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
					
					$this->_sendUserConnectedEmail($ent_admin_full_name,$key_person_email,$entity_full_name);
				}
			}
			
			/** HANDLE THE ENTITY **/
			
			//first add the key contact
			$address_book_id = $this->address_book_db->addMainAddressBookEntry($main_email,$type,$entity_family_name,$number_given_name,$contact_allowed);
						
			/** LINK ENTITY TO ADMIN **/
			
			$affected_rows = $this->address_book_db->addAddressBookAdminLink($address_book_id,$key_contact_id,'key_person', 'STAFF');
				
			if($affected_rows != 1)
			{
				$msg = "Tried to link {$address_book_id} to {$key_contact_id} but it failed. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}

			
		} else {
			
			/** ADD A NEW PERSON **/
			
			//first add the key contact
			$address_book_id = $this->address_book_db->addMainAddressBookEntry($main_email,$type,$entity_family_name,$number_given_name,$contact_allowed);
			
			if($address_book_id > 0)
			{
				//add the extra info
				$affected_rows = $this->address_book_db->insertAddressBookPer($address_book_id,$title,$middle_names,$dob,$sex);
				
				if($affected_rows != 1)
				{
					$msg = "There was a major issue with adding extra info to person address id {$address_book_id}. Affected was {$affected_rows}";
					throw new \RuntimeException($msg);
				}
			
			} else {
				$msg = 'Failed to add New Person Address Entry.  Address book id was empty!';
				throw new \RuntimeException($msg);
			}
			
			if($add_new_user)
			{
				if(empty($main_email))
				{
					$msg = 'Told to add new email to users but no email exists in main!';
					throw new \RuntimeException($msg);
				}
				
				$this->addNewUser($number_given_name,$entity_family_name,$main_email,$send_new_user_email);
			}

		}
		
		$this->_address_book_id = $address_book_id;
		
		return;
	}
	
	private function _checkAllOk()
	{
		$out = true;
		
		foreach($this->_content as $contentName => $contentClass )
		{
			if(!$contentClass->checkOK())
			{	
				$out = false;
			}
		}
		
		if($out == false)
		{
			$msg = 'Failed check of all models in add core object!';
			throw new \RuntimeException($msg);
		}
		
		return $out;
	}
		
	public function addNewUser($given_name,$family_name,$user_email,$send_new_user_email)
	{
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
				
				if($this->user_common->valueOk('username',$username) && !$this->user_db->checkUserNameInUse($username)) 
				{
					$newNameOk = false;
				} else {
					$newNameOk = true;
				}
				
				$count++;
				
			} while ($newNameOk);
			
			//need a password
			$password = $this->generic->generateRandomPassword(10);

			//add the new user
			$user_id = $this->user_common->addNewUser($username,$user_email,$password,'USER','ALL',1);
			$this->user_common->assignRoleNameToUser($user_id, 'member');
			
			//send the new user an email confirming
			$this->user_common->sendConfirmationEmail($username,$user_email,$password,'USER','ALL',$send_new_user_email);
			
			return $user_id;
	}
	
	private function _sendUserConnectedEmail($username,$email,$entity_full_name)
	{
		$mailing_common = new \core\modules\send_email\models\common\common;
		$mailing_db = new \core\modules\send_email\models\common\db;
		//from
		$from_name = $_SESSION['user_name'];
		$from_email = $_SESSION['user_email'];
		
		//subject
		$template = $mailing_common->renderEmailTemplate('address_book_access', [
			'username' => $username,
			'site_title' => $this->system_register->site_info('SITE_TITLE'),
			'entity_full_name' => $entity_full_name,
			'site_name' => $this->system_register->site_info('SITE_EMAIL_NAME')
		]);

		if ($template) {
			$subject = $template['subject'];
		} else {
			$subject = 'Address Book Access Updated: '.SITE_WWW;
		}
		
		//message
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
		$this->generic->sendEmail($username,$email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
		
		return;
	}
		
	/* Other Address Book Content Get */
	
	public function getContentViewFile($contentName,$personOnly = false,$main_email = '')
	{
		if( empty($this->_content[$contentName]) )
		{
			if(in_array($contentName, $this->_acceptable_content_array))
			{
				$className = '\core\modules\address_book\models\common\add\\'.$contentName;
				$this->_content[$contentName] = new $className;
				
			} else {
				$msg = "Can not get content view information for {$contentName} because it does not exist!";
				throw new \RuntimeException($msg); 
			}
		}
		
		//set the variables
		$this->address_book_common->setTerms($this->_view_base_dir,$contentName);
		$this->address_book_common->setJS($this->_view_base_dir,$contentName);
		$this->address_book_common->setCSS($this->_view_base_dir,$contentName);
		
		//view variables
		$this->_content[$contentName]->setVariablesArray();
		$viewVariables = $this->_content[$contentName]->getViewVariables();
		
		//specific settings for main
		if($contentName == 'main')
		{
			$viewVariables['personOnly'] = $personOnly;
			if(empty($main_email))
			{
				$viewVariables['fixedEmail'] = false;
			} else {
				$viewVariables['fixedEmail'] = true;
				$viewVariables['mainEmail'] = $main_email;
			}
		}
		
		if(!empty($viewVariables)) $this->address_book_common->setViewVariables($viewVariables);

		//view switches
		$viewSwitches = $this->_content[$contentName]->getviewSwitches();
		if(!empty($viewSwitches)) $this->address_book_common->setViewSwitches($viewSwitches);
				
		//view file
		$viewFile = DIR_BASE.$this->_view_base_dir.'/'.$contentName.'/'.$contentName.'.php';
		
		if(!is_readable($viewFile))
		{
		    $msg = "Can not find a view file {$viewFile}!";
			throw new \RuntimeException($msg);   
	    } 
	    
		return $viewFile;
	}

	public function insertAddressBookConnection($ab_id, $entity_id){
        $this->address_book_db->insertAddressBookConnection($ab_id, $entity_id);
    }
	
}
?>