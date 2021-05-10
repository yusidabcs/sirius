<?php

namespace core\modules\offer_letter\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends        module_ajax
 * @package    offer_letter
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 18 May 2020
 */
final class trackers extends \core\app\classes\module_base\module_ajax
{

    protected $optionRequired = true;

    public function run()
    {
        $out = null;
        $this->offer_letter_db = new \core\modules\offer_letter\models\common\db();
        $this->offer_letter_common = new \core\modules\offer_letter\models\common\common();
        $this->job_db = new \core\modules\job\models\common\db();
        switch ($this->option) {
            case 'list':
                $out = $this->offer_letter_db->getOfferLetterTrackerDatatable();
                return $this->response($out);
                break;

            case 'insert':
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                    'level' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->offer_letter_db->insertReports($data);
                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully insert offer letter report.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert offer letter report.']
                ], 400);
                break;

            case 'send_endorsement':
                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $this->offer_letter_common->sendEndorsement($data['job_application_id']);
                break;

            case 'update_endorsement':
                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                    'status' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                if (empty($_FILES['endorsement_file'])) {
                    return $this->response([
                        'errors' => [
                            'File is empty.'
                        ]
                    ], 400);
                }

                $this->_uploadEndorsement($data);
                break;

            case 'request_offer_letter':

                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                    'request_offer_letter_on' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                foreach ($data['job_application_id'] as $job_application_id) {
                    $offer_letter_tracker = $this->offer_letter_db->getOfferLetterTrackerStatus($job_application_id);

                    if ($offer_letter_tracker['status'] === 'offer_letter' && $offer_letter_tracker['level'] === 'deadline') {
                        $this->offer_letter_common->sendOfferLetterReminder($job_application_id);
                    }

                    $this->offer_letter_db->updateTrackerRequestOfferLetter($job_application_id, $data['request_offer_letter_on']);
                }
                return $this->response([
                    'message' => 'Successfully update request offer letter.'
                ]);
                break;

            case 'upload_offer_letter':
                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                if (empty($_FILES['offer_letter_file'])) {
                    return $this->response([
                        'errors' => [
                            'File is empty.'
                        ]
                    ], 400);
                }
                $this->tracker = $this->offer_letter_db->getOfferLetterTracker($data['job_application_id']);
                $this->_uploadOfferLetter($data);
                return $this->response([
                    'message' => 'Successfully upload offer letter.'
                ]);

                break;

            case 'offer_letter_acceptance':
                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                    'candidate_accepted_on' => 'required',
                    'status' => 'required',
                    'notes' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->offer_letter_db->updateTrackerAcceptanceOfferLetter($data);
                if ($rs > 0) {
                    if($data['status'] == 'denied'){
                        //update job application to not hired
                        $job_db = new \core\modules\job\models\common\db();
                        $job_db->updateJobApplicationStatus($data['job_application_id'], 'not_hired');
                    }
                    return $this->response([
                        'message' => 'Successfully update offer letter acceptance.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update offer letter acceptance.']
                ], 400);
                break;

            case 'personal_data':
                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                if (empty($_FILES['personal_data_file'])) {
                    return $this->response([
                        'errors' => [
                            'Personal data file is empty.'
                        ]
                    ], 400);
                }
                $this->tracker = $this->offer_letter_db->getOfferLetterTracker($data['job_application_id']);
                $this->_uploadPersonalData($data);

                return $this->response([
                    'message' => 'Successfully upload personal data.'
                ]);

                break;

            case 'loe':
                
                if (!$this->system_register->getModuleIsInstalled('workflow')) {
                    return $this->response([
                        'message' => 'Workflow module not installed'
                    ], 500);
                }

                $data = $_POST;
                $rule = [
                    'job_application_id' => 'required',
                    'job_demand_master_id' => 'required',
                    'loe_date' => 'required',
                    'deploy_date' => 'required',
                    'deploy_date_end' => 'required'
                ];

                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                $workflow_db = new \core\modules\workflow\models\common\db;
                $job_db = new \core\modules\job\models\common\db;

                $job_application = $job_db->getJobApplication($data['job_application_id']);

                if (empty($_FILES['loe_file'])) {
                    return $this->response([
                        'errors' => [
                            'Loe data file is empty.'
                        ]
                    ], 400);
                }
                $this->tracker = $this->offer_letter_db->getOfferLetterTracker($data['job_application_id']);
                $deploy_master = $workflow_db->putDeploymentMaster($job_application['address_book_id'], $data['job_demand_master_id'], $data['loe_date'], $data['deploy_date'], $data['deploy_date_end']);
                
                if ($deploy_master) {
                    $this->deployment_master = $workflow_db->getDeploymentMaster($job_application['address_book_id'], $data['job_demand_master_id']);
                    $filename = $this->_uploadLoe($data);

                    $workflow_db->updateLoeFile($job_application['address_book_id'], $data['job_demand_master_id'], $filename);
                }

                $this->_triggerTracker($data);
                $this->offer_letter_db->updateTrackerToDeployment($data['job_application_id'], 'deployment');

                return $this->response([
                    'message' => 'Successfully upload LOE file'
                ]);
            break;

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);
                break;
        }

    }

    private function _uploadOfferLetter($data)
    {
        $file = file_get_contents($_FILES['offer_letter_file']['tmp_name']);
        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
        $secure_db = new \core\modules\interview\models\common\secure_file();
        $job_application = $this->job_db->getJobApplication($data['job_application_id']);
        if ($this->tracker['offer_letter_file'] != '') {
            $filename = $this->tracker['offer_letter_file'];
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($filename, $job_application['address_book_id']);
            $affected_rows = $secure_db->deleteSecureFile($filename);

            if ($affected_rows != 1) {
                $msg = "There was a major issue with addInfo in passport for address id {$job_application['address_book_id']}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        $filename = $address_book_common->storeAddressBookFileData($file, $job_application['address_book_id'], true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        //insert also saves the image in the address book folder
        $affected_rows = $address_book_db->insertAddressBookFile($filename, $job_application['address_book_id'], 'offer_letter', 0, $job_application['job_application_id']);

        if ($affected_rows != 1) {

            $msg = "There was a major issue with addInfo in passport for address id {$job_application_id}. Affected was {$affected_rows}";
            throw new \RuntimeException($msg);
        }

        $this->offer_letter_db->updateTrackerOfferLetterFile([
            'offer_letter_file' => $filename,
            'job_application_id' => $data['job_application_id'],
        ]);

        $hash = md5($filename . date('Y-m-d H:i:s'));
        //insert hash
        if ($secure_db->checkDuplicateSecureFileHash($hash)) {
            $hash = md5($filename . date('Y-m-d H:i:s'));
        }
        $secure_db->insertSecureFile([
            'hash' => $hash,
            'file_id' => $filename,
            'type' => 'ab'
        ]);
    }

    private function _uploadLoe($data)
    {
        $file = file_get_contents($_FILES['loe_file']['tmp_name']);
        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
        $secure_db = new \core\modules\interview\models\common\secure_file();
        $job_application = $this->job_db->getJobApplication($data['job_application_id']);
        if ($this->deployment_master['loe_file'] != '') {
            $filename = $this->deployment_master['loe_file'];
            //delete the current passport image
            $address_book_common->deleteAddressBookFile($filename, $job_application['address_book_id']);
            $affected_rows = $secure_db->deleteSecureFile($filename);

            if ($affected_rows != 1) {
                $msg = "There was a major issue with addInfo in passport for address id {$job_application['address_book_id']}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        $filename = $address_book_common->storeAddressBookFileData($file, $job_application['address_book_id'], true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        //insert also saves the image in the address book folder
        $affected_rows = $address_book_db->insertAddressBookFile($filename, $job_application['address_book_id'], 'loe', 0, $job_application['job_application_id']);

        if ($affected_rows != 1) {

            $msg = "There was a major issue with addInfo in passport for address id {$job_application_id}. Affected was {$affected_rows}";
            throw new \RuntimeException($msg);
        }

        $hash = md5($filename . date('Y-m-d H:i:s'));
        //insert hash
        if ($secure_db->checkDuplicateSecureFileHash($hash)) {
            $hash = md5($filename . date('Y-m-d H:i:s'));
        }
        $secure_db->insertSecureFile([
            'hash' => $hash,
            'file_id' => $filename,
            'type' => 'ab'
        ]);

        return $filename;
    }

    private function _uploadPersonalData($data)
    {
        $file = file_get_contents($_FILES['personal_data_file']['tmp_name']);
        //address_book_common
        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
        $secure_db = new \core\modules\interview\models\common\secure_file();
        $job_application = $this->job_db->getJobApplication($data['job_application_id']);
        if ($this->tracker['personal_data_file'] != '') {
            $filename = $this->tracker['personal_data_file'];
            //delete the current passport image
            $affected_rows = $address_book_common->deleteAddressBookFile($filename, $job_application['address_book_id']);
            if ($affected_rows != 1) {
                $msg = "There was a major issue with addInfo in personal_data_file for address id {$job_application['address_book_id']}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }

        $filename = $address_book_common->storeAddressBookFileData($file, $job_application['address_book_id'], true);

        //set link to address book db because they all need it to add, modify and delete
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        //insert also saves the image in the address book folder
        $address_book_db->insertAddressBookFile($filename, $job_application['address_book_id'], 'personal_data', 0, $job_application['job_application_id']);

        $rs = $this->offer_letter_db->updateTrackerPersonalData([
            'personal_data_file' => $filename,
            'job_application_id' => $data['job_application_id'],
        ]);

        if($rs){
            //check if need invoice
            $ps = $this->job_db->getJobPremiumServiceByABId($job_application['address_book_id']);
            if($ps && $ps['verified'] == 'accepted' && $ps['status'] == 'confirmed'){
                $finance_db = new \core\modules\finance\models\common\db();
                $finance_db->insertFinancePSFTracker($job_application['job_application_id']);
            }

        }

        $hash = md5($filename . date('Y-m-d H:i:s'));
        //insert hash
        if ($secure_db->checkDuplicateSecureFileHash($hash)) {
            $hash = md5($filename . date('Y-m-d H:i:s'));
        }
        $secure_db->insertSecureFile([
            'hash' => $hash,
            'file_id' => $filename,
            'type' => 'ab'
        ]);
    }

    private function _uploadEndorsement($data){
        $link_id = 'offer_letter';
        $type = '';

        $content_id = 'endorsement_file';
        $title = 'No Title';
        $sdesc = 'No Description';
        $security_level_id = 'NONE';
        $group_id = 'ALL';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;

        $fileUpload_a['name'] = $_FILES['endorsement_file']['name'];
        $fileUpload_a['type'] = $_FILES['endorsement_file']['type'];
        $fileUpload_a['tmp_name'] = $_FILES['endorsement_file']['tmp_name'];
        $fileUpload_a['error'] = $_FILES['endorsement_file']['error'];
        $fileUpload_a['size'] = $_FILES['endorsement_file']['size'];

        //should be good to go with uploading
        $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
        $file_manager = $file_manager_ns::getInstance();

        //get latest sequence
        $sequence = $file_manager->file_manager_db->getLatestSequence($link_id,$content_id,$type);

        $file_manager->setLinkInfo($link_id,$content_id,$type);
        $fm = $file_manager->addFiles($title, $sdesc, $sequence, $security_level_id, $group_id, $status, $fileUpload_a, false);
        $out = [];
        $out['response'] = 'ok';
        $out['data'] = $fm['file_obj'];

        //insert secure file
        $secure_db = new \core\modules\interview\models\common\secure_file();
        $hash = md5($fm['file_obj']['file_manager_id'].date('Y-m-d H:i:s'));
        //insert hash
        if($secure_db->checkDuplicateSecureFileHash($hash)){
            $hash = md5($fm['file_obj']['file_manager_id'].date('Y-m-d H:i:s'));
        }
        $secure_db->insertSecureFile([
            'hash' => $hash,
            'file_id' => $fm['file_obj']['file_manager_id'],
            'type' => 'fm'
        ]);

        foreach ($data['job_application_id'] as $job_application_id){

            if($data['status'] == 'accepted'){
                //update of tracker to offer_letter
                $rs = $this->offer_letter_db->updateEndorsementFileStatus($job_application_id,$fm['file_obj']['file_manager_id'], 'offer_letter');
            }
            else if($data['status'] == 'denied'){
                $this->offer_letter_db->updateEndorsementFileStatus($job_application_id,$fm['file_obj']['file_manager_id'], 'denied');
                //update job application to accepted
                $job_db = new \core\modules\job\models\common\db();
                $job_db->updateJobApplicationStatus($job_application_id, 'not_hired');
            }
        }
    }

    private function _triggerTracker($data)
    {
        $workflow_common_db = new \core\modules\workflow\models\common\common;
        $job_db = new \core\modules\job\models\common\db;

        $job_application = $job_db->getJobApplication($data['job_application_id']);
        
        $workflow_common_db->triggerMasterWorkflow('address_book_id', $job_application['address_book_id'], '', 'deployment', array(
            'oktb_types' => $data['oktb_types'], 
            'visa_types' => $data['visa_types'], 
            'stcw_types' => $data['stcw_types'], 
            'medical_types' => $data['medical_types'], 
            'vaccine_types' => $data['vaccine_types'], 
            'job_application_id' => $data['job_application_id'],
            'deploy_date' => $data['deploy_date']
        ));

    }

}

?>