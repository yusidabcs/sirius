<?php
namespace core\modules\job_application\models\interview_note;

use DateTime;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'interview_note';
	protected $processPost = false;
	
	public function __construct()
	{
		parent::__construct();
        $this->interview_db = new \core\modules\interview\models\common\db();
        $this->job_db = new \core\modules\job\models\common\db();
        $this->address_book_db = new \core\modules\address_book\models\common\address_book_db_obj();
        $this->generic_obj = \core\app\classes\generic\generic::getInstance();
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->defaultView();
		$job_application_id = $this->page_options[0];
		$this->job_application = $this->job_db->getJobApplication($job_application_id);
		if(!$this->job_application){
            $msg = "No data found with that job application id";
            throw new \RuntimeException($msg);
        }

        $this->address_book = $this->address_book_db->getAddressBookMainDetails($this->job_application['address_book_id']);
        $this->fullname = $this->generic_obj->getName('per', $this->address_book['entity_family_name'], $this->address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

        $this->result = $this->interview_db->getIntreviewResult($job_application_id);
        $this->answer = $this->interview_db->getIntreviewAnswer($job_application_id);
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('interview_note');
		return;
	}
	
	//required function
	protected function setViewVariables()
    {
        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);
        $this->view_variables_obj->addViewVariables('job_application',$this->job_application);
        $this->view_variables_obj->addViewVariables('answer',$this->answer);
        $this->view_variables_obj->addViewVariables('result',$this->result);
        $this->view_variables_obj->addViewVariables('address_book',$this->address_book);
        $this->view_variables_obj->addViewVariables('fullname',$this->fullname);

		return;
	}

}
?>