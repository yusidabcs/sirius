<?php
namespace core\modules\personal\models\reference_check;

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

	protected $model_name = 'reference_check';
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
		//make sure we have a specific reference nominated which can be "new"
		if(isset($this->page_options[0]))
		{
			$reference_id = $this->page_options[0];
			
		} else {
			
			$msg = "What no reference specified! How did that happen?";
			throw new \RuntimeException($msg);

		}

        //get the existing information (if any)
        $personal_db = new \core\modules\personal\models\common\db;
		$this->reference = $this->_getReference($reference_id);
		$this->reference_check_list = $personal_db->getReferenceCheckList($reference_id);
        $this->reference_check = $personal_db->getLatestReferenceCheck($reference_id);
        $this->questions = $personal_db->getReferenceQuestions($this->reference['type']);
		$this->able_to_reference_check = true;

		foreach ($this->reference_check_list as $key => $value) {
			$this->answers[$value['id']] = $personal_db->getReferenceCheckAnswer($value['id']);
		}
		
		if (count($this->reference_check_list) === 2) {
			$this->able_to_reference_check = false;
		}

        if(empty($this->reference))
        {
            $msg = "What no reference information! How did that happen?";
            throw new \RuntimeException($msg);
        }

		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('reference_check');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{	
		//required scripts for the image
		$this->view_variables_obj->useSweetAlert();
		
		//variables
		$this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/home/'.($_SESSION['personal']['user_id'] != $_SESSION['user_id']? $_SESSION['personal']['address_book_id'].'/' : '').'ref');
		$this->view_variables_obj->addViewVariables('reference',$this->reference);
		$this->view_variables_obj->addViewVariables('reference_check',$this->reference_check);
		$this->view_variables_obj->addViewVariables('reference_check_list',$this->reference_check_list);
		$this->view_variables_obj->addViewVariables('able_to_reference_check',$this->able_to_reference_check);
		$this->view_variables_obj->addViewVariables('questions',$this->questions);
		$this->view_variables_obj->addViewVariables('answers',$this->answers);

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

	private function _getReference($reference_id)
	{
		$personal_db = new \core\modules\personal\models\common\db;
		$reference = $personal_db->getReference($reference_id);
		$generic = \core\app\classes\generic\generic::getInstance();

		$this->core_db = new \core\app\classes\core_db\core_db;
		$this->countryCodes = $this->core_db->getAllCountryCodes();
		$this->countryDialCodes = $this->core_db->getAllDialCodes();

		$reference['phone_number'] = $this->countryDialCodes[$reference['countryCode_id']]['dialCode'].$reference['number'];
		$reference['country'] = $this->countryCodes[$reference['countryCode_id']];
		$reference['fullname'] = 'Mr/Mrs. ' . $generic->getName('per', $reference['family_name'], $reference['given_names'], 'FFCC', '');

		return $reference;
	}
		
}
?>