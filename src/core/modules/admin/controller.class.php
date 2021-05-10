<?php
namespace core\modules\admin;

/**
 * Final controller class.
 * 
 * Controller for the secuity module
 *
 * @final
 * @extends module_controller
 * @package admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 August 2019
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