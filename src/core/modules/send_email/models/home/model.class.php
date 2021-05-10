<?php
namespace core\modules\send_email\models\home;

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

	protected $model_name = 'home';
	protected $processPost = true;

	private $templates;

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
		$this->view_variables_obj->setViewTemplate('home');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSelect2();
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);

		$this->collections = $this->_getCollections();
		$this->view_variables_obj->addViewVariables('collections', $this->collections);

		$db = new \core\modules\send_email\models\common\db;
		$this->view_variables_obj->addViewVariables('subscribers', $db->getAllSubscriber());
		
		//other variables
		$this->view_variables_obj->addViewVariables('css_info',$this->css_info);
		$this->view_variables_obj->addViewVariables('img_src',$this->img_src);
		
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

	private function _getCollections()
	{
		$mailing_db = new \core\modules\send_email\models\common\db;
		return $mailing_db->getAllCollection();
	}
		
}
?>