<?php
namespace core\modules\send_email\models\edit_template;

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

	protected $model_name = 'edit_template';
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
		$this->view_variables_obj->setViewTemplate('edit_template');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		$this->view_variables_obj->addViewVariables('back_url', '/send-email/email_template');
		
		//needed for the image
		$this->view_variables_obj->useSweetAlert();

		$template_name = $this->page_options[0];
		$this->view_variables_obj->addViewVariables('template_name', $template_name);
		$this->view_variables_obj->addViewVariables('template_content', file_get_contents(DIR_MODULES . '/send_email/views/template/' . $template_name . '.html'));

		
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