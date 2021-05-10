<?php
namespace core\modules\workflow\models\premium_service;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'premium_service';
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
        $this->status = ['request_psf','candidate_verification','confirm_psf','accepted'];
        $this->level = [1 => 'normal',2 => 'soft', 3 => 'hard', 4 => 'deadline'];
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('premium_service');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{

        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useFlatpickr();
        //POST Variable
        $this->view_variables_obj->addViewVariables('post',$this->myURL);
        $this->view_variables_obj->addViewVariables('status',$this->status);
        $this->view_variables_obj->addViewVariables('level',$this->level);
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