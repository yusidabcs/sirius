<?php
namespace core\modules\address_book\models\add;

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

		$ab_id = $add_core->addNewAddressBookEntry();
		if($this->useEntity)
            $add_core->insertAddressBookConnection($ab_id, $this->entity['address_book_ent_id']);

		//check if there is redirect option, and check if in allowerd redirect		
		if ( isset($_POST['redirect_to'])  && in_array($_POST['redirect_to'],['ab','rec']) )
		{
			$redirect_to = $_POST['redirect_to'];
			if ($redirect_to == 'rec')
			{
				$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
				$this->menu_register = $menu_register_ns::getInstance();
				$this->redirect = '/'.$this->menu_register->getModuleLink('recruitment').'/candidate';
			}else if ($redirect_to == 'ab'){
				$this->redirect = $this->baseURL;
			}
		}else{
			$this->redirect = $this->baseURL;
		}
		return;
	}
}
?>