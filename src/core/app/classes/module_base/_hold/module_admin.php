<?php
namespace iow\app\classes\module_base;
 
/**
 * Abstract  module_admin class.
 * 
 * Is the base class for all module menu delete options
 * - Normally, with most modules that can not have multiple pages, absolutely nothing happens
 * - So it is only relevent to modules, like Pages, Items, Categories, where multiples are possible.
 *
 * @abstract
 * @package 	module_setup
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 January 2015
 */

abstract class module_admin {
	
	protected $runMenuDelete = false; //normally don't even worry about it
	protected $runMenuUpdate = false; //normally don't even worry about it
	
	public function __construct()
	{
		$this->_menu_register = \iow\app\classes\menu_register\menu_register::getInstance();
		return;
	}
	
	protected function checkMenuLinkId($link_id)
	{
		return $this->_menu_register->linkIsReal($link_id);
	}
	
	public function deleteMenuLink($link_id)
	{
		if($this->runMenuDelete)
		{
			//check link info
			if($this->checkMenuLinkId($link_id))
			{
				$this->deleteMenuLinkId($link_id);
			}
		}
		return;
	}
	
	protected function deleteMenuLinkId($link_id)
	{
		//normally there is nothing that needs to be done when the link is deleted in the menu
		return;
	}
	
	public function updateMenuLink($link_id_new, $link_id_orig)
	{
		if($this->runMenuUpdate)
		{
			//check link info
			if($link_id_new != $link_id_orig && !$this->checkMenuLinkId($link_id_new) && $this->checkMenuLinkId($link_id_orig) )
			{
				$this->updateMenuLinkId($link_id_new, $link_id_orig);
			}
		}
		return;
	}
	
	protected function updateMenuLinkId($link_id_new, $link_id_orig)
	{
		//normally there is nothing to update when the link changes in the menu
		return;
	}
	
}
?>