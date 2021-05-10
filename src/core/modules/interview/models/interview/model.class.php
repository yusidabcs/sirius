<?php
namespace core\modules\interview\models\interview;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'interview';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->core_db = new \core\app\classes\core_db\core_db;
        $this->job_db = new \core\modules\job\models\common\db();
        $this->interview_db = new \core\modules\interview\models\common\db();
        $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        //get detail schedule
        $this->schedule = $this->interview_db->getInterviewScheduleById($this->page_options[0]);
        if(!$this->schedule){
            die('schedule not found');
        }
        $this->location = null;
        if($this->schedule['type'] == 'physical'){
            $this->location = $this->interview_db->getInterviewLocationById($this->schedule['interview_location_id']);
            $country = $this->core_db->getCountry($this->location['countryCode_id']);
            $subcountry = $this->core_db->getSubCountry($this->location['countrySubCode_id']);
            $this->location['country'] = $country[$this->location['countryCode_id']];
            $this->location['subcountry'] = $subcountry[$this->location['countryCode_id']];
            $this->interviewer = $this->address_book_db->getAddressBookMainDetails($_SESSION['address_book_id']);

        }else{
            $this->interviewer = $this->address_book_db->getAddressBookMainDetails($this->schedule['interviewer_id']);
        }
        $this->job_application = $this->job_db->getJobApplication($this->schedule['job_application_id']);
        if(!$this->job_application){
            die('Job application not found');
        }

        $this->address_book = $this->address_book_db->getAddressBookMainDetails($this->job_application['address_book_id']);

        $this->general_question = $this->interview_db->getRandomIntreviewQuestion('general',2);
        $this->specific_question = $this->interview_db->getRandomIntreviewQuestion('specific',2, $this->job_application['job_speedy_code']);

        $this->job_speedy = $this->job_db->getJobSpeedy($this->job_application['job_speedy_code']);
        $this->similar_job_speedy = $this->job_db->getJobSpeedyInSameCategory($this->job_speedy['job_speedy_category_id']);
        $this->job_master = $this->job_db->getJobMasterByJobSpeedy($this->job_application['job_speedy_code']);
        $this->status = ['excellent','favorable','acceptable','unfavorable','highly_unfavorable'];

        $system_register = \core\app\classes\system_register\system_register::getInstance();
        if($system_register->getModuleIsInstalled('personal'))
        {
			$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
			$menu_register = $menu_register_ns::getInstance();
            $this->personal_link = '/'.$menu_register->getModuleLink('personal').'/home/'.$this->job_application['address_book_id'];
        } else {
            $this->personal_link = '#';
        }
        $this->link_personal = 
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('interview');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSortable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('job_application',$this->job_application);
        $this->view_variables_obj->addViewVariables('schedule',$this->schedule);
        $this->view_variables_obj->addViewVariables('general_question',$this->general_question);
        $this->view_variables_obj->addViewVariables('specific_question',$this->specific_question);
        $this->view_variables_obj->addViewVariables('status',$this->status);
        $this->view_variables_obj->addViewVariables('address_book',$this->address_book);
        $this->view_variables_obj->addViewVariables('location',$this->location);
        $this->view_variables_obj->addViewVariables('interviewer',$this->interviewer);
        $this->view_variables_obj->addViewVariables('similar_job_speedy',$this->similar_job_speedy);
        $this->view_variables_obj->addViewVariables('job_master',$this->job_master);
        $this->view_variables_obj->addViewVariables('personal_link',$this->personal_link);

        return;
    }

}
?>
