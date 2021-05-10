<?php
namespace core\modules\education\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	education
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'education';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>