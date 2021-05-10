<?php
namespace core\modules\send_email\models\banner;

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

	protected $model_name = 'banner';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->css_info = file_get_contents(DIR_LOCAL_UPLOADS.'/send_email/email.css');
		
		if(is_readable(DIR_LOCAL_UPLOADS.'/send_email/banner.png'))
		{
			$this->img_src = HTTP_TYPE.SITE_WWW.'/local/uploads/send_email/banner.png';
		} else {
			$this->img_src = false;
		}
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('banner');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		
		//other variables
		$this->view_variables_obj->addViewVariables('css_info',$this->css_info);
		$this->view_variables_obj->addViewVariables('img_src',$this->img_src);
		
		//needed for the image
		$this->view_variables_obj->useCroppie();
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