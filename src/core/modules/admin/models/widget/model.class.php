<?php
namespace core\modules\admin\models\widget;

/**
 * Final model class.
 *
 * This is a factory based on the first option.  All sub classes are of interface type admin_main 
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'widget';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		
		//this model uses a number of different functions
		
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
		$this->view_variables_obj->setViewTemplate('widget');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//POST Variable
		if($this->input_obj)
		{
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