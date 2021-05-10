<?php
namespace core\modules\interview\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	\core\app\classes\module_base\module_setup
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 August 2019
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'interview';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>