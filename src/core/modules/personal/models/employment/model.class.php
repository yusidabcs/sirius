<?php
namespace core\modules\personal\models\employment;

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

	protected $model_name = 'employment';
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
		
		//make sure we have a specific employment nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$employment_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no employment specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		if($employment_id == 'new')
		{
			$this->employment = array(
				'employment_id' => '',
				'job_speedy_category_id' => '',
				'from_date' => '',
				'to_date' => '',
				'employer' => '',
				'countryCode_id' => '',
				'website' => '',
				'email' => '',
				'phone' => '',
				'job_title' => '',
				'type' => '',
				'description' => '',
				'active' => '',
				'filename' => '',
				'view_from' => '',
				'view_to' => ''
			);
			
		} else {
			
			//get the existing information (if any)
			$personal_db = new \core\modules\personal\models\common\db;
			$this->employment = $personal_db->getEmployment($employment_id);
			if(empty($this->employment))
			{
				$msg = "What no employment information! How did that happen?";
				throw new \RuntimeException($msg);
			}
		}
		
		//set main details for the view (i.e. $main['sex'])
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file
		
		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		$category_db = new \core\modules\job\models\common\job_category_db();
		$this->job_speedy_category = $category_db->getAll();
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('employment');
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
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'employ');
		$this->view_variables_obj->addViewVariables('employment',$this->employment);
		$this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
		$this->view_variables_obj->addViewVariables('job_categories',$this->job_speedy_category);
				
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