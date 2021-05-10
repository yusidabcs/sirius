<?php
namespace core\modules\address_book\models\common\view;

/**
 * Final core class.
 *
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 December  2016
 */
final class core_obj {
	
	private $_address_book_id = ''; //the address book id
	
	//used to find the view information
	private $_view_base_dir = '/core/modules/address_book/views/common/view';
	
	//acceptable content
	private $_acceptable_content_array = array('address','internet','main','pots','avatar');

	//js and css index
	private $_index = 500; //should be far enough away from all the others
	
	
	public function __construct($address_book_id)
	{	
		//set link to address book db because they all need it to add, modify and delete
		$this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		
		$this->address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
		
		if(empty($address_book_id))
		{
			$msg = "The address book id {$this->_address_book_id} is not valid!";
			throw new \RuntimeException($msg); 
		} else {
			if($this->address_book_db->checkAddressID($address_book_id))
			{
				$this->_address_book_id = $address_book_id;
			} else {
				$msg = "The address book id {$this->_address_book_id} does not exist!";
				throw new \RuntimeException($msg); 
			}
		} 
					
		return;
	}
						
	/* Other Address Book Content Get */
	
	public function getContentViewFile($contentName)
	{
		if(in_array($contentName, $this->_acceptable_content_array))
		{
			$className = '\core\modules\address_book\models\common\view\\'.$contentName;
			$content = new $className($this->_address_book_id);
			
		} else {
			$msg = "Can not get content view information for {$contentName} because it does not exist!";
			throw new \RuntimeException($msg); 
		}
		
		$this->address_book_common->setTerms($this->_view_base_dir,$contentName);
		$this->address_book_common->setJS($this->_view_base_dir,$contentName);
		$this->address_book_common->setCSS($this->_view_base_dir,$contentName);
		
		//view variables
		$content->setVariablesArray();
		
		$viewVariables = $content->getViewVariables();
		
		if(!empty($viewVariables)) $this->address_book_common->setViewVariables($viewVariables);
		
		//view switches
		$viewSwitches = $content->getviewSwitches();
		
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