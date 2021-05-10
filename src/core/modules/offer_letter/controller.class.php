<?php
namespace core\modules\offer_letter;

/**
 * Final controller class.
 * 
 * Controller for the offer_letter module
 *
 * @final
 * @extends		module_controller
 * @package		offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
 */
final class controller extends \core\app\classes\module_base\module_controller {
	
	protected $commonNav = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
}
?>