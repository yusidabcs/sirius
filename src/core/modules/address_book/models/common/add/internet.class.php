<?php
Namespace core\modules\address_book\models\common\add;

final class internet extends content
{
	//this name
	protected $contentName = 'internet';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	//acceptale internet options
	private $type_array = array('skype','facebook','youtube-video','youtube-channel','twitter','linked-in','google-plus','instagram');
	
	public function setVariablesArray()
	{
		$out = array();
		
		//set the defaults
		$out['internet'] = array();
		$out['internet_default_type'] = ADDRESS_BOOK_DEFAULT_INTERNET_TYPE;
			
		if(!empty($this->contentValue))
		{
			$out['internet'] = $this->contentValue;
		}
		
		$this->viewVariables = $out;
		return;
	}
	
	public function checkVariables()
	{
		if(empty($this->contentValue))
		{
			$msg = 'You can not check something that has no content values at all! ('.$this->contentName.')';
			throw new \RuntimeException($msg);
		}
		
		$errors = array();
		
		$count = 1;
		$newValues = array();
		
		foreach($this->contentValue as $key => $value)
		{
			if( empty($value['id']) || $key === '{X}') continue;
			
			$newValues[$count] = array('type'=>$value['type'],'id'=>$value['id']);
			
			$count++;
		}
		
		$this->contentValue = $newValues;
		
		if(empty($errors))
		{
			$this->checkedOK = true;
		}
		
		return $errors;
	}
		
	public function addInfo($address_book_id)
	{
		if( !$this->checkedOK )
		{
			$msg = 'You must check the data before you attempt to add it!';
			throw new \RuntimeException($msg);
		}
		
		foreach($this->contentValue as $sequence => $value)
		{
			$affected_rows = $this->address_book_db->insertAddressBookInternet($address_book_id,$value['type'],$value['id'],$sequence);
			
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in interent for address id {$address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
		}
		
		return;
	}

	
}

?>