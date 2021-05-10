<?php
Namespace core\modules\address_book\models\common\add;
	
abstract class content extends \core\modules\address_book\models\common\base_content
{	
	protected $contentName = ''; //the name of the actual model being used
	protected $contentValue; //the array holding the content values
	protected $checkedOK = false; //true means that the check has been done
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_loadContentValueArray(); //load the content objects
		
		return;
	}
	
	/* Load up the content array with classes */
	private function _loadContentValueArray()
	{
		if(!empty($_POST[$this->contentName]))
		{
			$this->contentValue = $_POST[$this->contentName];
		}
		return;
	}

	public function checkOK()
	{
		return $this->checkedOK;
	}
	
	abstract public function setVariablesArray();
	
	abstract public function checkVariables();
	
	abstract public function addInfo($address_book_id);	

}

?>