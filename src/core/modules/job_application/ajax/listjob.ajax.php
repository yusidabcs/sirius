<?php
namespace core\modules\job_application\ajax;

final class listjob extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('listjob');
        if ($_SESSION['user_id']){// restrict access only to logged in user
            if ($this->option){
                $this->job_db = new \core\modules\job\models\common\db;
                $type = $this->option;
                if ( $type == 'availablejob' ){
                    return $this->response(200, $this->job_db->getJobSpeedyWithDemandDatatable());
                }
                
            }
        }
    }

}
?>