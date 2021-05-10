<?php
namespace core\modules\offer_letter\models\home;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package		offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
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