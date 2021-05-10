<?php
namespace core\modules\address_book;

/**
 * Final controller class.
 * 
 * Controller for the address_book module
 *
 * @final
 * @extends 	module_controller
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class controller extends \core\app\classes\module_base\module_controller {
	protected $commonNav = false;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
}
?>