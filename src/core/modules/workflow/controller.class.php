<?php
namespace core\modules\workflow;

/**
 * Final controller class.
 * 
 * Controller for the workflow module
 *
 * @final
 * @extends		module_controller
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class controller extends \core\app\classes\module_base\module_controller {
	
	protected $commonNav = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
}
?>