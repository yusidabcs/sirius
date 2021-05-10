<?php
namespace core\modules\workflow\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'workflow';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>