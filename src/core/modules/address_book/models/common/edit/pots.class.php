<?php
Namespace core\modules\address_book\models\common\edit;

final class pots extends content
{
	//this name
	protected $contentName = 'pots';
	protected $address_book_id = ''; //the address book id for this object
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	protected function setContent()
	{
		if(!empty($_POST[$this->contentName]))
		{
			$this->contentValue = $_POST[$this->contentName];
			
		} else {
			
			$currentDataArray = $this->address_book_db->getAddressBookPotsDetails($this->address_book_id);
			
			if(empty($currentDataArray))
			{
				$this->contentValue = array();		
			} else {
				//main address
				$this->contentValue = $currentDataArray;
			}
		}
		
		$count = 1;
		$newValues = array();
		
		foreach($this->contentValue as $key => $value)
		{
			if( empty($value['number']) || $key === '{X}') continue;
			
			$private = empty($value['private']) ? 0 : 1;
			$whatsapp = empty($value['whatsapp']) ? 0 : 1;
			$viber = empty($value['viber']) ? 0 : 1;
			
			$newValues[$count] = array('type'=>$value['type'],'country'=>$value['country'],'number'=>$value['number'],'private'=>$private,'whatsapp'=>$whatsapp,'viber'=>$viber);
			
			$count++;
		}
	
		$this->contentValue = $newValues;
		
		return;	
	}
	
	public function setVariablesArray()
	{
		$out = array();
		
		//set the defaults
		$out['pots'] = $this->contentValue;
		$out['pots_default_type'] = ADDRESS_BOOK_DEFAULT_POTS_TYPE;
		$out['pots_default_country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
		
		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$out['countryDialCodes'] = $core_db->getAllDialCodes();
			
		$this->viewVariables = $out;
		return;
	}
	
	public function checkVariables()
	{
		$errors = array();
		
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
		
		//delete whatever is there first
		$this->address_book_db->deleteAddressBookPotsAll($this->address_book_id);
		
		foreach($this->contentValue as $sequence => $value)
		{
			$affected_rows = $this->address_book_db->insertAddressBookPots($this->address_book_id,$value['type'],$value['country'],$value['number'],$value['private'],$value['whatsapp'],$value['viber'],$sequence);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with updateInfo in interent for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
		}
		
		return;
	}
	
}

?>