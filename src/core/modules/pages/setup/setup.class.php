<?php
namespace core\modules\pages\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	\iow\app\classes\module_base\module_setup
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'pages';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>