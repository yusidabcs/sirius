<?php
Namespace core\modules\address_book\models\common\add;

final class pots extends content
{
	//this name
	protected $contentName = 'pots';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	public function setVariablesArray()
	{
		$out = array();
		
		//set the defaults
		$out['pots'] = array();
		$out['pots_default_type'] = ADDRESS_BOOK_DEFAULT_POTS_TYPE;
		$out['pots_default_country'] = ADDRESS_BOOK_DEFAULT_COUNTRY_CODE;
			
		if(!empty($this->contentValue))
		{
			$out['pots'] = $this->contentValue;
		}
		
		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$out['countryDialCodes'] = $core_db->getAllDialCodes();
			
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
			if( empty($value['number']) || $key === '{X}') continue;
			
			$private = empty($value['private']) ? 0 : 1;
			$whatsapp = empty($value['whatsapp']) ? 0 : 1;
			$viber = empty($value['viber']) ? 0 : 1;
			
			$newValues[$count] = array('type'=>$value['type'],'country'=>$value['country'],'number'=>$value['number'],'private'=>$private,'whatsapp'=>$whatsapp,'viber'=>$viber);
			
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
			$affected_rows = $this->address_book_db->insertAddressBookPots($address_book_id,$value['type'],$value['country'],$value['number'],$value['private'],$value['whatsapp'],$value['viber'],$sequence);
			
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