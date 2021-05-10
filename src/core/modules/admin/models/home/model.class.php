<?php
namespace core\modules\admin\models\home;

/**
 * Final model class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
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
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//default view
		$this->view_variables_obj->setViewTemplate('home');
		
		//variables
		if( is_writable(FILE_MANAGER_STORAGE_LOCAL) )
		{
			$this->view_variables_obj->addViewVariables('hasFMDir',true);
		} else {
			$this->view_variables_obj->addViewVariables('hasFMDir',false);
		}
		
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->modelURL);
		
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