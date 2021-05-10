<?php
namespace core\modules\workflow\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 15 Jun 2020
 */
final class recruitment extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $out = null;
        $this->db = new \core\modules\workflow\models\common\recruitment_db();
        $this->job_application_db = new \core\modules\workflow\models\common\jobapplication_db();
        switch($this->option)
        {
            case 'list':
                
                if($this->useEntity){
                    $out = $this->db->getRecruitmentTrackerDatatable($this->entity['address_book_ent_id']);
                }
                else{
                    $out = $this->db->getRecruitmentTrackerDatatable();
                }
                    
                

                break;
            case 'list-personal-verification':

                $out = $this->db->getTrackerDatatable();
                break;

            case 'total':

                $out = $this->job_application_db->getTotalTrackerByLevel('workflow_recruitment_tracker', ($this->entity ? $this->entity['address_book_ent_id'] : false));

                break;

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);
                break;
        }

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

}
?>