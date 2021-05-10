<?php
namespace core\modules\reference_check\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'reference_check';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>