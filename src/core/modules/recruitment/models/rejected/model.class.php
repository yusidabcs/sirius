<?php
namespace core\modules\recruitment\models\rejected;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'rejected';
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
		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		$recruitment_db = new \core\modules\recruitment\models\common\db;
		$entity = '';
		
		if(isset($_SESSION['entity'])){
			$entity = $_SESSION['entity'];
			$this->partners = [];
		}else{
			$this->partners = $recruitment_db->getListPartner();	
		}

		$this->list_status = ['unverified','request','process','verified','rejected'];
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('rejected');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();

		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);

		$this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
		$this->view_variables_obj->addViewVariables('partners',$this->partners);
		$this->view_variables_obj->addViewVariables('list_status',$this->list_status);
		$this->view_variables_obj->addViewVariables('add_ab_link',$this->menu_register->getModuleLink('address_book').'/add');

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