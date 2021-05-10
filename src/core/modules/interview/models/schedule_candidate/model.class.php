<?php
namespace core\modules\interview\models\schedule_candidate;

final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'schedule_candidate';
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
		$entity = '';
		
		if(isset($_SESSION['entity'])){
			$entity = $_SESSION['entity'];
			$this->partners = [];
		}else{
			$this->partners = $recruitment_db->getListPartner();	
		}

		$this->list_status = ['applied','accepted','interview','canceled'];
		$this->active_status = isset($this->page_options[0]) ? $this->page_options[0] : null;
        $this->timezones = timezone_identifiers_list(16);
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('schedule_candidate');
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
        $this->view_variables_obj->addViewVariables('timezones',$this->timezones);

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