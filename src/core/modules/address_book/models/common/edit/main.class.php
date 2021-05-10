<?php
Namespace core\modules\address_book\models\common\edit;

final class main extends content
{
	//this name
	protected $contentName = 'main';
	protected $address_book_id = ''; //the address book id for this object
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert','useFlatpickr'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	protected $contentValue = array();
	protected $type_array = array('ent','per');
	
	//check name or not
	private $_checkEmail = false;
	private $_checkName = false;
	
	public function __construct($address_book_id)
	{
		parent::__construct($address_book_id);
		
		//set link to address book db because they all need it to add, modify and delete
		$this->address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				
		return;
	}
	
	protected function setContent()
	{
		$currentDataArray = $this->address_book_db->getAddressBookMainDetails($this->address_book_id);
		
		if($currentDataArray['type'] == 'ent')
		{
			$currentDataArray['ent_admin_details'] = $this->address_book_db->getAddressBookAdminLinks($this->address_book_id);
		} else {
			$currentDataArray['ent_admin_details'] = array();
		}
		
		if(!empty($_POST['address_book_id']))
		{
			$this->contentValue = $_POST[$this->contentName];
		    $this->contentValue['type'] = $currentDataArray['type'];  // this is disabled in the form so it will never be posted
		   
		    //!we need to fix this up.  At the moment it is disabled and that means it can not be changed by the user ... but I will fix that in due course so the user can change it
			
			// if change email not checked, don't change user email
			if (!isset($this->contentValue['change_email'])) {
				$this->contentValue['main_email'] = $currentDataArray['main_email']; // this is disabled in the form so it will never be posted
			}


		    $this->contentValue['contact_allowed'] = isset($_POST['main']['contact_allowed']) ? $_POST['main']['contact_allowed'] : 0;
		    
		    //if it is an entity then get rid of the stupid values
		    if($this->contentValue['type'] == 'ent')
		    {
			    $this->contentValue['title'] = '';
			    $this->contentValue['middle_names'] = '';
			    $this->contentValue['dob'] = '';
			    $this->contentValue['sex'] = 'not specified';
		    }
		    
		    if( empty($this->contentValue['main_email']))
		    {
			    $this->contentValue['contact_allowed'] = 0;
		    }
		    
			//check if we need to handle email or not
			if($this->contentValue['main_email'] != $currentDataArray['main_email'])
			{
				$this->_checkEmail = true;
			}
			
			//check if we have to handle the name change or not
			if($this->contentValue['type'] == 'per')
			{
				if(	$this->contentValue['entity_family_name'] != $currentDataArray['entity_family_name'] || $this->contentValue['number_given_name'] != $currentDataArray['number_given_name'] || $this->contentValue['middle_names'] != $currentDataArray['middle_names'] || $this->contentValue['dob'] != $currentDataArray['dob'] || $this->contentValue['sex'] != $currentDataArray['sex'] )
				{
					$this->_checkName = true;
				}
				
			} else {
				//$this->address_book_common->checkEntity($this->contentValue['entity_family_name'],$this->contentValue['number_given_name']);
				if(	$this->contentValue['entity_family_name'] != $currentDataArray['entity_family_name'] || $this->contentValue['number_given_name'] != $currentDataArray['number_given_name'] )
				{
					$this->_checkName = true;
				}
			}
						
		} else {
			
			if(empty($currentDataArray))
			{
				$msg = "There was a major issue main edit because there was no current data!";
				throw new \RuntimeException($msg);
			} else {
				//main address
				$this->contentValue = $currentDataArray;
			}
		}
	}
	
	public function setVariablesArray()
	{
		$out = array();
		
		//set the variables from content
		$out = $this->contentValue;
		
		//values for date of birth picker
		$min_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MAX_AGE );
		$max_date = mktime( date("H"), date("i"), date("s"), date("m"), date("d"), date("Y") - ADDRESS_BOOK_ADDRESS_DOB_MIN_AGE );
		
		$out['dob_min'] = date('c', $min_date);
		$out['dob_max'] = date('c', $max_date);
		
		//address book id to exclude this entry from equivalance tests
		$out['address_book_id'] = $this->address_book_id;
		
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
		
		//reset the variables if it is an ent
		if($this->contentValue['type'] == 'ent')
		{
			$this->contentValue['title'] = '';
			$this->contentValue['middle_names'] = '';
			$this->contentValue['dob'] = '';
			$this->contentValue['sex'] = 'not specified';
		}
				
		//first check the email
		if($this->_checkEmail)
		{
			$errors = $this->address_book_common->checkEmail($this->contentValue['main_email']);

			$user_db = new \core\modules\user\models\common\user_db;
			$duplicateEmail = $user_db->checkEmailInUse($this->contentValue['main_email']);

			if ($duplicateEmail) {
				$errors['user_email'] = 'Email already been taken.';
			}
		}
		
		//first check the email
		if(ADDRESS_BOOK_MAIN_REQUIRE_EMAIL && empty($this->contentValue['main_email']))
		{
			$errors['main_email'] = "You must supply a main email address to add a new entry.";
		}
		
		//check the main details
		if($this->_checkName)
		{
			if($this->contentValue['type'] == 'per')
			{
				$score = $this->address_book_common->checkPerson($this->contentValue['entity_family_name'],$this->contentValue['number_given_name'],$this->contentValue['middle_names'],$this->contentValue['dob'],$this->contentValue['sex']);
				if($score == 3) $errors['person_entry'] = 'This is a duplicate person entry and can not be added.';
			} else {
				$score = $this->address_book_common->checkEntity($this->contentValue['entity_family_name'],$this->contentValue['number_given_name']);
				if($score == 3) $errors['entity_entry'] = 'This is a duplicate entity entry and can not be added.';
			}
		}
		
		if(empty($errors))
		{
			$this->checkedOK = true;
		}
		
		return $errors;
	}
		
	public function updateInfo()
	{
		if( !$this->checkedOK )
		{
			$msg = 'You must check the data before you attempt to add it!';
			throw new \RuntimeException($msg);
		}
		
		//update main
		$affected_rows = $this->address_book_db->updateMainAddressBookEntry($this->address_book_id,$this->contentValue['main_email'],$this->contentValue['type'],$this->contentValue['entity_family_name'],$this->contentValue['number_given_name'],$this->contentValue['contact_allowed']);
					
		if($affected_rows == 0)
		{
			$msg = "There was a major issue with updateInfo main details in main for address id {$this->address_book_id}. Affected was {$affected_rows}";
			throw new \RuntimeException($msg);
		}

		if($this->contentValue['type'] == 'per')
		{		
			//update person
			$affected_rows = $this->address_book_db->updateAddressBookPer($this->address_book_id,$this->contentValue['title'],$this->contentValue['middle_names'],$this->contentValue['dob'],$this->contentValue['sex']);
			
			if($affected_rows == 0)
			{
				$msg = "There was a major issue with updateInfo person in main for address id {$this->address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
		}

		if (isset($this->contentValue['change_email'])) {
			
			if ($this->contentValue['change_email'] == 1) {
				# code...
				$user_db = new \core\modules\user\models\common\user_db;
				$user_db->updateUser($_SESSION['user_id'],'email',$this->contentValue['main_email']);
			}
			
		}
		
		return;
	}
	
}

?>