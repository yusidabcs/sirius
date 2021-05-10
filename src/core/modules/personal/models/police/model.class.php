<?php
namespace core\modules\personal\models\police;

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

	protected $model_name = 'police';
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
		
		//make sure we have a specific passport nominated which can be "new"
		if(isset($this->page_options[0]))
		{
            $police_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no passport specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		if($police_id == 'new')
		{
			$this->police = array(
				'police_id' => '',
				'countryCode_id' => '',
				'from_date' => '',
				'to_date' => '',
				'family_name' => '',
				'given_names' => '',
				'full_name' => '',
				'nationality' => '',
				'sex' => '',
                'dob' => '',
                'pob' => '',
				'place_issued' => '',
				'date_issued' => '',
				'active' => '',
				'filename' => ''
			);
			
		} else {
			
			//get the existing information (if any)
			$personal_db = new \core\modules\personal\models\common\db;
			$this->police = $personal_db->getPolice($police_id);
			if(empty($this->police))
			{
				$msg = "What no police check information! How did that happen?";
				throw new \RuntimeException($msg);
			}
		}

		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('police');
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
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'police');
		$this->view_variables_obj->addViewVariables('police',$this->police);
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