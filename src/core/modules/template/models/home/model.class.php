<?php
namespace iow\modules\xMODULEx\models\home;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		xMODULEx
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee xDATEx
 */
final class model extends \iow\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('home');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
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