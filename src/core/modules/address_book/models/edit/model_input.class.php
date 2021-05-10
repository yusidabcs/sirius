<?php
namespace core\modules\address_book\models\edit;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 4 January 2016
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'edit';
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		//if page is set in options
		if(isset($_POST['address_book_id']) && $_POST['address_book_id'] > 0)
		{
			$address_book_id = $_POST['address_book_id'];
		} else {
			$msg = "No valid address book id given!";
			throw new \RuntimeException($msg);
		}
		
		//include common
		$edit_core = \core\modules\address_book\models\common\edit\core::getInstance($address_book_id);
		$this->errors = array();

		$input_error_array = $edit_core->checkVariables();
		
		if(!empty($input_error_array))
		{	
			$this->errors = array_merge($this->errors,$input_error_array);
			return;
		}
		
		$edit_core->updateAddressBookEntry();

		if(isset($_SESSION['personal'])){
            $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
            $this->menu_register = $menu_register_ns::getInstance();
            $this->redirect = '/'.$this->menu_register->getModuleLink('personal').'/home/'.$_SESSION['personal']['address_book_id'];
        }else{
            $this->redirect = $this->baseURL;
        }
		
	}
}
?>