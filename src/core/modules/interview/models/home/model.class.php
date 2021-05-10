<?php
namespace core\modules\interview\models\home;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'home';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->interview_db = new \core\modules\interview\models\common\db();
        return;
    }

    //required function
    protected function main()
    {   
        $this->authorize();
        $ent = false;
        if($this->useEntity) {
            $ent = $this->entity['address_book_ent_id'];
        }
        $this->ongoing_location = $this->interview_db->getOnGoingInterviewLocation($ent);
        $this->total_hire = $this->interview_db->getTotalHireInterview(false,$ent);
        $this->total_interview = $this->interview_db->getTotalInterviewCandidate();
        $this->total_schedule = $this->interview_db->getTotalScheduleCandidate();
        
        $this->total_not_hire = $this->interview_db->getTotalNotHireInterview(false,$ent);
        $this->interviewers = $this->interview_db->getListInterviewer();
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('home');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSortable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useFullcalendar();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('ongoing_location',$this->ongoing_location);
        $this->view_variables_obj->addViewVariables('total_interview',$this->total_interview);
        $this->view_variables_obj->addViewVariables('total_schedule',$this->total_schedule);
        $this->view_variables_obj->addViewVariables('total_hire',$this->total_hire);
        $this->view_variables_obj->addViewVariables('total_not_hire',$this->total_not_hire);
        $this->view_variables_obj->addViewVariables('interviewers',$this->interviewers);

        return;
    }

}
?>
