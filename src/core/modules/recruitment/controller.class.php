<?php
namespace core\modules\recruitment;

/**
 * Final controller class.
 * 
 * Controller for the recruitment module
 *
 * @final
 * @extends		module_controller
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
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