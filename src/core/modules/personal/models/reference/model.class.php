<?php
namespace core\modules\personal\models\reference;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 January 2018
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'reference';
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
		
		//make sure we have a specific the type
		if(isset($this->page_options[0]))
		{
			$acceptable_types = array('personal','work');
			
			if(in_array($this->page_options[0], $acceptable_types))
			{
				$type = $this->page_options[0];
			} else {
				$msg = "What no valid type specified! How did that happen?";
				throw new \RuntimeException($msg);
			}
			
		} else {
			
			$msg = "What no type specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		//make sure we have a specific reference nominated which can be "new"
		if(isset($this->page_options[1]))
		{
			$reference_id = $this->page_options[1];
			
		} else {
			
			$msg = "What no reference specified! How did that happen?";
			throw new \RuntimeException($msg);

		}
		
		if($reference_id == 'new')
		{
			$this->reference = array(
				'reference_id' => '',
				'type' => $type,
				'entity_name' => '',
				'family_name' => '',
				'given_names' => '',
				'relationship' => '',
				'line_1' => '',
				'line_2' => '',
				'line_3' => '',
				'countryCode_id' => '',
				'number_type' => '',
				'number' => '',
				'email' => '',
				'skype' => '',
				'comment' => '',
				'filename' => ''
			);
			
		} else {

						
			//get the existing information (if any)
			$personal_db = new \core\modules\personal\models\common\db;
			$this->reference = $personal_db->getReference($reference_id);
			if(empty($this->reference))
			{
				$msg = "What no reference information! How did that happen?";
				throw new \RuntimeException($msg);
			}
		}
		
		//get the countryCodes
		$core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $core_db->getAllCountryCodes();
		//dialCodes
		$this->countryDialCodes = $core_db->getAllDialCodes();
		//set main details for the view (i.e. $main['sex'])
		$view_core = \core\modules\address_book\models\common\view\core::getInstance($_SESSION['personal']['address_book_id']);
		$main_file = $view_core->getContentViewFile('main'); //we actually don't use the file
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('reference');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useCroppie();
		$this->view_variables_obj->useSweetAlert();
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'ref');
		$this->view_variables_obj->addViewVariables('reference',$this->reference);
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