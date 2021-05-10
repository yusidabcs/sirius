<?php
namespace iow\modules\xMODULEx\models\home;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package		xMODULEx
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee xDATEx
 */
final class model_input extends \iow\app\classes\module_base\module_model_input {

	protected $model_name = 'home';
	
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
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		die('END');
		
		return;
	}
}
?>