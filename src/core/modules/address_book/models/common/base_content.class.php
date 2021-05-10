<?php
Namespace core\modules\address_book\models\common;
	
abstract class base_content
{
	protected $contentName = '';
	
	protected $contentValue = array(); //the array that holds all the content values set by post or database
	
	protected $viewVariables = array(); //array for view_variables_obj->addViewVariables($key,$value)
	protected $viewSwitches = array(); //array for view_variables_obj->$value() to switch to true
	
	public function __construct()
	{
		if(empty($this->contentName))
		{
			$msg = 'You must not have the content name value blank in the content class!';
			throw new \RuntimeException($msg);
		}
		
		//set link to address book db because they all need it to add, modify and delete
		$this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		return;
	}
	
	//!VIEW STUFF
	
	public function getViewFile()
	{
		return $this->viewFile;
	}
	
	public function getTermsFile()
	{
		return $this->termsFile;
	}
	
	public function getJsHref()
	{
		return $this->jsHref;
	}
	
	public function getCssHref()
	{
		return $this->cssHref;
	}
	
	public function getViewVariables()
	{
		return $this->viewVariables;
	}
	
	public function getViewSwitches()
	{
		return $this->viewSwitches;
	}
	
	public function getValue($contentValueID)
	{
		if(isset($this->contentValue[$contentValueID]))
		{
			$out = $this->contentValue[$contentValueID];
		} else {
			$msg = 'There is no value for this content! ('.$contentValueID.')';
			throw new \RuntimeException($msg);
		}
		return $out;
	}
	
	public function getAllValues()
	{
		return $this->contentValue;
	}
		
}

?>