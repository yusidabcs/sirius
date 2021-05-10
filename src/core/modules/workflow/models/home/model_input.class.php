<?php
namespace core\modules\workflow\models\home;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

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