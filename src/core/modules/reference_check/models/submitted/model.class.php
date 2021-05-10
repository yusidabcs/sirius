<?php
namespace core\modules\reference_check\models\submitted;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 26 September 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'submitted';
	protected $processPost = false;
	
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
		$this->view_variables_obj->setViewTemplate('submitted');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//variables
		$this->view_variables_obj->addViewVariables('baseURL',$this->baseURL);
		return;
	}
		
}
?>