<?php

namespace core\modules\interview\ajax;

/**
 * Final default class.
 *
 * @final
 * @package    interview
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright    Martin O'Dee 22 August 2019
 */
final class schedule extends \core\app\classes\module_base\module_ajax
{

    protected $optionRequired = true;
    private $_form_check; //the obj for checking menu fields

    public function run()
    {
        $this->authorizeAjax('schedule');
        //we are going to need
        //$interview_db = NS_MODULES.'\\menu\\models\\common\\form_check';
        $interview_db = new \core\modules\interview\models\common\db();


        switch ($this->option) {
            case 'insert':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'organizer_id' => 'required',
                    'interviewer_id' => 'required',
                    'start_on' => 'required',
                    'finish_on' => 'required',
                    'countryCode_id' => 'required',
                    'countrySubCode_id' => 'required',
                    'address' => 'required',
                    'google_map' => 'required',
                    'status' => 'required',
                ];
                $data['start_on'] = date('Y-m-d H:i:s', strtotime($data['start_on']));
                $data['finish_on'] = date('Y-m-d H:i:s', strtotime($data['finish_on']));
                //check if all data is exist but not it's validity
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $interview_db->insertInterviewSchedule($data);
                if($rs){
                    return $this->response([
                       'message' => 'Successfully insert data'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully insert data'
                    ]);
                }
                break;
            case 'update':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'schedule_id' => 'required',
                    'organizer_id' => 'required',
                    'interviewer_id' => 'required',
                    'start_on' => 'required',
                    'finish_on' => 'required',
                    'countryCode_id' => 'required',
                    'countrySubCode_id' => 'required',
                    'address' => 'required',
                    'google_map' => 'required',
                    'status' => 'required',
                ];
                $data['start_on'] = date('Y-m-d H:i:s', strtotime($data['start_on']));
                $data['finish_on'] = date('Y-m-d H:i:s', strtotime($data['finish_on']));
                //check if all data is exist but not it's validity
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                $rs = $interview_db->updateInterviewSchedule($data);

                if($rs){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }

                break;
            case 'delete':

                $id = $this->page_options[1];
                $rs = $interview_db->deleteInterviewSchedule($id);

                if($rs){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }
                break;
            case 'online_list':
                if($this->useEntity) {
                    $rs = $interview_db->getOnlineInterviewSchedule($this->entity['address_book_ent_id']);
                } else {
                    $rs = $interview_db->getOnlineInterviewSchedule();
                }
                return $this->response($rs);

                break;
            case 'set_schedule':
                if (!$this->system_register->getModuleIsInstalled('workflow')) {
                    return $this->response([
                        'message' => 'Workflow module not installed, Please contact admin support!'
                    ], 500);
                }
                $workflow_db = new \core\modules\workflow\models\common\db;
                $job_db = new \core\modules\job\models\common\db();

                $data = $_POST;
                //check applicant data
                $rule = [
                    'job_application_id' => 'required',
                    'type' => 'required'
                ];
                if($data['type'] == 'physical'){
                    $rule['interview_location_id'] = 'required';
                }
                if($data['type'] == 'online'){
                    $rule['schedule_on'] = 'required';
                    $rule['timezone'] = 'required';
                }
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $job_application = $job_db->getJobApplication($data['job_application_id']);
                

                if ($workflow_db->getActiveWorkflow('workflow_interview_ready_tracker', 'address_book_id', $job_application['address_book_id'])) {
                    # code...
                        $tracker = $workflow_db->updateTrackers('workflow_interview_ready_tracker', $job_application['address_book_id'], [
                            'accepted_on' => date('Y-m-d H:i:s'),
                            'accepted_by' => $_SESSION['user_id'],
                            'notes' => 'Interview schedule has been set, ready for interview',
                            'status' => 'accepted'
                        ]);
                }

                
                $rs = $interview_db->insertInterviewScheduleByType($data);
                
                $interview_data = $interview_db->getLatestInterviewSchedule($data['job_application_id']);
                
                $this->generic = \core\app\classes\generic\generic::getInstance();
                $to_name = $this->generic->getName('per', $interview_data['entity_family_name'], $interview_data['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $lp_name = $this->generic->getName('ent', $interview_data['lp_entity_family_name'], $interview_data['lp_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $site_a = @parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

                $interview_db->sendInterviewNotificationEmail($interview_data, $to_name, $interview_data['candidate_email'], $site_a['SITE_EMAIL_NAME'], $site_a['SITE_EMAIL_ADD'],$lp_name);

                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }

                break;
            case 'update_physical_schedule':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'schedule_id' => 'required',
                    'interview_location_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $interview_db->updatePhysicalInterview($data);

                $data_schedule = $interview_db->getInterviewScheduleById($data['schedule_id']);
                $interview_data = $interview_db->getLatestInterviewSchedule($data_schedule['job_application_id']);

                $this->generic = \core\app\classes\generic\generic::getInstance();
                $to_name = $this->generic->getName('per', $interview_data['entity_family_name'], $interview_data['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $lp_name = $this->generic->getName('ent', $interview_data['lp_entity_family_name'], $interview_data['lp_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $site_a = @parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

                $interview_db->sendInterviewNotificationEmail($interview_data, $to_name, $interview_data['candidate_email'], $site_a['SITE_EMAIL_NAME'], $site_a['SITE_EMAIL_ADD'],$lp_name,'change');

                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }

                break;
            case 'update_online_schedule':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'schedule_id' => 'required',
                    'schedule_on' => 'required',
                    'timezone' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $interview_db->updateOnlineInterview($data);

                $data_schedule = $interview_db->getInterviewScheduleById($data['schedule_id']);
                $interview_data = $interview_db->getLatestInterviewSchedule($data_schedule['job_application_id']);

                $this->generic = \core\app\classes\generic\generic::getInstance();
                $to_name = $this->generic->getName('per', $interview_data['entity_family_name'], $interview_data['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $lp_name = $this->generic->getName('ent', $interview_data['lp_entity_family_name'], $interview_data['lp_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $site_a = @parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

                $interview_db->sendInterviewNotificationEmail($interview_data, $to_name, $interview_data['candidate_email'], $site_a['SITE_EMAIL_NAME'], $site_a['SITE_EMAIL_ADD'],$lp_name,'change');
                
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }

                break;

            case 'set_interviewer':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'address_book_id' => 'required',
                    'schedule_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                foreach ($data['schedule_id'] as $schedule_id){
                    $rs = $interview_db->setInterviewerForSchedule([
                        'address_book_id' => $data['address_book_id'],
                        'schedule_id' => $schedule_id,
                    ]);
                }

                if($rs >= 0){
                    return $this->response([
                        'message' => 'Successfully update data'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully update data']
                    ],400);
                }

                break;

            case 'remove_schedule':
                $data = $_POST;
                //check applicant data
                $rule = [
                    'schedule_id' => 'required',
                    'address_book_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $data_schedule = $interview_db->getInterviewScheduleById($data['schedule_id']);
                $interview_data = $interview_db->getLatestInterviewSchedule($data_schedule['job_application_id']);

                $this->generic = \core\app\classes\generic\generic::getInstance();
                $to_name = $this->generic->getName('per', $interview_data['entity_family_name'], $interview_data['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $lp_name = $this->generic->getName('ent', $interview_data['lp_entity_family_name'], $interview_data['lp_number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

                $site_a = @parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

                $interview_db->sendInterviewNotificationEmail($interview_data, $to_name, $interview_data['candidate_email'], $site_a['SITE_EMAIL_NAME'], $site_a['SITE_EMAIL_ADD'],$lp_name,'removed');

                $rs = $interview_db->removeInterviewSchedule($data);

                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully remove schedule'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully remove schedule']
                    ],400);
                }

                break;

            case 'job_application':
                $job_application_id = $this->page_options[1];
                $interview_group = $interview_db->getScheduleByJobApplication($job_application_id);
                $interview_result = $interview_db->getIntreviewResult($job_application_id);

                return $this->response([
                    'interview_schedule' => $interview_group,
                    'interview_result' => $interview_result
                ]);
                break;
            case 'list_candidate':

                if(empty($this->page_options[1])){
                    return $this->response('No location id',500);
                }
                $location_id = $this->page_options[1];

                $rs = $interview_db->getListCandidateInGroup($location_id);
                return $this->response($rs);

                break;
            case 'allocation':

                $data = $_POST;
                //check applicant data
                $rule = [
                    'job_demand_master_id' => 'required',
                    'job_application_id' => 'required',
                    'address_book_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $job_db = new \core\modules\job\models\common\db();

                foreach ($data['address_book_id'] as $index => $item){
                    $rs = $job_db->addDemandAllocation([
                        'job_demand_master_id' => $data['job_demand_master_id'],
                        'address_book_id' => $data['address_book_id']
                    ]);
                    if($rs > 0){
                        $job_application_id = $data['job_application_id'][$index];
                        $job_db->allocatedJobApplication($job_application_id);
                    }
                }


                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully add job allocation'
                    ]);
                }else{
                    return $this->response([
                        'errors' => ['Unsuccessfully add job allocation']
                    ],400);
                }

                break;

        }


    }

}

?>