<?php
namespace core\modules\personal\models\general;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 December 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'general';
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
				
		
		//get the existing information (if any)
		$personal_db = new \core\modules\personal\models\common\db;
		$this->general = $personal_db->getGeneral($_SESSION['personal']['address_book_id']);
		
		if(empty($this->general))
		{
			$this->general = array(
				'height_weight' => 'me',
				'height_cm' => '',
				'weight_kg' => '',
				'height_in' => '',
				'weight_lb' => '',
				'bmi' => '',
				'tattoo' => '',
				'relationship' => '',
				'children' => '',
				'employment' => '',
				'job_hunting' => '',
				'seafarer' => '',
				'migration' => '',
				'country_born' => '',
				'country_residence' => '',
				'passport' => '',
				'travelled_overseas' => '',
				'nok_family_name' => '',
				'nok_given_names' => '',
				'nok_relationship' => '',
				'nok_line_1' => '',
				'nok_line_2' => '',
				'nok_line_3' => '',
				'nok_country' => '',
				'nok_number_type' => '',
				'nok_number' => '',
				'nok_email' => '',
				'nok_skype' => '',
				'filename' => ''
			);
		}
				
		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		//dialCodes
		$this->countryDialCodes = $core_db->getAllDialCodes();
		
		//set main details for the title
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file

		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('general');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useCroppie("2.5.1");
		$this->view_variables_obj->useSweetAlert("6.6.2");
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? '/home/'.$_SESSION['personal']['address_book_id'].'/' : ''));
		$this->view_variables_obj->addViewVariables('general',$this->general);
		$this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
		$this->view_variables_obj->addViewVariables('countryDialCodes',$this->countryDialCodes);
		
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