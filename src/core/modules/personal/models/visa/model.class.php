<?php
namespace core\modules\personal\models\visa;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 December 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'visa';
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
		
		//make sure we have a specific visa nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$visa_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no visa specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		//get the passport list because all visa's must be linked to a passport
		$personal_db = new \core\modules\personal\models\common\db;
		$passportList = $personal_db->getPassportList($_SESSION['personal']['address_book_id']);
		
		if(empty($passportList))
		{
			$msg = "What no passports? You can not add a visa without a passport?";
			throw new \RuntimeException($msg);	
		} else {
			$this->passportArray = array_keys($passportList);
		}
		
		if($visa_id == 'new')
		{
			$this->visa = array(
				'visa_id' => '',
				'countryCode_id' => '',
				'from_date' => '',
				'to_date' => '',
				'family_name' => '',
				'given_names' => '',
				'full_name' => '',
				'place_issued' => '',
				'entry' => '',
				'type' => '',
				'class' => '',
				'authority' => '',
				'active' => '',
				'passport_id' => '',
				'filename' => ''
			);
			
		} else {
			
			//get the existing information (if any)
			$this->visa = $personal_db->getVisa($visa_id);
			if(empty($this->visa))
			{
				$msg = "What no visa information! How did that happen?";
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
		$this->view_variables_obj->setViewTemplate('visa');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useCroppie("2.5.1");
		$this->view_variables_obj->useSweetAlert("6.6.2");
		$this->view_variables_obj->useFlatpickr("3.0.6");
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'passp');
		$this->view_variables_obj->addViewVariables('visa',$this->visa);
		$this->view_variables_obj->addViewVariables('passportArray',$this->passportArray);
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