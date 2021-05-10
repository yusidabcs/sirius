<?php
Namespace core\modules\address_book\models\common\add;

final class avatar extends content
{
	//this name
	protected $contentName = 'avatar';
	
	//protected variables
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array('useSweetAlert','useCroppie'); //array for view_variables_obj->$viewSwitch()
	
	protected $checkedOK = false;
	
	protected $contentValue = array();
	
	public function setVariablesArray()
	{
		$out = array();
		
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
	
	public function addInfo($address_book_id)
	{
		if(empty($_POST['avatar']['avatar_base64'])) return; //no need to do anything if we don't have an image	
			
		//decode
        $data = $_POST['avatar']['avatar_base64'];
        list($type, $data) = explode(';', $data);
        list(,$data) = explode(',', $data);
        $data = base64_decode($data);
	
		if( !$this->checkedOK )
		{
			$msg = 'You must check the data before you attempt to add it!';
			throw new \RuntimeException($msg);
		}
		
		//address_book_common
		$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		$filename = $address_book_common->storeAddressBookFileData($data,$address_book_id);
		
		//set link to address book db because they all need it to add, modify and delete
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		//insert also saves the image in the address book folder
		$affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,'avatar',0);
			
		if($affected_rows != 1)
		{
			$msg = "There was a major issue with addInfo in avatar for address id {$address_book_id}. Affected was {$affected_rows}";
			throw new \RuntimeException($msg);
		}
		
		return;
	}

	
}

?>