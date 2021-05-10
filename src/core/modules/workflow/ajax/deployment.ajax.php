<?php
namespace core\modules\workflow\ajax;

final class deployment extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('deployment');
        $out = null;
        $this->deployment_db = new \core\modules\workflow\models\common\deployment();
        switch($this->option)
        {
            case 'list':

                $out = $this->deployment_db->getTrackerDatatableDeployment();
                break;
            case 'summary':
                $status_complete = array('accepted','rejected','docs_application_rejected','not_active','paid','cancelled');

                $status_visa =array('register_visa','docs_application','interview','upload_visa');
                $status_oktb=array('requirement_check','request_file','review_file');
                $status_stcw=array('request_file','review_file');
                $status_medical=array('request_appointment_date','request_file','review_file');
                $status_vaccination=array('request_appointment_date','request_file','review_file');
                $status_flight=array('request_file','review_file');
                $status_police=array('request_file','review_file');
                $status_seaman=array('request_file','review_file');
                $status_travelpack=array('request_invoice','generate_invoice','pay_invoice');

                $data = $_POST;

                $visa_status = $this->deployment_db->getStatusTracker('workflow_visa_tracker',$data['address_book_id']);
                $visa_pos = $this->_getPositionStatus($visa_status,$status_visa,$status_complete);

                $oktb_status = $this->deployment_db->getStatusTracker('workflow_oktb_tracker',$data['address_book_id']);
                $oktb_pos = $this->_getPositionStatus($oktb_status,$status_oktb,$status_complete);

                $stcw_status = $this->deployment_db->getStatusTracker('workflow_stcw_tracker',$data['address_book_id']);
                $stcw_pos = $this->_getPositionStatus($stcw_status,$status_stcw,$status_complete);

                $medical_status = $this->deployment_db->getStatusTracker('workflow_medical_tracker',$data['address_book_id']);
                $medical_pos = $this->_getPositionStatus($medical_status,$status_medical,$status_complete);

                $vaccination_status = $this->deployment_db->getStatusTracker('workflow_vaccination_tracker',$data['address_book_id']);
                $vaccination_pos = $this->_getPositionStatus($vaccination_status,$status_vaccination,$status_complete);

                $flight_status = $this->deployment_db->getStatusTracker('workflow_flight_tracker',$data['address_book_id']);
                $flight_pos = $this->_getPositionStatus($flight_status,$status_flight,$status_complete);

                $police_status = $this->deployment_db->getStatusTracker('workflow_police_tracker',$data['address_book_id']);
                $police_pos = $this->_getPositionStatus($police_status,$status_police,$status_complete);

                $seaman_status = $this->deployment_db->getStatusTracker('workflow_seaman_tracker',$data['address_book_id']);
                $seaman_pos = $this->_getPositionStatus($seaman_status,$status_seaman,$status_complete);

                $travelpack_status = $this->deployment_db->getStatusTracker('workflow_travelpack_tracker',$data['job_application_id'],'job_application_id');
                $travelpack_pos = $this->_getPositionStatus($travelpack_status,$status_travelpack,$status_complete);

                $out = [
                    'visa' => array(
                            'status' => $visa_status,
                            'percentage' => $visa_pos
                        ),
                    'oktb' => array(
                            'status' => $oktb_status,
                            'percentage' => $oktb_pos
                        ),
                    'stcw' => array(
                            'status' => $stcw_status,
                            'percentage' => $stcw_pos
                        ),
                    'medical' => array(
                            'status' => $medical_status,
                            'percentage' => $medical_pos
                        ),
                    'vaccination' => array(
                            'status' => $vaccination_status,
                            'percentage' => $vaccination_pos
                        ),
                    'flight' => array(
                            'status' => $flight_status,
                            'percentage' => $flight_pos
                        ),
                    'police' => array(
                            'status' => $police_status,
                            'percentage' => $police_pos
                        ),
                    'seaman' => array(
                            'status' => $seaman_status,
                            'percentage' => $seaman_pos
                        ),
                    'travelpack' => array(
                            'status' => $travelpack_status,
                            'percentage' => $travelpack_pos
                        )

                ];
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

    private function _getPositionStatus($status,$arr_status,$arr_complete) {
        if(array_search($status,$arr_status)!==false) {
            $pos = array_search($status,$arr_status) + 1;
            $max_value = count($arr_status)+1;
            $pos = round(($pos/$max_value),2)*100;
            $pos = $pos.'%';
        } else {
            if(array_search($status,$arr_complete)!==false) {
                $pos = '100%';
            } else {
                $pos = '0%';
            }
        }

        return $pos;
    }

}
?>