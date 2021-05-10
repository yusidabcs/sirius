<?php
namespace core\modules\user;

/**
 * Final controller class.
 * 
 * Controller for the secuity module
 *
 * @final
 * @extends 	module_controller
 * @package 	user
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
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