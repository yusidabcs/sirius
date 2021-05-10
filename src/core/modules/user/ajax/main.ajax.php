<?php
namespace core\modules\user\ajax;

/**
 * Final default class.
 * 
 * @final
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class main extends \core\app\classes\module_base\module_ajax {
	
	protected $optionRequired = true;
	
	public function run()
	{
		//the option is the user_id but it can not be the user themselves
		if( $this->option == $_SESSION['user_id'] )
		{
			die('Use the "user forms" to update yourself!');
		}
		
		//we neeed user_db
		$user_db_common_ns = NS_MODULES.'\\user\\models\\common\\user_db';
		$this->user_db = new $user_db_common_ns();
			
		//field
		$valid_fields = array('username','email','security_level_id','group_id','status');
		
		if( empty($_POST['attribute']) || !in_array($_POST['attribute'], $valid_fields) )
		{
			die('No valid data!');
		}
		
		//posted_value is empty so no good!
		if(empty($_POST['value']))
		{
			//this is the original data from the database
			$original_values = $this->user_db->selectUserDetails($this->option);	
			
			//this is the original specific data for the correct field	
			$orig_val = $original_values[$this->option][$_POST['attribute']];
			
			//modify origninal value output for specific fields if needed
			switch ($_POST['attribute']) 
			{
			    case 'status':
			        $orig_val = $orig_val == 1 ? 'On' : 'Off';
			        break;
			    case 'security_level_id':
			        $orig_val = $this->system_register->getSecurityTitleFromId($orig_val);
			        break;
			    case 'group_id':
			        $orig_val = $this->system_register->getGroupTitle($orig_val);
			        break;
			}
			
			$out['update'] = $orig_val;
			$out['message'] = 'The server was not updated!';
			
		} else {
			
			//ok the value and field is set so correct it or check it if needed to add to the database
			
			switch ($_POST['attribute']) 
			{
			    case 'status':
			    
			    	//correct the value 
			    	$corrected_value = $_POST['value'] == 'On' ? 1 : 0;
			    	$out = $this->_updateInformation($this->option,$_POST['attribute'],$corrected_value);
			        break;
					
				case 'email':
			    
			    	$user_id_for_email = $this->user_db->checkEmailInUse($_POST['value']);
			    	
					if($user_id_for_email)
					{
						if($user_id_for_email == $this->option)
						{
							$out['update'] = $_POST['value'];
							$out['message'] = '';
						} else {
							//this is the original data from the database
							$original_values = $this->user_db->selectUserDetails($this->option);	
							
							//this is the original specific data for the correct field	
							$orig_val = $original_values[$this->option][$_POST['attribute']];
				
							$out['update'] = $orig_val;
							$out['message'] = 'Duplicate Email, not updated!';
						}
					} else {
						
						if($this->system_register->getModuleIsInstalled('address_book'))
						{
							//get address book db
							$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
							
							//run checks
							$original_user_email = $this->user_db->getEmailFromUserID($this->option);
							$address_id_1 = $address_book_db->checkPersonEmail($original_user_email);
							
							$address_id_2 = $address_book_db->checkPersonEmail($_POST['value']);
							
							//you can not proceed if there is an id for both
							if($address_id_1 && $address_id_2)
							{
								$out['update'] = $original_user_email;
								$out['message'] = 'That email is used by another person in the address book!';
							} else {
							
								$out = $this->_updateInformation($this->option,$_POST['attribute'],$_POST['value']);
								$address_book_db->updateMainAddressBookPerEmail($original_user_email,$_POST['value']);
							}
						}
						
					}
					
			        break;

			    default:
			    	$out = $this->_updateInformation($this->option,$_POST['attribute'],$_POST['value']);
			}
			
		}
			
		return json_encode($out);
	}
	
	private function _updateInformation($user_id,$field,$value)
	{
		if( $this->user_db->updateUser($user_id,$field,$value) )
		{
			//ok it went fine so return back values that the js can use
			switch ($field) 
			{
			    case 'status':
			        $out['update'] = $value == 1 ? 'On' : 'Off';
			        break;
			    case 'security_level_id':
			        $out['update'] = $this->system_register->getSecurityTitleFromId($value);
			        break;
			    case 'group_id':
			        $out['update'] = $this->system_register->getGroupTitle($value);
			        break;
			    default:
			       $out['update'] = $value;
			}
			
			//message for Sysadmin Only
			if($_SESSION['user_id'] == 1)
			{
				$out['message'] = "$field to $value for $this->option was Updated";
			} else {
				$out['message'] = 'Updated';
			}
			
		} else {
			
			//did not update so revert back
			
			//get the original information array from the database
			$original_values = $this->user_db->selectUserDetails($user_id);
			
			//get the original value for this field		
			$value = $original_values[$this->option][$field];
			
			//correct it for output
			switch ($field) 
			{
			    case 'status':
			        $out['update'] = $value == 1 ? 'On' : 'Off';
			        break;
			    case 'security_level_id':
			        $out['update'] = $this->system_register->getSecurityTitleFromId($value);
			        break;
			    case 'group_id':
			        $out['update'] = $this->system_register->getGroupTitle($value);
			        break;
			    default:
			       $out['update'] = $value;
			}
			
			//Message for Everyone
			$out['message'] = 'The server was not update!';	
		}
		
		return $out;
	}
	
}
?>