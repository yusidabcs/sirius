<?php
namespace core\modules\recruitment\models\candidate;

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

	protected $model_name = 'candidate';
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
		$this->job_category_db = new \core\modules\job\models\common\job_category_db;
		$this->entity = false;
		$this->partners_lep=[];
		if(isset($_SESSION['entity'])){
            $this->entity = $_SESSION['entity'];
			$this->partners = [];
		}else{
			$this->partners = $recruitment_db->getListPartner();
			$this->partners_lep = $recruitment_db->getListPartner('LEP');	
		}

		$this->list_status = ['unverified','request','process','verified','rejected'];
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('candidate');
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
		$this->view_variables_obj->addViewVariables('partners_lep',$this->partners_lep);
		$this->view_variables_obj->addViewVariables('list_status',$this->list_status);
		$this->view_variables_obj->addViewVariables('entity',$this->entity);
		$this->view_variables_obj->addViewVariables('job_categories',$this->job_category_db->getAll());
		$this->view_variables_obj->addViewVariables('add_ab_link',$this->menu_register->getModuleLink('address_book').'/add/rec');

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