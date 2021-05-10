<?php
Namespace core\modules\address_book\models\common\add;

final class main extends content
{
	//this name
	protected $contentName = 'main';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert','useFlatpickr'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	protected $contentValue = array();
	
	public function __construct()
	{
		parent::__construct();
		
		//set link to address book db because they all need it to add, modify and delete
		$this->address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				
		return;
	}
	
	public function setVariablesArray()
	{
		$out = array();
		
		//values for date of birth picker
		$min_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MAX_AGE );
		$max_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MIN_AGE );
		
		$out['dob_min'] = date('c', $min_date);
		$out['dob_max'] = date('c', $max_date);
		
		if(empty($this->contentValue))
		{
			$out['type'] = ADDRESS_BOOK_ADDRESS_TYPE;
			$out['entity_family_name'] = '';
			$out['number_given_name'] = '';
			$out['per_address_book_id'] = 0;
			$out['main_email'] = '';
			$out['contact_allowed'] = ADDRESS_BOOK_CONTACT_ALLOWED;
			$out['add_new_user'] = ADDRESS_BOOK_ADD_NEW_USER;
			$out['send_new_user_email'] = ADDRESS_BOOK_SEND_USER_EMAIL;
			
			$out['title'] = '';
			$out['middle_names'] = '';
			$out['dob'] = '';
			$out['sex'] = 'not specified';
			
			$out['ent_admin']['same_email'] = 0;
			$out['ent_admin']['per_address_book_id'] = 0;
			$out['ent_admin']['email'] = '';
			$out['ent_admin']['title'] = '';
			$out['ent_admin']['family_name'] = '';
			$out['ent_admin']['given_name'] = '';
			$out['ent_admin']['middle_names'] = '';
			$out['ent_admin']['dob'] = '';
			$out['ent_admin']['sex'] = 'not specified';
			$out['ent_admin']['contact_allowed'] = ADDRESS_BOOK_CONTACT_ALLOWED;
			$out['ent_admin']['add_new_user'] = ADDRESS_BOOK_ADD_NEW_USER;
			$out['ent_admin']['send_new_user_email'] = ADDRESS_BOOK_SEND_USER_EMAIL;
			
		} else {
			$out['type'] = $this->contentValue['type'];
			$out['title'] = isset($this->contentValue['title']) ? $this->contentValue['title'] : '';
			$out['middle_names'] = isset($this->contentValue['middle_names']) ? $this->contentValue['middle_names'] : '';
			$out['dob'] = isset($this->contentValue['dob']) ? date('Y-m-d', strtotime($this->contentValue['dob'])) : '';
			$out['sex'] = isset($this->contentValue['sex']) ? $this->contentValue['sex'] : 'not specified';
			$out['entity_family_name'] = isset($this->contentValue['entity_family_name']) ? $this->contentValue['entity_family_name'] : '';
			$out['number_given_name'] = isset($this->contentValue['number_given_name']) ? $this->contentValue['number_given_name'] : '';
			$out['main_email'] = isset($this->contentValue['main_email']) ? $this->contentValue['main_email'] : '';
			$out['per_address_book_id'] = isset($this->contentValue['per_address_book_id']) ? $this->contentValue['per_address_book_id'] : 0;
			$out['contact_allowed'] = isset($this->contentValue['contact_allowed']) ? $this->contentValue['contact_allowed'] : 0;
			$out['add_new_user'] = isset($this->contentValue['add_new_user']) ? $this->contentValue['add_new_user'] : 0;
			$out['send_new_user_email'] = isset($this->contentValue['send_new_user_email']) ? $this->contentValue['send_new_user_email'] : 0;
			
			$out['ent_admin']['same_email'] = isset($this->contentValue['ent_admin']['same_email']) ? $this->contentValue['ent_admin']['same_email'] : 0;
			$out['ent_admin']['per_address_book_id'] = isset($this->contentValue['ent_admin']['per_address_book_id']) ? $this->contentValue['ent_admin']['per_address_book_id'] : 0;
			$out['ent_admin']['email'] = isset($this->contentValue['ent_admin']['email']) ? $this->contentValue['ent_admin']['email'] : '';
			$out['ent_admin']['title'] = isset($this->contentValue['ent_admin']['title']) ? $this->contentValue['ent_admin']['title'] : '';
			$out['ent_admin']['family_name'] = isset($this->contentValue['ent_admin']['family_name']) ? $this->contentValue['ent_admin']['family_name'] : '';
			$out['ent_admin']['given_name'] = isset($this->contentValue['ent_admin']['given_name']) ? $this->contentValue['ent_admin']['given_name'] : '';
			$out['ent_admin']['middle_names'] = isset($this->contentValue['ent_admin']['middle_names']) ? $this->contentValue['ent_admin']['middle_names'] : '';
			$out['ent_admin']['dob'] = isset($this->contentValue['ent_admin']['dob']) ? $this->contentValue['ent_admin']['dob'] : '';
			$out['ent_admin']['sex'] = isset($this->contentValue['ent_admin']['sex']) ? $this->contentValue['ent_admin']['sex'] : 'not specified';
			$out['ent_admin']['contact_allowed'] = isset($this->contentValue['ent_admin']['contact_allowed']) ? $this->contentValue['ent_admin']['contact_allowed'] : 0;
			$out['ent_admin']['add_new_user'] = isset($this->contentValue['ent_admin']['add_new_user']) ? $this->contentValue['ent_admin']['add_new_user'] : 0;
			$out['ent_admin']['send_new_user_email'] = isset($this->contentValue['ent_admin']['send_new_user_email']) ? $this->contentValue['ent_admin']['send_new_user_email'] : 0;
		}
		
		//set the user email variables

		$this->viewVariables = $out;
		return;
	}
	
	public function checkVariables()
	{
		$errors = array();
		
		if(empty($this->contentValue))
		{
			$msg = 'You can not check something that has no content values at all! ('.$this->contentName.')';
			throw new \RuntimeException($msg);
		}
		
		/* common */
		
		//make sure that all tick boxes that are not visible are 0
		if(!isset($this->contentValue['contact_allowed'])) $this->contentValue['contact_allowed'] = 0;
		if(!isset($this->contentValue['add_new_user'])) $this->contentValue['add_new_user'] = 0;
		if(!isset($this->contentValue['send_new_user_email'])) $this->contentValue['send_new_user_email'] = 0;
		
		if(!isset($this->contentValue['ent_admin']['same_email'])) $this->contentValue['ent_admin']['same_email'] = 0;
		if(!isset($this->contentValue['ent_admin']['contact_allowed'])) $this->contentValue['ent_admin']['contact_allowed'] = 0;
		if(!isset($this->contentValue['ent_admin']['add_new_user'])) $this->contentValue['ent_admin']['add_new_user'] = 0;
		if(!isset($this->contentValue['ent_admin']['send_new_user_email'])) $this->contentValue['ent_admin']['send_new_user_email'] = 0;
		
		//Ensure the correct values if there is no main email address
		if(empty($this->contentValue['main_email']))
		{
			$this->contentValue['per_address_book_id'] = 0;
			$this->contentValue['contact_allowed'] = 0;
			$this->contentValue['add_new_user'] = 0;
			$this->contentValue['send_new_user_email'] = 0;
		}
		
		//check the main email
		if(!empty($this->contentValue['main_email']))
		{
			$main_email_errors = $this->address_book_common->checkMainEmail($this->contentValue['type'],$this->contentValue['main_email']);
			if($main_email_errors['level'] == 'error') $errors['main_email'] = "The main email address failed checks.";
			
			//check the person address book id is valid
			if( $this->contentValue['per_address_book_id'] > 0 )
			{
				//check that it is the right person
				if($this->address_book_db->checkPersonEmail($this->contentValue['main_email']) == $this->contentValue['per_address_book_id'])
				{
					//now set the flags to off so there is no way it can be added as a new user
					$this->contentValue['add_new_user'] = 0;
					$this->contentValue['send_new_user_email'] = 0;
				} else {			
					$errors['main_email_address_book_id'] = 'The stated email address ('.$this->contentValue['main_email'].') does not match a persons address book id ('.$this->contentValue['per_address_book_id'].').';
				}
				
			} else {
				
				if( $this->address_book_db->checkPersonEmail($this->contentValue['main_email']) )
				{
					$errors['main_email'] = "The stated main email address is a persons already in the address book but the id has not been submitted.  Please revalidate the form.";
				}
			}
		} else {
			$this->contentValue['per_address_book_id'] = 0;
			$this->contentValue['contact_allowed'] = 0;
			$this->contentValue['add_new_user'] = 0;
			$this->contentValue['send_new_user_email'] = 0;
		}
		
		if(empty($this->contentValue['add_new_user']) && !empty($this->contentValue['send_new_user_email']))
		{
			$errors['send_new_user_email'] = "You should not have send email set in main if the user is not being added.";
		}

		/* Specific */
	
		//it is an entity or a person
		if($this->contentValue['type'] == 'ent')
		{
			//reset the variables if it is an ent
			$this->contentValue['title'] = '';
			$this->contentValue['middle_names'] = '';
			$this->contentValue['dob'] = '';
			$this->contentValue['sex'] = 'not specified';
			$this->contentValue['add_new_user'] = 0;
			$this->contentValue['send_new_user_email'] = 0;
			
			if(empty($this->contentValue['entity_family_name']))
			{
				$errors['entity_family_name'] = "Entity Name can not be blank.";
			}
			
			if(empty($this->contentValue['per_address_book_id']) && empty($this->contentValue['ent_admin']['per_address_book_id']))
			{	
				if(empty($this->contentValue['ent_admin']['given_name']))
				{
					$errors['ent_admin_given_name'] = "You must enter a given name for the Key Contact Person.";
				}
			}

			if(ADDRESS_BOOK_MAIN_REQUIRE_EMAIL && empty($this->contentValue['main_email']) )
			{
				$errors['main_email'] = "You must supply a main email address to add a new entry.";
			}
			
			if(ADDRESS_BOOK_MAIN_REQUIRE_EMAIL && empty($this->contentValue['ent_admin']['email']) )
			{
				$errors['ent_admin_email'] = "You must supply an email address for the Key Contact Person.";
			}
			
			$score = $this->address_book_common->checkEntity($this->contentValue['entity_family_name'],$this->contentValue['number_given_name']);
			if($score == 3) $errors['entity_entry'] = 'This is a duplicate entry for another entity and can not be added.';
			
			$score = $this->address_book_common->checkPerson($this->contentValue['ent_admin']['family_name'],$this->contentValue['ent_admin']['given_name'],$this->contentValue['ent_admin']['middle_names'],$this->contentValue['ent_admin']['dob'],$this->contentValue['ent_admin']['sex']);
			if($score == 3) $errors['ent_admin'] = 'The key person is a duplicate of an existing individual entry and can not be added.';
			
			if(!empty($this->contentValue['ent_admin']['email']))
			{
				$admin_email_errors = $this->address_book_common->checkAdminEmail($this->contentValue['ent_admin']['email']);
				if($admin_email_errors['level'] == 'error') $errors['ent_admin_email'] = "The key contact person email address failed checks.";
				
				//check the person address book id is valid
				if( $this->contentValue['ent_admin']['per_address_book_id'] > 0 )
				{
					//check that it is the right person
					if($this->address_book_db->checkPersonEmail($this->contentValue['ent_admin']['email']) == $this->contentValue['ent_admin']['per_address_book_id'])
					{
						//now set the flags to off so there is no way it can be added as a new user
						$this->contentValue['ent_admin']['add_new_user'] = 0;
						$this->contentValue['ent_admin']['send_new_user_email'] = 0;
					} else {
						$errors['ent_admin_email_address_book_id'] = "The stated email address does not match a persons address book id.";
					}
				} else {
					if($this->address_book_common->checkPersonEmail($this->contentValue['ent_admin']['email']))
					{
						$errors['ent_admin_email'] = "The stated email address is a persons already in the address book but the required id has not been submitted.  Please revalidate the form.";
					}
				}
			} else if( $this->contentValue['ent_admin']['same_email'] != 1 || empty($this->contentValue['main_email'])) {
				
				$this->contentValue['ent_admin']['per_address_book_id'] = 0;
				$this->contentValue['ent_admin']['contact_allowed'] = 0;
				$this->contentValue['ent_admin']['add_new_user'] = 0;
				$this->contentValue['ent_admin']['send_new_user_email'] = 0;
			}
			
			if(!empty($this->contentValue['ent_admin']['email']) && !empty($this->contentValue['main_email']) && $this->contentValue['ent_admin']['same_email'] )
			{
				if($this->contentValue['ent_admin']['email'] == $this->contentValue['main_email'])
				{
					$this->contentValue['ent_admin']['email'] = '';
				} else {
					$errors['ent_admin_same_email'] = "The same email button has been selected but there are two email addresses.";
				}
			}
			
			if( empty($this->contentValue['ent_admin']['add_new_user']) && $this->contentValue['ent_admin']['send_new_user_email'])
			{
				$errors['ent_admin_send_new_user_email'] = "You should not have send email set if the user is not being added.";
			}

					
		} else {
			
			if(empty($this->contentValue['add_new_user'])) $this->contentValue['send_new_user_email'] = 0;
			if(empty($this->contentValue['send_new_user_email'])) $this->contentValue['send_new_user_email'] = 0;
			
			//reset the values if it is a person
			$this->contentValue['ent_admin']['same_email'] = 0;
			$this->contentValue['ent_admin']['per_address_book_id'] = 0;
			$this->contentValue['ent_admin']['email'] = '';
			$this->contentValue['ent_admin']['title'] = '';
			$this->contentValue['ent_admin']['family_name'] = '';
			$this->contentValue['ent_admin']['given_name'] = '';
			$this->contentValue['ent_admin']['middle_names'] = '';
			$this->contentValue['ent_admin']['dob'] = '';
			$this->contentValue['ent_admin']['sex'] = 'not specified';
			$this->contentValue['ent_admin']['contact_allowed'] = 0;
			$this->contentValue['ent_admin']['add_new_user'] = 0;
			$this->contentValue['ent_admin']['send_new_user_email'] = 0;
			
			if(empty($this->contentValue['number_given_name']))
			{
				$errors['number_given_name'] = "Person's Given Name must not be blank.";
			}
			
			//first check the email
			if(ADDRESS_BOOK_MAIN_REQUIRE_EMAIL && empty($this->contentValue['main_email']))
			{
				$errors['main_email'] = "You must supply a main email address to add a person.";
			}
			
			$score = $this->address_book_common->checkPerson($this->contentValue['entity_family_name'],$this->contentValue['number_given_name'],$this->contentValue['middle_names'],$this->contentValue['dob'],$this->contentValue['sex']);
			if($score == 3) $errors['person_entry'] = 'This is a duplicate person entry and can not be added.';	
		}
		
		if(empty($errors))
		{
			$this->checkedOK = true;
		}
		
		/*
		echo "<pre>";
		print_r($errors);
		print_r($_POST);
		print_r($this->contentValue);
		echo "</pre>";
		die('END');
		*/
		
		return $errors;
	}
		
	public function addInfo($address_book_id)
	{
		if( !$this->checkedOK )
		{
			$msg = 'You must check the data before you attempt to add it!';
			throw new \RuntimeException($msg);
		}
		
		//adding for main data is done in the core_obj
		
		return;
	}
	
}

?>