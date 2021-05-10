<?php
namespace core\modules\education\models\dashboard;


final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'dashboard';
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
        $this->period = ['today','this_month'];
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		
		$this->view_variables_obj->setViewTemplate('dashboard');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
        $this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useFlatpickr();
		$this->view_variables_obj->useMoment();
		
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('period',$this->period);

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