<?php
namespace core\modules\register\models\process;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 February 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'process';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		//if they have not attempted to give a hash then redirect to base url
		if(isset($this->page_options[0]))
		{
			$hash = $this->page_options[0];
		} else {
			header("Location: $this->baseURL");
			exit();
		}

		//make sure this hash is real
		$register_db = new \core\modules\register\models\common\register_db;
		
		//clean up old registrations first
		$register_db->cleanRegistrationInfo();
		
		//now check this hash
		$this->register_info = $register_db->getRegistrationInfo($hash);

		//countries
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countries = $core_db->getAllCountryCodes();
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('process');
		$this->view_variables_obj->addViewVariables('registered',false);
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//POST Variable
		$this->view_variables_obj->addViewVariables('baseURL',$this->baseURL);
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		
		//variables
		$this->view_variables_obj->addViewVariables('register_info',$this->register_info);
		$this->view_variables_obj->addViewVariables('countries',$this->countries);
		
		if(!empty($this->register_info))
		{
			$age = date_diff(date_create($this->register_info['dob']), date_create('today'))->y;
			$this->view_variables_obj->addViewVariables('age',$age);		
		}
		
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