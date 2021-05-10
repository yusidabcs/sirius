<?php
namespace core\modules\admin\models\module;

/**
 * Final model_input.
 *
 * This is a factory based on the first option.  All sub classes are of interface type admin_main 
 *
 * @final
 * @package 	admin
 *�@author		Martin O'Dee�<martin@iow.com.au>
 *�@copyright	Martin O'Dee�16 October 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'module';	//set by the MODULE!
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		$this->authorize();
		if($_POST['action'] == 'update_modules')
		{
			$module_admin = new \core\modules\admin\models\common\admin_module();
			$module_admin->installUpdateModules($_POST);
		}

		//need to sleep to make sure the page reloads properly
		sleep(2);
		
		$this->redirect = $this->modelURL;
				
		return;
	}
		
}
?>