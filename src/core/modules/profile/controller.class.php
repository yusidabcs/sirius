<?php
namespace core\modules\profile;

/**
 * Final controller class.
 * 
 * Controller for the profile module
 *
 * @final
 * @extends 	module_controller
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 July 2017
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