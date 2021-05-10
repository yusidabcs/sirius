<?php
Namespace core\modules\address_book\models\common\edit;
	
abstract class content extends \core\modules\address_book\models\common\base_content
{		
	protected $contentName = ''; //the name of the actual model being used
	protected $address_book_id = ''; //the address book id for this object
	protected $contentValue; //the array holding the content values
	protected $checkedOK = false;
	
	public function __construct($address_book_id)
	{
		parent::__construct();
		
		if(empty($address_book_id))
		{
			$msg = "Address id of {$address_book_id} is not a valid common edit value.";
			throw new \RuntimeException($msg);
		} else {
			$this->address_book_id = $address_book_id;
			$this->setContent();
		}
		
		return;
	}
	
	public function checkOK()
	{
		return $this->checkedOK;
	}
	
	abstract protected function setContent();
	
	abstract public function setVariablesArray();
	
	abstract public function checkVariables();
	
	abstract public function updateInfo();	

}
?>