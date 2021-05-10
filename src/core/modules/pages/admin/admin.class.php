<?php
namespace core\modules\pages\admin;

/**
 * Final admin class.
 * 
 * A class that deletes or updates items when the menu item is deleted or changed
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 27 August 2019
 */
final class admin extends \core\app\classes\module_base\module_admin {

	protected $runMenuDelete = true;
	protected $runMenuUpdate = true;
	
	private $_pages_db;
	
	public function __construct()
	{
		//we need the database
		$pages_db_ns = NS_MODULES.'\\pages\\models\\common\\db';
		$this->_pages_db = new $pages_db_ns();
		
		parent::__construct();
		return;
	}
		
	protected function deleteMenuLinkId($link_id)
	{
		//delete all pages and sub-pages (if any)
		$this->_pages_db->deletePage($link_id);
		return;
	}
	
	protected function updateMenuLinkId($to_link_id,$from_link_id)
	{
		$this->_pages_db->updateLinkIds($to_link_id,$from_link_id);
		return;
	}
	
		
}
?>