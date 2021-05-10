<?php
namespace core\modules\education\models\request;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		education
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'request';
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$recruitment_db = new \core\modules\recruitment\models\common\db;
		
		$this->status = ['request','accepted','enrolled','finish','cancel'];
		
		$this->partners = $recruitment_db->getListPartner();
		$this->partners_lep = $recruitment_db->getListPartner('LEP');

		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		
		$this->view_variables_obj->setViewTemplate('request');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
        $this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useFlatpickr();
		
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('status',$this->status);

		$this->view_variables_obj->addViewVariables('partners',$this->partners);
		$this->view_variables_obj->addViewVariables('partners_lep',$this->partners_lep);

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