<?php
namespace core\modules\job\models\user;

/**
 * Final model class.
 *
 * @final
 * @package 	job
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'user';
	protected $processPost = true;
		
	public function __construct()
	{
		parent::__construct();
		$this->job_db = new \core\modules\job\models\common\db;
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
		$this->view_variables_obj->setViewTemplate('user');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();
		
		
        //POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		
		return;
	}
		
}
?>
