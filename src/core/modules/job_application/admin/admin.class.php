<?php
namespace core\modules\job_application\admin;

/**
 * Final admin class.
 * 
 * A class that deletes items when the menu item is deleted
 *
 * @final
 * @extends		module_admin
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 July 2017
 */
final class admin extends \core\app\classes\module_base\module_admin {

	public function __construct()
	{
		parent::__construct();
		return;
	}
	
}
?>