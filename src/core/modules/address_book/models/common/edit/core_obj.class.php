<?php
namespace core\modules\address_book\models\common\edit;

/**
 * Final core_obj class.
 *
 * Is the actual address book common class where things need to change.
 *
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 December 2016
 */
final class core_obj {
	
	private $_address_book_id = ''; //the address book id
	
	//used to find the view information
	private $_view_base_dir = '/core/modules/address_book/views/common/edit';
	
	//acceptable content
	private $_acceptable_content_array = array('address','internet','main','pots','avatar');
	
	//array of content classes
	private $_content = array();
	
	public function __construct($address_book_id)
	{	
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		if($address_book_db->checkAddressID($address_book_id))
		{
			$this->_address_book_id = $address_book_id;
		} else {
			$msg = "The address book id {$this->_address_book_id} does not exist!";
			throw new \RuntimeException($msg); 
		}
		
		//load up content classes
		$this->_loadContentClassArray();
		
		//address_book_common
		$this->address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				
		return;
	}
	
	/* Load up the content array with all the relevant classes */
	private function _loadContentClassArray()
	{
        if(empty($_POST))
            return;
		$contentNames = array_keys($_POST);
		foreach($contentNames as $contentName)
		{
			if( in_array($contentName, $this->_acceptable_content_array) )
			{
				$className = '\core\modules\address_book\models\common\edit\\'.$contentName;
				$this->_content[$contentName] = new $className($this->_address_book_id);
			}
		}
		
		return;
	}
		
	/* Check Values */
	
	public function checkVariables()
	{	
		if(empty($this->_content))
		{
			$msg = 'Call to check variables with no content classes!';
			throw new \RuntimeException($msg);
		}
		
		$errors = array();
		
		foreach($this->_content as $contentClass)
		{
			$classError_a = $contentClass->checkVariables();
			$errors = array_merge($errors,$classError_a);
		}
		
		return $errors;
	}
	
	/* Add Main Variables */
	
	public function updateAddressBookEntry()
	{
		//check all is ok
		if( $this->_checkAllOk() )
		{
			if( empty($this->_address_book_id) )
			{
				$msg = 'Failed to add New Address Entry.  Address book id was empty!';
				throw new \RuntimeException($msg);
			} else {
				//now add all the rest of the information
				foreach($this->_content as $contentName => $contentClass )
				{
					$contentClass->updateInfo();
				}
			}
			
		} else {
			$msg = 'You must ensure that there are no error in the data first!';
			throw new \RuntimeException($msg);
		}
		
		return $this->_address_book_id;
	}
	
	private function _checkAllOk()
	{
		$out = true;
		
		foreach($this->_content as $contentName => $contentClass )
		{
			if(!$contentClass->checkOK())
			{	
				$out = false;
			}
		}
		
		return $out;
	}
			
	/* Other Address Book Content Get */
	
	public function getContentViewFile($contentName)
	{
		if( empty($this->_content[$contentName]) )
		{
			if(in_array($contentName, $this->_acceptable_content_array))
			{
				$className = '\core\modules\address_book\models\common\edit\\'.$contentName;
				$this->_content[$contentName] = new $className($this->_address_book_id);
				
			} else {
				$msg = "Can not get content view information for {$contentName} because it does not exist!";
				throw new \RuntimeException($msg); 
			}
		}
		
		//set files
		$this->address_book_common->setTerms($this->_view_base_dir,$contentName);
		$this->address_book_common->setJS($this->_view_base_dir,$contentName);
		$this->address_book_common->setCSS($this->_view_base_dir,$contentName);
		
		//view variables
		$this->_content[$contentName]->setVariablesArray();
		$viewVariables = $this->_content[$contentName]->getViewVariables();
		if(!empty($viewVariables)) $this->address_book_common->setViewVariables($viewVariables);
		
		//view switches
		$viewSwitches = $this->_content[$contentName]->getviewSwitches();
		if(!empty($viewSwitches)) $this->address_book_common->setViewSwitches($viewSwitches);
				
		//view file
		$viewFile = DIR_BASE.$this->_view_base_dir.'/'.$contentName.'/'.$contentName.'.php';
		
		if(!is_readable($viewFile))
		{
		    $msg = "Can not find a view file {$viewFile}!";
			throw new \RuntimeException($msg);   
	    } 
	    
		return $viewFile;
	}
	
}
?>