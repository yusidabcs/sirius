<?php
namespace core\modules\reference_check;

/**
 * Final controller class.
 * 
 * Controller for the register module
 *
 * @final
 * @extends 	module_controller
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
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