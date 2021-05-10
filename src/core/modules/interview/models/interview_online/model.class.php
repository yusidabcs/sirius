<?php
namespace core\modules\interview\models\interview_online;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'interview_online';
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
        $this->interviewers = $this->interview_db->getListInterviewer();
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('interview_online');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useFlatpickr();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('interviewers',$this->interviewers);

        return;
    }

}
?>
