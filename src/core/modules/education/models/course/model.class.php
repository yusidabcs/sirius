<?php
namespace core\modules\education\models\course;

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

	protected $model_name = 'course';
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();

        $this->status = ['active','disabled'];
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		
		$this->view_variables_obj->setViewTemplate('course');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{		
        $this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useFlatpickr();
		$this->view_variables_obj->useTrumbowyg();
        $this->view_variables_obj->useCroppie();
		
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		$this->view_variables_obj->addViewVariables('status',$this->status);

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