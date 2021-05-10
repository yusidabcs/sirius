<?php
namespace core\modules\recruitment\models\prescreen_form;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'prescreen_form';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->core_db = new \core\app\classes\core_db\core_db;
        $this->recruitment_db = new \core\modules\recruitment\models\common\db();
        $this->job_db = new \core\modules\job\models\common\db();
        $this->generic = \core\app\classes\generic\generic::getInstance();
        $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $this->personal_db = new \core\modules\personal\models\common\db;
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        if (!isset($this->page_options[0]))
        {
            //job application id not found
            $htmlpage_ns = NS_HTML . '\\htmlpage';
            new $htmlpage_ns(404);
            exit();   
        }
        //check if job application valid
        if(!$job_application = $this->job_db->getJobApplication($this->page_options[0])){
            $htmlpage_ns = NS_HTML . '\\htmlpage';
            new $htmlpage_ns(404);
            exit();
        }

        //check if there is redirect option, and check if in allowerd redirect	
        $this->redirect_to = '';
        if (isset($this->page_options[1]))
        {
            $redirect_to = $this->page_options[1];
            if ( in_array($redirect_to,['rec']) )
            {
                $this->redirect_to = $redirect_to;
            }
        }


        $address_book_id = $job_application['address_book_id'];

        $prescreener_main = $this->address_book_db->getAddressBookMainDetails($_SESSION['address_book_id']);
        $main = $this->address_book_db->getAddressBookMainDetails($address_book_id);
        $partner_main = $this->personal_db->getLocalPartnerDataByAddressBookId($address_book_id);
        
        $applicant['job_position'] = $job_application['job_title'];
        $applicant['full_name'] = $this->generic->getName('per', $main['entity_family_name'], $main['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $applicant['prescreener_full_name'] = $this->generic->getName('per', $prescreener_main['entity_family_name'], $prescreener_main['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $applicant['partner_name'] = $partner_main['entity_family_name'];
        $applicant['email'] = $main['main_email'];

        $this->applicant = $applicant;
        $this->questions = $this->core_db->getPreIntreviewQuestion();
        $rs = [];
        foreach ($this->questions as $index => $item){
            $item['childs'] = [];
            if($item['parent_id'] == 0){

                foreach ($this->questions as $index2 => $item2){
                    if($item2['parent_id'] == $item['question_id']){
                        $item['childs'][] = $item2;
                    }
                }
                $rs[] = $item;
            }
        }
        $this->questions = $rs;

        $this->answers = $this->recruitment_db->getJobApplicationInterviewAnswer($this->page_options[0], 'prescreen');
        $this->type = 'prescreen';
        $this->job_application = $job_application;
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('prescreen_form');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSweetAlert();
        //POST Variable
        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);
        $this->view_variables_obj->addViewVariables('back_url',$this->baseURL.'/prescreen');
        $this->view_variables_obj->addViewVariables('redirect_to',$this->redirect_to);
        $this->view_variables_obj->addViewVariables('questions',$this->questions);
        $this->view_variables_obj->addViewVariables('answers',$this->answers);
        $this->view_variables_obj->addViewVariables('type',$this->type);
        $this->view_variables_obj->addViewVariables('applicant',$this->applicant);
        $this->view_variables_obj->addViewVariables('job_application',$this->job_application);
        $this->view_variables_obj->addViewVariables('checklist_type','pre_screen');
        
        $this->_system_ini_a = parse_ini_file(DIR_SECURE_INI.'/system_config.ini');
		$this->view_variables_obj->addViewVariables('can_by_pass',$this->_system_ini_a['BYPASS_USER_PROCESS']);
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
