<?php
namespace core\modules\offer_letter\models\trackers;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'trackers';
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
		$this->defaultView();
		$this->workflow_db = new \core\modules\workflow\models\common\db;

        $this->status = ['endorsement','offer_letter','candidate_acceptance','personal_data','accepted','denied'];
		$this->level = ['normal','soft','hard','deadline'];
		$this->oktb_types = $this->workflow_db->getOktbTypes();
		$this->visa_types = $this->workflow_db->getVisaTypes();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('trackers');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{

        $this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSelect2();

		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('status',$this->status);
		$this->view_variables_obj->addViewVariables('levels',$this->level);
		$this->view_variables_obj->addViewVariables('oktb_types',$this->oktb_types);
		$this->view_variables_obj->addViewVariables('visa_types',$this->visa_types);

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