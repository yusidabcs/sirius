<?php
namespace core\modules\job\models\speedy;

/**
 * Final model class.
 *
 * @final
 * @package 	job
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'speedy';
	protected $processPost = true;
	protected $core_db;
	public function __construct()
	{
		parent::__construct();
		$this->job_db = new \core\modules\job\models\common\db;
		$this->job_category_db = new \core\modules\job\models\common\job_category_db;
		$this->core_db = new \core\app\classes\core_db\core_db();
		$this->principal_db = new \core\modules\principal\models\common\db();

		$this->min_education_list = ['school','certificate','diploma','degree','honours','masters','doctorate'];
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->countries = $this->core_db->getAllCountryCodes();
		$this->principal = $this->principal_db->getPrincipalArray();
		//print_r($this->countries);
		$this->defaultView();
		return;
	}
		
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('speedy');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useTrumbowyg();
        $this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useSelect2();
		
        //POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		$this->view_variables_obj->addViewVariables('job_categories',$this->job_category_db->getAll());
		$this->view_variables_obj->addViewVariables('min_education_list',$this->min_education_list);
		$this->view_variables_obj->addViewVariables('countries',$this->countries);
		$this->view_variables_obj->addViewVariables('principal', $this->principal);
		return;
	}
		
}
?>
