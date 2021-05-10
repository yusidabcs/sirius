<?php
Namespace core\modules\address_book\models\common\edit;

final class internet extends content
{
	//this name
	protected $contentName = 'internet';
	protected $address_book_id = ''; //the address book id for this object
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	//acceptale internet options
	private $type_array = array('skype','facebook','youtube-video','youtube-channel','twitter','linked-in','google-plus','instagram');
	
	protected function setContent()
	{
		if(!empty($_POST[$this->contentName]))
		{
			$this->contentValue = $_POST[$this->contentName];
			
		} else {
			
			$currentDataArray = $this->address_book_db->getAddressBookInternetDetails($this->address_book_id);
			
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
			if( empty($value['id']) || $key === '{X}') continue;
			
			$newValues[$count] = array('type'=>$value['type'],'id'=>$value['id']);
			
			$count++;
		}
		
		$this->contentValue = $newValues;
		
		return;
	}
	
	public function setVariablesArray()
	{
		$out = array();
		
		//set the defaults
		$out['internet'] = $this->contentValue;
		$out['internet_default_type'] = ADDRESS_BOOK_DEFAULT_INTERNET_TYPE;
	
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
		$this->address_book_db->deleteAddressBookInternetAll($this->address_book_id);
		
		foreach($this->contentValue as $sequence => $value)
		{
			$affected_rows = $this->address_book_db->insertAddressBookInternet($this->address_book_id,$value['type'],$value['id'],$sequence);
			
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