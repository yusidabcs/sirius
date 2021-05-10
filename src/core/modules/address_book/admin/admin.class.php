<?php
namespace core\modules\address_book\admin;

/**
 * Final admin class.
 * 
 * A class that deletes items when the menu item is deleted
 *
 * @final
 * @extends		module_admin
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class admin extends \core\app\classes\module_base\module_admin {

	public function __construct()
	{
		parent::__construct();
		return;
	}
	
}
?>