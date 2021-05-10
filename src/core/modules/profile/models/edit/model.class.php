<?php
namespace core\modules\profile\models\edit;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'edit';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		//if page is set in options
		if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0)
		{
			$user_id = $_SESSION['user_id'];
			
			//Get all the user information
			$user_db = new \core\modules\user\models\common\user_db;
			
			$user_info = $user_db->selectUserDetails($user_id);
			
			$user_info = $user_info[$user_id];

			//convert to an address book id if there is one
			$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
			
			$address_book_id = $address_book_db->getPersonhAddressBookIdFromEmail($user_info['email']);
			
			//if there is no address book entry then we need to add one
			if(empty($address_book_id))
			{
				$this->redirect = $this->baseURL.'/add';
				return;
			}
			
		} else {
			$msg = "Wow you should never see this error ... very bad attempt to edit!";
			throw new \RuntimeException($msg);
		}	
		
		//include common
		$edit_core = \core\modules\address_book\models\common\edit\core::getInstance($address_book_id);
		
		//main file
		$this->main_file = $edit_core->getContentViewFile('main');
		
		//address file
		$this->address_file = $edit_core->getContentViewFile('address');
		
		//pots file
		$this->pots_file = $edit_core->getContentViewFile('pots');
		
		//internet file
		$this->internet_file = $edit_core->getContentViewFile('internet');
		
		//avatar file
		$this->avatar_file = $edit_core->getContentViewFile('avatar');

		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('edit');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//include
		$this->view_variables_obj->addViewVariables('main_file',$this->main_file);
		$this->view_variables_obj->addViewVariables('address_file',$this->address_file);
		$this->view_variables_obj->addViewVariables('pots_file',$this->pots_file);
		$this->view_variables_obj->addViewVariables('internet_file',$this->internet_file);
		$this->view_variables_obj->addViewVariables('avatar_file',$this->avatar_file);
		
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		
		if($this->input_obj)
		{
			if($this->input_obj->hasErrors())
			{
				$this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
			}
			
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
		}
		return;
	}
		
}
?>