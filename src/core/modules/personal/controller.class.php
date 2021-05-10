<?php
namespace core\modules\personal;

/**
 * Final controller class.
 * 
 * Controller for the personal module
 *
 * @final
 * @extends 	module_controller
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
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