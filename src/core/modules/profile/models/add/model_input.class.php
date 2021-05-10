<?php
namespace core\modules\profile\models\add;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'add';
	
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
		//include common
		$add_core = \core\modules\address_book\models\common\add\core::getInstance();
		$this->errors = array();

		$input_error_array = $add_core->checkVariables();
		
		if(!empty($input_error_array))
		{	
			$this->errors = array_merge($this->errors,$input_error_array);
			return;
		}
				
		$add_core->addNewAddressBookEntry();
		
		$this->redirect = $this->baseURL;
		
		return;
	}
}
?>