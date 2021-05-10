<?php
namespace core\modules\personal\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'personal';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>