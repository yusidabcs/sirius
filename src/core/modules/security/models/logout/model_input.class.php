<?php
namespace core\modules\security\models\logout;

/**
 * Final logout_model_input class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {
	
	protected $model_name = 'logout';
	
	//my variables
	private $_allowLogin = false;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}

    /**
     * handle logout request, clear all session and redirect to base url
     */
    protected function processPost()
	{
		if(isset($_POST['logout']) && $_POST['logout'] == 'go_now')
		{
			session_destroy();
			setcookie('PHPSESSID', '', time() - 3600);
			$this->redirect = '/';
		}
		return;
	}
	
}
?>