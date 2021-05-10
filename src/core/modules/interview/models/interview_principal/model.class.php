<?php
namespace core\modules\interview\models\interview_principal;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'interview_principal';
    protected $processPost = false;

    public function __construct()
    {
        parent::__construct();
        $this->interview_db = new \core\modules\interview\models\common\db();
        $this->partner_db = new \core\modules\partner\models\common\db();
        $this->job_db = new \core\modules\job\models\common\db();
        return;
    }

    //required function
    protected function main()
    {
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('interview_principal');

        $this->interviewers = $this->interview_db->getListInterviewer();
        $this->partners = $this->partner_db->getPartnerArray();
        $this->principals = $this->partner_db->getPrincipalArray();
        $this->jobs = $this->job_db->getAllJobSpeedy();
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useFlatpickr();

        if($this->useEntity) {
            $data['principal_id'] = $this->entity['address_book_ent_id'];
            $filter_principal = false;
        } else {
            $filter_principal = true;
        }

        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('interviewers',$this->interviewers);
        $this->view_variables_obj->addViewVariables('partners',$this->partners);
        $this->view_variables_obj->addViewVariables('principals',$this->principals);
        $this->view_variables_obj->addViewVariables('jobs',$this->jobs);
        $this->view_variables_obj->addViewVariables('filter_principal',$filter_principal);
        return;
    }

}
?>
