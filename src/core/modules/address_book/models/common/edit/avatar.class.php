<?php
Namespace core\modules\address_book\models\common\edit;

final class avatar extends content
{
	//this name
	protected $contentName = 'avatar';
	protected $address_book_id = ''; //the address book id for this object
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert','useCroppie'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	protected function setContent()
	{
		if(!empty($_POST[$this->contentName]))
		{
			$this->contentValue = $_POST[$this->contentName];
			
		}
		
		return;
	}
	
	public function setVariablesArray()
	{
		$out = array();
		$out['avatar'] = $this->address_book_db->getAddressBookFileArray($this->address_book_id,'avatar');

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
			$msg = 'You must check the data before you attempt to update it!';
			throw new \RuntimeException($msg);
		}
		
		if(empty($this->contentValue['avatar_base64'])) return; //no need to do anything if we don't have an image	
		
		//decode
        $data = $this->contentValue['avatar_base64'];
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$this->address_book_id);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if(empty($this->contentValue['current']))
		{
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->insertAddressBookFile($filename,$this->address_book_id,'avatar',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in avatar for address id {$this->address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			//delete the current avatar
			$address_book_common->deleteAddressBookFile($this->contentValue['current'],$this->address_book_id); 
			
			//insert also saves the image in the address book folder
			$affected_rows = $address_book_db->updateAddressBookFile($filename,$this->address_book_id,'avatar',0);
				
			if($affected_rows != 1)
			{
				$msg = "There was a major issue with addInfo in avatar for address id {$this->address_book_id}. Affected was {$affected_rows}";
				throw new \RuntimeException($msg);
			}
		}
		
		return;
	}

}

?>