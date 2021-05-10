<?php
namespace iow\modules\xMODULEx;

/**
 * Final controller class.
 * 
 * Controller for the xMODULEx module
 *
 * @final
 * @extends		module_controller
 * @package		xMODULEx
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee xDATEx
 */
final class controller extends \iow\app\classes\module_base\module_controller {
	
	protected $commonNav = false;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
}
?>