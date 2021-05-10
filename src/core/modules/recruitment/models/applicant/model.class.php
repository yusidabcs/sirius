<?php
namespace core\modules\recruitment\models\applicant;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'applicant';
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
		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		$recruitment_db = new \core\modules\recruitment\models\common\db;

		$this->principal_db = new \core\modules\principal\models\common\db();
		$this->principals = $this->principal_db->getPrincipalArray();
		$entity = '';

		if(isset($_SESSION['entity'])){
			$entity = $_SESSION['entity'];
			$this->partners = [];
		}else{
			$this->partners = $recruitment_db->getListPartner();	
		}

		$this->list_status = ['applied','accepted','interview','canceled'];
		$this->active_status = isset($this->page_options[0]) ? $this->page_options[0] : null;

		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('applicant');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
        $this->view_variables_obj->useEkkoLightBox();
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();

		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->myURL); 
		$this->view_variables_obj->addViewVariables('prescreen_base_url',HTTP_TYPE.SITE_WWW.''.$this->baseURL.'/prescreen_form');

		$this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
		$this->view_variables_obj->addViewVariables('partners',$this->partners);
		$this->view_variables_obj->addViewVariables('list_status',$this->list_status);
		$this->view_variables_obj->addViewVariables('active_status',$this->active_status);
		$this->view_variables_obj->addViewVariables('principals',$this->principals);

		$this->_system_ini_a = parse_ini_file(DIR_SECURE_INI.'/system_config.ini');
		$this->view_variables_obj->addViewVariables('can_by_pass',$this->_system_ini_a['BYPASS_USER_PROCESS']);

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