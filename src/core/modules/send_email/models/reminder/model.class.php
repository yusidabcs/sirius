<?php
namespace core\modules\send_email\models\reminder;

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

	protected $model_name = 'reminder';
	protected $processPost = true;

	private $templates;
	
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
		$this->view_variables_obj->setViewTemplate('reminder');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->mailing_db = new \core\modules\send_email\models\common\db;
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSelect2();
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		$this->view_variables_obj->addViewVariables('campaigns', $this->mailing_db->getAllCampaign());
		
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
		
}
?>