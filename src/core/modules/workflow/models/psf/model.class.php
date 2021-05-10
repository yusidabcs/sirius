<?php
namespace core\modules\workflow\models\psf;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'psf';
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
		$this->status = ['generate_invoice','pay_invoice','paid','cancelled'];
        $this->level = [1 => 'normal',2 => 'soft', 3 => 'hard', 4 => 'deadline'];
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		
		$this->view_variables_obj->setViewTemplate('psf');
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