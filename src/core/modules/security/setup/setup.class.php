<?php
namespace core\modules\security\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	\iow\app\classes\module_base\module_setup
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'security';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}	
}
?>