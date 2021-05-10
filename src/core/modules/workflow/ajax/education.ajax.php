<?php
namespace core\modules\workflow\ajax;

final class education extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('education');
        $out = null;
        $this->db = new \core\modules\workflow\models\common\education_db();
        switch($this->option)
        {
            case 'list-education':

                $out = $this->db->getTrackerDatatable();
                break;
            case 'list-dashbord':
                if($this->useEntity) {
                    $out = $this->db->getTrackerDatatableDashboard($this->entity['address_book_ent_id']);
                } else {
                    $out = $this->db->getTrackerDatatableDashboard();
                }
                
                break;
            case 'count-tracker-dashborad' :
                $data = $_POST;
                if($this->useEntity) {
                    $out = $this->db->getCountTrackerEducation($data,$this->entity['address_book_ent_id']);
                } else {
                    $out = $this->db->getCountTrackerEducation($data);
                }
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