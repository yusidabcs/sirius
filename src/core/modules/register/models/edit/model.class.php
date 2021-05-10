<?php
namespace core\modules\register\models\edit;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'edit';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countries = $core_db->getAllCountryCodes();
		
		//register db
		$register_db = new \core\modules\register\models\common\register_db;

		$this->country_code_info = $register_db->getInfoArray();
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('edit');		
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL);
		
		$this->view_variables_obj->addViewVariables('country_code_info',$this->country_code_info);
		
		$this->view_variables_obj->addViewVariables('countries',$this->countries);
		
		
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