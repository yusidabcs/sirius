<?php
namespace core\modules\finance;

/**
 * Final controller class.
 * 
 * Controller for the finance module
 *
 * @final
 * @extends		module_controller
 * @package		finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
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