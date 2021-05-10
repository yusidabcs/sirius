<?php
namespace core\modules\send_email\models\campaign;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	send_email
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 21 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'campaign';
	protected $processPost = true;

	private $templates;
	private $emails;
	private $collections;
	
	public function __construct()
	{
		parent::__construct();		
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
		$this->view_variables_obj->setViewTemplate('campaign');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useSelect2();
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useMoment();
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);

		$this->templates = $this->_getTemplates();
		$this->view_variables_obj->addViewVariables('templates', $this->templates);

		$this->emails = $this->_getSubscribers();
		$this->view_variables_obj->addViewVariables('emails', $this->emails);

		$this->collections = $this->_getCollections();
		$this->view_variables_obj->addViewVariables('collections', $this->collections);
		
		//needed for the image
		$this->view_variables_obj->useSweetAlert();
		
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

	private function _getTemplates()
	{
		$mailing_db = new \core\modules\send_email\models\common\db;

		$out = $mailing_db->getMarketingTemplates();

		return $out;
	}

	private function _getSubscribers()
	{
		$mailing_db = new \core\modules\send_email\models\common\db;

		return $mailing_db->getAllSubscriber();
	}

	private function _getCollections()
	{
		$mailing_db = new \core\modules\send_email\models\common\db;

		return $mailing_db->getAllCollection();
	}
		
}
?>