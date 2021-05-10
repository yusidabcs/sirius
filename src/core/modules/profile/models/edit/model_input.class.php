<?php
namespace core\modules\profile\models\edit;

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

	protected $model_name = 'edit';
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{	
		$this->authorize();
		//if page is set in options
		if(isset($_POST['address_book_id']) && $_POST['address_book_id'] > 0)
		{
			$address_book_id = $_POST['address_book_id'];
		} else {
			$msg = "No valid address book id given!";
			throw new \RuntimeException($msg);
		}

		if (!empty($_POST['avatar']['avatar_base64']) && !empty($_POST['address']['main']['line_1']) && count($_POST['pots']) > 0) {
			
			$address_book_common = new \core\modules\address_book\models\common\address_book_common_obj;
			$address_book = $address_book_common->getAddressBookMainDetails($address_book_id);

			$this->_moveCollection($address_book['main_email'], 'personal_not_complete');
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

		$this->addMessage('Profile','Succcessfull update profile.');
		
		$this->redirect = $this->baseURL;
		
	}

	private function _moveCollection($email, $collection_name)
	{
		$mailing_common = new \core\modules\send_email\models\common\common;

		$mailing_common->moveSubscriberToCollection('profile_not_complete', $collection_name, $email);
	}

}
?>