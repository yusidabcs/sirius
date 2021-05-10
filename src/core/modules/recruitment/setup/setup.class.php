<?php
namespace core\modules\recruitment\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'recruitment';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>