<?php
namespace core\modules\offer_letter\setup;

/**
 * Final setup class.
 *
 * @final
 * @extends 	module_setup
 * @package 	offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
 */
final class setup extends \core\app\classes\module_base\module_setup {
	
	protected $module = 'offer_letter';
	
	public function __construct()
	{	
		parent::__construct();
		return;
	}
	
}
?>