<?php
namespace core\modules\reference_check\models\process;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'process';
	protected $processPost = true;
	protected $address_book_detail = null;
	
	public function __construct()
	{
		parent::__construct();		
		return;
	}
	
	//required function
	protected function main()
	{
		if (isset($this->page_options[0])) {
            $hash = $this->page_options[0];
            $personal_db = new \core\modules\personal\models\common\db();
			$this->reference_check = $personal_db->getReferenceCheckByHash($hash);

            if (empty($this->reference_check)) {
                // no partner with the partner code embedded
                $htmlpage_ns = NS_HTML . '\\htmlpage';
                $htmlpage = new $htmlpage_ns(404);
                exit();
            }
            $this->questions = $personal_db->getReferenceQuestions($this->reference_check['question_type']);
			$this->reference = $personal_db->getReference($this->reference_check['reference_id']);
			
			if($this->reference){
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				
				$this->address_book_detail = $address_book_common->getAddressBookMainDetails($this->reference['address_book_id']);
			}
			
        }else{

            // no partner with the partner code embedded
            $htmlpage_ns = NS_HTML . '\\htmlpage';
            $htmlpage = new $htmlpage_ns(404);
            exit();
        }


		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('process');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);
		$this->view_variables_obj->addViewVariables('questions',$this->questions);
		$this->view_variables_obj->addViewVariables('reference',$this->reference);
		$this->view_variables_obj->addViewVariables('reference_check',$this->reference_check);

		$generic_obj = \core\app\classes\generic\generic::getInstance();
		$full_name = $generic_obj->getName('per',$this->address_book_detail['entity_family_name'], $this->address_book_detail['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
		$this->view_variables_obj->addViewVariables('address_book_detail',$this->address_book_detail);
		$this->view_variables_obj->addViewVariables('full_name',$full_name);
		
		//needed items
		$this->view_variables_obj->useSweetAlert();
		
		//variables

		
		
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

        if(isset($_SESSION['user_id']))
        {
            $this->view_variables_obj->addViewVariables('use_captcha',false);
        } else {
            $this->view_variables_obj->addViewVariables('use_captcha',true);
        }
		return;
	}
		
}
?>