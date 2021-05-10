<?php
namespace core\modules\personal\models\education;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 7 January 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'education';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function main()
	{	
		$this->authorize();
		//if Session Resume Address_book_id is not set then we should not be here
		if(!isset($_SESSION['personal']['address_book_id']))
		{
			header('Location: '.$this->baseURL);
			exit();
		}
		
		//make sure we have a specific education nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$education_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no education specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		if($education_id == 'new')
		{
			$this->education = array(
				'education_id' => '',
				'from_date' => '',
				'to_date' => '',
				'institution' => '',
				'countryCode_id' => '',
				'website' => '',
				'email' => '',
				'phone' => '',
				'qualification' => '',
				'type' => '',
				'description' => '',
				'level' => '',
				'attended_countryCode_id' => '',
				'active' => '',
				'english' => '',
				'certificate_date' => '',
				'certificate_number' => '',
				'certificate_expiry' => '',
				'stcw_type' => '',
				'filename' => '',
				'view_from' => '',
				'view_to' => '',
				'certificate_from' => '',
				'certificate_to' => ''
			);
			
		} else {
			
			//get the existing information (if any)
			$personal_db = new \core\modules\personal\models\common\db;
			$this->education = $personal_db->getEducation($education_id);
			if(empty($this->education))
			{
				$msg = "What no education information! How did that happen?";
				throw new \RuntimeException($msg);
			}
		}
		
		//set main details for the view (i.e. $main['sex'])
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file
		
		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('education');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->useFlatpickr();
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'edu');
		$this->view_variables_obj->addViewVariables('education',$this->education);
		$this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
				
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