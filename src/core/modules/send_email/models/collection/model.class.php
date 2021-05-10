<?php
namespace core\modules\send_email\models\collection;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'collection';
	protected $processPost = true;

	private $templates;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->list_status = ['unverified','request','process','verified','rejected'];
		$recruitment_db = new \core\modules\recruitment\models\common\db;
		$this->job_category_db = new \core\modules\job\models\common\job_category_db;

		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();

		$this->partners = $recruitment_db->getListPartner();
		$this->partners_lep = $recruitment_db->getListPartner('LEP');	
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('collection');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSelect2();
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);

		$db = new \core\modules\send_email\models\common\db;
		
		//other variables
		$this->view_variables_obj->addViewVariables('css_info',$this->css_info);
		$this->view_variables_obj->addViewVariables('img_src',$this->img_src);
		$this->view_variables_obj->addViewVariables('job_categories',$this->job_category_db->getAll());$this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
		$this->view_variables_obj->addViewVariables('partners',$this->partners);
		$this->view_variables_obj->addViewVariables('partners_lep',$this->partners_lep);
		$this->view_variables_obj->addViewVariables('list_status',$this->list_status);
		
		//needed for the image
		$this->view_variables_obj->useSweetAlert();
		
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