<?php
namespace core\modules\job_application\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 July 2017
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'job_application';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>