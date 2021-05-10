<?php
namespace core\modules\offer_letter\admin;

/**
 * Final admin class.
 * 
 * A class that deletes items when the menu item is deleted
 *
 * @final
 * @extends		module_admin
 * @package 	offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
 */
final class admin extends \core\app\classes\module_base\module_admin {

	public function __construct()
	{
		parent::__construct();
		return;
	}
	
}
?>