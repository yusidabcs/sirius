<?php
namespace core\modules\job\ajax;

final class premium extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('premium');
        $workflow_db = new \core\modules\workflow\models\common\db();

        if ($this->option)
        {
            $this->job_db = new \core\modules\job\models\common\db;
            $job_application_db = new \core\modules\workflow\models\common\jobapplication_db();

            $type = $this->option;

            if ( $type == 'update' )
            {
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required|int',
                    'status' => 'required'
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);
                
                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                
                $affected_rows = $this->job_db->updateJobPremiumServiceStatus($data);

                if($affected_rows != -1)
                {
                    $out['message'] = 'Update premium status success';
                }else{
                    $out['message'] = 'Problem in update premium status.';
                    return $this->response($out,500);
                }

            } 
            elseif ( $type == 'send_email' )
            {
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required|int',
                    'type' => 'required',
                    'full_amount' => 'required',
                    'amount' => 'required',
                ];

                $validator = new \core\app\classes\validator\validator($data, $rule);
                
                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $hash = md5($data['address_book_id'].date('Y-m-d H:i:s'));

                //check if there is no status, or is already rejected (pending), 
                $data['status'] = (($data['status'] == null) || ($data['status'] == '') || ($data['status'] == 'pending'))? 'sending' : $data['status'];
                $data['hash'] = $hash;
                $data['full_amount'] = $data['full_amount'] * 100;
                $data['amount'] = $data['amount'] * 100;

                if (!$this->system_register->getModuleIsInstalled('workflow')) {
                    return $this->response([
                        'message' => 'Workflow module not installed!',
                    ]);
                }

                if ($workflow_db->getActiveWorkflow('workflow_premium_service_tracker', 'address_book_id', $data['address_book_id'])) {
                    $workflow = $workflow_db->updateTrackers('workflow_premium_service_tracker', $data['address_book_id'], [
                        'request_psf_on' => date('Y-m-d H:i:s'),
                        'request_psf_by' => $_SESSION['user_id'],
                        'level' => 1,
                        'status' => 'candidate_verification',
                        'notes' => 'Waiting verification from candidate'
                    ]);
                }


                //insert to table
                $premium_service = $this->job_db->getJobPremiumServiceByABId($data['address_book_id']);
                if($premium_service && $premium_service['verified'] == 'rejected'){
                    $affected_row = $this->job_db->updateJobPremiumService($data);
                }else{
                    $affected_row = $this->job_db->insertJobPremiumService($data);
                }
                
                if ($affected_row < 0)
                {
                    $out['message'] = 'Problem in insert job premium service.'.$affected_row;
                    return $this->response($out,500);
                }
                
                //send email
                $_system_ini_a = parse_ini_file(DIR_SECURE_INI.'/system_config.ini');
                if (isset($data['by_pass']) && isset($_system_ini_a['BYPASS_USER_PROCESS'])) {
                    if ($data['by_pass'] == 1 && $_system_ini_a['BYPASS_USER_PROCESS'] == 1) {
                        $this->_userAcceptancePremiumService($data['hash'], $data['user_acceptance']);
                        $this->_adminConfirmPremiumService($data['address_book_id']);
                    }
                    $out['message'] = 'Premium service complete';
                } else {

                    $jobapplication_common = new \core\modules\job_application\models\common\common;
                    $jobapplication_common->sendPremiumServiceEmail($data['address_book_id'],$hash,'verification',$data['type']);
                    $out['message'] = 'An email has been sent to the candidate\'s email, The candidate needs to accept / reject the premium service in 1x24 hours to continue the application.';
                }
                
            }
            elseif ( $type == 'confirm' ){
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required|int',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                if ($workflow_db->getActiveWorkflow('workflow_premium_service_tracker', 'address_book_id', $data['address_book_id'])) {
                    # code...
                    $workflow = $workflow_db->updateTrackers('workflow_premium_service_tracker', $data['address_book_id'], [
                        'psf_confirmed_on' => date('Y-m-d H:i:s'),
                        'psf_confirmed_by' => $_SESSION['user_id'],
                        'level' => 1,
                        'status' => 'accepted',
                        'notes' => 'Psf was accepted'
                    ]);
    
                    if ($workflow !== 1) {
                        $out['message'] = 'Problem in update tracker '.$workflow;
                        return $this->response($out, 500);
                    }
                }

                $affected_rows = $this->job_db->confirmPremiumService($data);

                //send email to candidate

                if($affected_rows != -1)
                {
                    $jobapplication_common = new \core\modules\job_application\models\common\common;
                    //generate PSF
                    $psf_file = $jobapplication_common->generatePSF($data['address_book_id']);
                    $jobapplication_common->sendPremiumServiceEmail($data['address_book_id'],false,'confirmed','early',$psf_file);

                    $out['message'] = 'Update premium status success';
                }else{
                    $out['message'] = 'Problem in update premium status.';
                    return $this->response($out,500);
                }
            }

            elseif ( $type == 'list' ){
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required|int',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $out = $this->job_db->getJobPremiumServiceByABId($data['address_book_id']);
            }

            return $this->response($out);
        }
    }

    private function _userAcceptancePremiumService($hash, $acceptance)
    {
        $this->job_db = new \core\modules\job\models\common\db();

        //check and get data by hash
        $check = $this->job_db->getJobPremiumServiceByHash($hash);
        
        if (empty($check))
        {
            $msg = 'No data found with hash '.$hash;
            throw new \RuntimeException($msg);
        }

        //check if not already confirmed
        if ($check['verified'] == 'unknown' || $check['verified'] == 'rejected')
        {
            if ($acceptance == 'accept'){
                $premium_verified = 'accepted';
            }elseif ($acceptance == 'reject'){
                $premium_verified = 'rejected';
            }
            $data = array(
                'verified' => $premium_verified,
                'hash' =>$hash,
            );

            $workflow_db = new \core\modules\workflow\models\common\db();
            $personal_db = new \core\modules\personal\models\common\db();

            $address_book = $personal_db->getAddressBook($check['address_book_id']);
            $user = $personal_db->getUserBy('email', $address_book['main_email']);

            // insert workflow
            $workflow = $workflow_db->updateTrackers('workflow_premium_service_tracker', $address_book['address_book_id'], [
                'psf_verified_on' => date('Y-m-d H:i:s'),
                'psf_verified_by' => $user['user_id'],
                'status' => 'confirm_psf',
                'notes' => 'psf has been confirmed by candidate, wating accept by administrator'
            ]);

            if ($workflow_db->getActiveWorkflow('workflow_premium_service_tracker', 'address_book_id', $address_book['address_book_id'])) {
                # code...
                if ($workflow !== 1) {
                    $msg = 'Problem in update tracker '.$workflow;
                    throw new \RuntimeException($msg);
                }
            }

            //update job premium service with inserted hash
            $affected_rows = $this->job_db->userConfirmJobPremiumService($data);
        }
    }

    private function _adminConfirmPremiumService($address_book_id)
    {
        $data['address_book_id'] = $address_book_id;
        $workflow_db = new \core\modules\workflow\models\common\db();
        if ($workflow_db->getActiveWorkflow('workflow_premium_service_tracker', 'address_book_id', $data['address_book_id'])) {
            # code...
            $workflow = $workflow_db->updateTrackers('workflow_premium_service_tracker', $data['address_book_id'], [
                'psf_confirmed_on' => date('Y-m-d H:i:s'),
                'psf_confirmed_by' => $_SESSION['user_id'],
                'status' => 'accepted',
                'notes' => 'Psf was accepted'
            ]);

            if ($workflow !== 1) {
                $out['message'] = 'Problem in update tracker '.$workflow;
                return $this->response($out, 500);
            }
        }

        $affected_rows = $this->job_db->confirmPremiumService($data);

        $jobapplication_common = new \core\modules\job_application\models\common\common;
        $jobapplication_common->generatePSF($data['address_book_id']);
    }

}
?>