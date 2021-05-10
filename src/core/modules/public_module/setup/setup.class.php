<?php
namespace core\modules\cv\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	\core\app\classes\module_base\module_setup
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 August 2019
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'cv';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>