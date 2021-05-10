<?php
namespace core\modules\menu;

/**
 * Final controller class.
 * 
 * Controller for the menu module
 *
 * @final
 * @extends module_controller
 * @package menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
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