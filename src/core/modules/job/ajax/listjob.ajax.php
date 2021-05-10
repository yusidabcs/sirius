<?php
namespace core\modules\job\ajax;

final class listjob extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('listjob');
        if ($_SESSION['user_id']){// restrict access only to logged in user
            if ($this->option){
                $this->job_db = new \core\modules\job\models\common\db;
                
                $type = $this->option; //check type of job that wanted to be accessed
                header('Content-Type: application/json; charset=utf-8');
                if ( $type == 'master' )
                {
                    return json_encode(
                        $this->job_db->getAllJobMaster()
                    );
                }else if ( $type == 'speedy' ){
                    return json_encode(
                        $this->job_db->getAllJobSpeedyDatatable()
                    );
                }else if ( $type == 'user' ){
                    return json_encode(
                        $this->job_db->getAllJobUser()
                    );
                }else if ( $type == 'search_job_master' ){

                    if(empty($this->page_options[1])){
                        $query = '';
                    }else{
                        $query = $this->page_options[1];
                    }

                    $job_master = $this->job_db->searchJobMaster($query);
                    return $this->response($job_master);
                }
                
            }
        }
    }

}
?>