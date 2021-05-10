<?php
namespace core\modules\offer_letter\models\speedy_pool;

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

	protected $model_name = 'speedy_pool';
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
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('speedy_pool');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{

        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();

		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		
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