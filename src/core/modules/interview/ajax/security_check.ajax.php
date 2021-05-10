<?php
namespace core\modules\interview\ajax;

final class security_check extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('security_check');
        if ($this->option)
        {

            $this->interview_db = new \core\modules\interview\models\common\db();
            $this->interview_common = new \core\modules\interview\models\common\common();
            $type = $this->option;

            if ( $type == 'list' ){

                $result = $this->interview_db->getInterviewSecurityCheckDatatable();

                return $this->response($result);

            }

            else if ( $type == 'send_request_file' ){

                $job_application_id = $_POST['job_application_id'];
                foreach ($job_application_id as $index => $id){
                    $result = $this->interview_common->sendRequestFile($id);
                    if($result){
                        $this->interview_db->updateInterviewSecurityCheckRequestFile($id);
                    }
                }

                return $this->response([
                    'message' => 'Successfulle send request file to candidate.'
                ]);

            }
            else if ( $type == 'upload_passport_file' ){

                $data = $_POST;
                //check applicant data
                $rule = [
                    'job_application_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                $job_application_id = $_POST['job_application_id'];
                $job_db = new \core\modules\job\models\common\db();
                $job_application = $job_db->getJobApplication($job_application_id);
                $interview_security_check = $this->interview_db->getInterviewSecurityCheck($job_application_id);


                $data = file_get_contents($_FILES['passport_file']['tmp_name']);
                //address_book_common
                $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
                $secure_db = new \core\modules\interview\models\common\secure_file();

                if($interview_security_check['passport_file'] != '')
                {
                    $filename = $interview_security_check['passport_file'];
                    //delete the current passport image
                    $address_book_common->deleteAddressBookFile($filename,$job_application['address_book_id']);
                    $affected_rows = $secure_db->deleteSecureFile($filename);

                    if($affected_rows != 1)
                    {
                        $msg = "There was a major issue with addInfo in passport for address id {$job_application['address_book_id']}. Affected was {$affected_rows}";
                        throw new \RuntimeException($msg);
                    }

                }

                $filename = $address_book_common->storeAddressBookFileData($data,$job_application['address_book_id'],true);

                //set link to address book db because they all need it to add, modify and delete
                $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

                //insert also saves the image in the address book folder
                $affected_rows = $address_book_db->insertAddressBookFile($filename,$job_application['address_book_id'],'passport',0,$job_application_id);

                if($affected_rows != 1)
                {

                    $msg = "There was a major issue with addInfo in passport for address id {$job_application_id}. Affected was {$affected_rows}";
                    throw new \RuntimeException($msg);
                }

                $this->interview_db->updateInterviewSecurityCheckRequestedFile($job_application_id,$filename);

                $hash = md5($filename.date('Y-m-d H:i:s'));
                //insert hash
                if($secure_db->checkDuplicateSecureFileHash($hash)){
                    $hash = md5($filename.date('Y-m-d H:i:s'));
                }
                $secure_db->insertSecureFile([
                   'hash' => $hash,
                   'file_id' => $filename,
                    'type' => 'ab'
                ]);

                return $this->response([]);

            }
            else if ( $type == 'send_request_clearance' ){

                $job_application_id = $_POST['job_application_id'];
                $result = $this->interview_common->sendRequestClearance($job_application_id);

                return $this->response([
                    'message' => 'Successfull send request security clearance to principal.'
                ]);

            }
            else if ( $type == 'upload_clearance_file' ){

                $data = $_POST;

                //check applicant data
                $rule = [
                    'job_application_id' => 'required',
                    'status' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }

                if(empty($_FILES['clearance_file'])){
                    return $this->response(['Empty clearance file'], 400);
                }

                $this->_updateClearanceFile($data);

                return $this->response([
                    'message' => 'Successfully update status'
                ]);

            }
        }
    }

    private function _updateClearanceFile($data){
        $link_id = 'interview';
        $type = '';

        $content_id = 'clearance';
        $title = 'No Title';
        $sdesc = 'No Description';
        $security_level_id = 'NONE';
        $group_id = 'ALL';
        $status = isset($_POST['status']) ? $_POST['status'] : 0;

        $fileUpload_a['name'] = $_FILES['clearance_file']['name'];
        $fileUpload_a['type'] = $_FILES['clearance_file']['type'];
        $fileUpload_a['tmp_name'] = $_FILES['clearance_file']['tmp_name'];
        $fileUpload_a['error'] = $_FILES['clearance_file']['error'];
        $fileUpload_a['size'] = $_FILES['clearance_file']['size'];

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
            $this->interview_db->updateInterviewSecurityCheckClearanceFileStatus($job_application_id,$fm['file_obj']['file_manager_id'], $data['status']);
            if($data['status'] == 'accepted'){
                //update job application to accepted
                $job_db = new \core\modules\job\models\common\db();
                $job_db->updateJobApplicationStatus($job_application_id, 'hired');
            }
            else if($data['status'] == 'denied'){
                //update job application to accepted
                $job_db = new \core\modules\job\models\common\db();
                $job_db->updateJobApplicationStatus($job_application_id, 'not_hired');
            }
        }
    }

}
?>