<?php
namespace core\modules\menu\models\edit;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 25 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {
	
	protected $model_name = 'edit';
	
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
		//need Common Menu Object
		$form_check_ns = NS_MODULES.'\\menu\\models\\common\\form_check';
		$form_check = new $form_check_ns();

		//set variables up
		$form_check->setInput();
		
		//process the inputs
		$error_a = $form_check->checkData();
		
		if(!empty($error_a))
		{
			foreach($error_a as $key => $value)
			{
				$this->addError($key,$value);
			}
		}
	
		if($this->hasErrors())
		{
			return;
		}
		
		//update the menu
		$form_check->updateMenuItem();
		
		//redirect	
		$this->redirect = $this->baseURL;
		
		return;
	}
	
}
?>