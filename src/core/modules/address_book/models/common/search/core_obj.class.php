<?php
namespace core\modules\address_book\models\common\search;

/**
 * Final core_obj class.
 *
 * Is the actual address book common class where things need to change.
 *
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 5 January 2016
 */
final class core_obj {
	
	//used to find the view information
	private $_view_base_dir = '/core/modules/address_book/views/common/search';
	
	//acceptable content
	private $_acceptable_content_array = array('main');
	
	//array of content classes
	private $_content = array();
	
	public function __construct($link_id,$model_name,$page)
	{
		//set the globals
		$menu_register = \core\app\classes\menu_register\menu_register::getInstance();
		if( !$menu_register->checkLink($link_id) )
	    {
	    	$msg = "Bad link id ({$link_id}) when trying to form core address book search!";
			throw new \RuntimeException($msg);
	    }
		
		$this->link_id = $link_id;
		$this->model_name = $model_name;
		$this->page = $page;
		
		//address_book_common
		$this->address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				
		return;
	}
	
	public function getContentViewFile($contentName)
	{
		if( empty($this->_content[$contentName]) )
		{
			if(in_array($contentName, $this->_acceptable_content_array))
			{
				$className = '\core\modules\address_book\models\common\search\\'.$contentName;
				$this->_content[$contentName] = new $className($this->link_id,$this->model_name,$this->page);
				
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