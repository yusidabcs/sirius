<?php
namespace core\modules\send_email;

/**
 * Final controller class.
 * 
 * Controller for the send_email module
 *
 * @final
 * @extends 	module_controller
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
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