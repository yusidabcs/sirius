<?php
namespace core\modules\recruitment\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class applicant extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        $this->authorizeAjax('applicant');
        // print_r($this->page_options[1]);
        // exit(0);
        switch ($this->option){
            case 'pre-interview-checklist':
                return $this->_preInterviewChecklist();
                break;

            case 'accept':
                $job_db = new \core\modules\job\models\common\db();
                $out = $job_db->acceptJobApplication($this->page_options[1]);
                if($out){
                    return $this->response([
                        'message' => 'Successfully accept the applicants'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully accept the applicants'
                    ]);
                }
                break;

            case 'cancel':
                $job_db = new \core\modules\job\models\common\db();
                $out = $job_db->cancelJobApplication($this->page_options[1]);
                if($out){
                    return $this->response([
                        'message' => 'Successfully cancel the applicants'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully cancel the applicants'
                    ]);
                }
                break;
            case 'accept-interview':
                $job_db = new \core\modules\job\models\common\db();
                $interview_db = new \core\modules\interview\models\common\db();
                $job_application = $job_db->getJobApplication($this->page_options[1]);

                if (!$this->system_register->getModuleIsInstalled('workflow')) {
                    return $this->response([
                        'message' => 'Module workflow not installed, Please contact admin support!'
                    ], 500);
                }

                $workflow_db = new \core\modules\workflow\models\common\db;
                if (!$workflow_db->getActiveWorkflow('workflow_interview_ready_tracker', 'address_book_id', $job_application['address_book_id'])) {
                    $init_tracker = $workflow_db->insertInterviewTracker($job_application['address_book_id']);
                    
                    if ($init_tracker < 1) {
                        return $this->response(['message' => 'Cannot init tracker'], 500);
                    }
                }

                $interview_db->saveInterviewResultPrincipal([
                    "job_application_id" => $this->page_options[1],
                    "principal_code" => $_POST['principal_code'],
                ]);
                $this->_checkInterviewSecurity($job_application['address_book_id'],$_POST['principal_code']);
                $this->_triggerWorkflow($this->page_options[1], $_POST['principal_code']);

                $out = $job_db->acceptInterviewJobApplication($this->page_options[1]);
                if($out){
                    return $this->response([
                        'message' => 'Successfully accept interview the applicants'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully accept interview the applicants'
                    ]);
                }
                break;

            case 'reject':
                
                $job_db = new \core\modules\job\models\common\db();
                $out = $job_db->rejectJobApplication($this->page_options[1]);
                if($out){
                    return $this->response( [
                        'message' => 'Successfully reject the applicants'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully reject the applicants'
                    ]);
                }
                break;
    
            case 'ctrac':
                $data = $_POST;
                $job_application_id = $this->page_options[1];
                $data['job_application_id'] = $job_application_id;
                $job_db = new \core\modules\job\models\common\db();
                $out = $job_db->updateCtrac($data);
                if($out){
                    return $this->response([
                        'message' => 'Successfully update ctrac'
                    ]);
                }else{
                    return $this->response([
                        'message' => 'Unsuccessfully update ctrac'
                    ]);
                }
                break;
            case 'getctrac':
                $job_application_id = $this->page_options[1];
                $job_db = new \core\modules\job\models\common\db();
                $data = $job_db->getJobApplicationCTrack($job_application_id);
                return $this->response($data);
                break;

            case 'send_reminder_all_user' : 
                $data = [];
                $db = new \core\modules\recruitment\models\common\db;
                $reminder = 7; // 7 days
                $connection_id = 0;
                if($this->useEntity)
                    $data = $db->getCheckRecruitment($reminder,$this->entity['address_book_ent_id']);
                else
                    $data = $db->getCheckRecruitment($reminder);
                
                
                $total_send=0;
                
                foreach ($data as $key => $value) {
                    $address_book_id = $value['address_book_id'];
                    $verify = true;
                    //check personal general
                    $passport = 'yes';
                    $general_info = $db->checkPersonalDataUser($address_book_id,'personal_general');
                    if(empty($general_info)) {
                        $verify = false;
                    } else {
                        $passport = $general_info[0]['passport'];
                    }
                    //check id card
                    if(empty($db->checkPersonalDataUser($address_book_id,'personal_idcard'))) {
                        $verify = false;
                    }
                    //check character
                    if(empty($db->checkPersonalDataUser($address_book_id,'personal_checklist','character'))) {
                        $verify = false;
                    }
                    //check health
                    if(empty($db->checkPersonalDataUser($address_book_id,'personal_checklist','health'))) {
                        $verify = false;
                    }
                    //check passport
                    if($passport=='yes') {
                        if(empty($db->checkPersonalDataUser($address_book_id,'personal_passport'))) {
                            $verify = false;
                        }
                    }
                    //check language
                    if(empty($db->checkPersonalDataUser($address_book_id,'personal_language'))) {
                        $verify = false;
                    }
                    //check education
                    if(empty($db->checkPersonalDataUser($address_book_id,'personal_education'))) {
                        $verify = false;
                    }
                    //check personal reference
                    if(empty($db->checkPersonalDataUser($address_book_id,'personal_reference'))) {
                        $verify = false;
                    }

                    if(!$verify) {
                        //send email reminder
                        $total_send++;
                        $ab_db = new \core\modules\address_book\models\common\address_book_db_obj();
                        $ab = $ab_db->getAddressBookMainDetails($address_book_id);
                        $this->_sendEmailReminderToUser($ab);
                        
                    }

                }
                $response['status']='Email reminder has been send to '.$total_send.' user!';
                return $this->response($response);
            break;
            default:
                $db_ns = NS_MODULES.'\\job\\models\\common\\db';
                $db = new $db_ns();

                if($this->useEntity)
                    $data = $db->getAllJobApplicationsDatatable($this->entity['address_book_ent_id']);
                else
                    $data = $db->getAllJobApplicationsDatatable();

        		$interview_common = new \core\modules\recruitment\models\common\common();

                $generic_obj = \core\app\classes\generic\generic::getInstance();
                foreach ($data['data'] as $key => $item){
                    $check_data = $interview_common->checkPrescreenStatus($data['data'][$key]['job_application_id']);
                    //check if already answered
                    if(!empty($check_data))
                    {

                        $prescreen_data = $interview_common->getPrescreenInterviewData($data['data'][$key]['job_application_id']);

                        $data['data'][$key]['prescreen_data'] = $prescreen_data;
                        $data['data'][$key]['prescreen_valid'] = $check_data['valid'];
                        $data['data'][$key]['prescreen_need_review'] = $check_data['need_review'];
                    }
                   
                    $fullname = $generic_obj->getName('per', $item['entity_family_name'], $item['number_given_name'] . ' ' . $item['middle_names'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                    $data['data'][$key]['fullname'] = $item['title'].' '.$fullname;
                }
                return $this->response($data,200);
                break;

        }
    }

    private function _preInterviewChecklist(){
        $data = [];
        $valid = true;
        $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
        $this->menu_register = $menu_register_ns ::getInstance();

        $this->personal_db = new \core\modules\personal\models\common\db();
        $job_db = new \core\modules\job\models\common\db();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $cv_db = new \core\modules\cv\models\common\db;

        $applicant = $job_db->getJobApplication($this->page_options[1]);
        $ctrack = $job_db->getJobApplicationCTrack($this->page_options[1]);
        $address_book_id = $applicant['address_book_id'];
        $passports = $this->personal_db->getPassportList($address_book_id);
        $general = $this->personal_db->getGeneral($address_book_id);
        $avatar = $address_book_db->getAddressBookAvatarDetails($address_book_id);
        $internet = $address_book_db->getAddressBookInternetDetails($address_book_id);
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        // hash cv
        $hash_cv = $this->getHashCV($address_book_id,'template1');
        $link_cv = '';
        if($hash_cv!='') {
            $link_cv = '/'.$this->menu_register->getModuleLink('cv').'/share/'.$hash_cv;
        }
        $data['link_cv'] = $link_cv;
        // end hash cv

        //stcw document
        $stcw = $this->personal_db->getEducationSTCW($address_book_id);

        //job premium
        $premium = $job_db->getJobPremiumServiceByABId($applicant['address_book_id']);

        $checklists = $this->_getChecklistInfo($address_book_id);
        $englishs = $this->_getEnglishList($address_book_id);


        //personal reference
        $personal_reference = $this->personal_db->getLatestReferenceCheck($applicant['personal_reference_id']);
        if($personal_reference)
        {
            $personal_reference['link'] = '/'.$this->menu_register->getModuleLink('personal').'/reference_check/'.$personal_reference['reference_id'];
        }

        //work reference
        $work_reference = $this->personal_db->getLatestReferenceCheck($applicant['work_reference_id']);
        if($work_reference)
        {
            $work_reference['link'] = '/'.$this->menu_register->getModuleLink('personal').'/reference_check/'.$work_reference['reference_id'];
        }

        $data['avatar'] = null;
        if (!empty($avatar))
        {
            $file = DIR_LOCAL_UPLOADS.'/address_book/'.$address_book_id.'/'.$avatar[0]['filename'];
            //check avatar is exist
            if(file_exists($file))
            {
                $data['avatar'] = $avatar[0]['filename'];
            }
        }

        if (isset($general['filename']))
        {
            $file = DIR_LOCAL_UPLOADS.'/address_book/'.$address_book_id.'/'.$general['filename'];
            //check general image is not exist
            if(!file_exists($file))
            {
                $general['filename'] = null;
            }
        }else{
            $general['filename'] = null;

        }

        $data['english_test'] = $englishs;
        $data['full_body_photo'] = $general['filename'];
        $data['applicant'] = $applicant;
        $data['stcw'] = $stcw;
        $data['premium'] = $premium;
        $data['skype'] = null;
        $data['interview_valid'] = 0;

        //init all data inserted
        $data['passport_document'] = null;
        $data['passport_name'] = null;
        $data['passport_valid_date'] = null;
        $data['passport_valid'] = null;
        $data['applied_job'] = null;

        foreach ($internet as $item){
            if($item['type'] == 'skype'){
                $data['skype'] = $item['id'];
            }
        }

        $data['email'] = $address_book['main_email'];

        //ctrack
        $data['ctrack'] = null;
        $data['send_ctrack_on'] = null;
        $data['ctrack_accessed_on'] = null;
        $data['ctrack_completed_on'] = null;

        $data['checklists'] = null;
        $data['personal_reference'] = null;
        $data['work_reference'] = null;
        $data['passport'] = null;

        if(count($passports) > 0)
        {
            $latest_passport = reset($passports);
            $data['passport_document'] = $latest_passport['filename'];
            $data['passport_name'] = $latest_passport['full_name'];
            $data['passport_valid_date'] = date("d M Y",strtotime($latest_passport['to_date']));
            $to_date = new \DateTime($latest_passport['to_date']);
            $now = new \DateTime();
            $month_diff = $now->diff($to_date)->m + ($now->diff($to_date)->y*12);
            $data['passport_valid'] = $month_diff >= 13 ? true : false;
            $data['applied_job'] = $applicant['job_title'] .' ('.$applicant['job_speedy_code'].')';


            //ctrack
            if (count($ctrack) > 0)
            {
                $data['ctrack'] = 1;
                $data['send_ctrack_on'] = $ctrack['send_ctrac_on'];
                $data['ctrack_accessed_on'] = $ctrack['ctrac_accessed_on'];
                $data['ctrack_completed_on'] = $ctrack['ctrac_completed_on'];
            }else{
                $data['ctrack'] = 0;
            }

            $data['checklists'] = $checklists;
            $data['personal_reference'] = $personal_reference;
            $data['work_reference'] = $work_reference;
            $data['passport'] = 1;



        }else{
            $data['passport'] = 0;
        }

        //check applicant data
        $rule = [
            'passport_name' => 'required',
            'applied_job' => 'required',
            'email' => 'required',
            'checklists' => 'required',
            // 'send_ctrack_on' => 'required',
            // 'ctrack_accessed_on' => 'required',
            // 'ctrack_completed_on' => 'required',
            'passport_document' => 'required',
            'full_body_photo' => 'required',
            'avatar' => 'required',
            'personal_reference' => 'required|min:1',
            'work_reference' => $applicant['work_reference_id'] == 0 ? '' : 'required|min:1',
            'premium' => 'required',
            //'english_test' => 'required|min:1'
        ];

        //check if all data is exist but not it's validity
        $validator = new \core\app\classes\validator\validator($data, $rule);
        $data['errors'] = null;
        if($validator->hasErrors())
        {
            $data['errors'] = $validator->getValidationErrors()['errors'];
        }else{
            $data['interview_valid'] = 1;
        }

        //checklist validation
        if(empty($data['checklists']['character']) || ($data['checklists']['character']['result'] != 'All Good' && $data['checklists']['character']['result'] != 'Review')){
            $data['errors']['checklist_character'] = 'Checklist character need to be completed.';
        }
        if(empty($data['checklists']['health']) || ($data['checklists']['health']['result'] != 'All Good' && $data['checklists']['health']['result'] != 'Review')){
            $data['errors']['checklist_health'] = 'Checklist health need to be completed.';
        }

        if($personal_reference && $personal_reference['status'] != 'confirmed'){
            $data['errors']['personal_reference'] = 'Personal reference check need to completed & confirmed. Please update in candidate personal';
        }

        if($work_reference && $work_reference['status'] != 'confirmed'){
            $data['errors']['work_reference'] = 'Professional reference check need to completed & confirmed. Please update in candidate personal';
        }


        //premium validation
        if(isset($data['premium']['status']) && $data['premium']['status'] != 'confirmed'){
            $data['errors']['premium'] = 'Premium status need to be confirmed by LP with verified status accepted / rejected.';
        }

        if (!$data['passport_valid'])
        {
            $data['errors']['passport_valid'] = 'The passport - must be valid';
        }

        if(isset($rule['english_test'])){
            $english_codes = array_keys($data['english_test']);
            for ($i=0; $i < count($english_codes); $i++) { 
                if ($data['english_test'][$english_codes[$i]]['status'] === 'accepted') {
                    break;
                }
                $data['errors']['english_test'] = 'The english test need to accepted';
            }
        }
        

        return $this->response($data);
    }

    public function getHashCV($address_book_id,$template) {
        //check hash personal cv
        $hash_cv ='123';
        $cv_db = new \core\modules\cv\models\common\db;
        $data_hash_cv = $cv_db->checkHashPersonalCV($address_book_id);
        if(count($data_hash_cv)>0) {
            $hash_cv = $data_hash_cv[0]['hash'];
        } else {
            $unix = false;
            do {
                $random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
                $hash_cv = md5($random_string);

                //check hash unix
                $data_personal_cv = $cv_db->getIDHashPersonalCV($hash_cv);
                if(count($data_personal_cv)>0) {
                    $unix = true;
                }
            } while($unix);

            $data_to_db = [
                'address_book_id' => $address_book_id,
                'hash' => $hash_cv,
                'template' => $template
            ];
            $cv_db->insertHashPersonalCV($data_to_db);
        }
        //update status file address book
        $cv_db->updateAddressBookFileCV($address_book_id);
        return $hash_cv;
    }

    private function _getChecklistInfo($address_book_id)
    {
        $out = array();

        //valid checklists
        $checklists = array('character','health');

        foreach($checklists as $type)
        {
            $not_specified = 0;
            $yes = 0;
            $yes_array = array();
            $no = 0;

            $info = $this->personal_db->getChecklist($address_book_id,$type);

            if($info)
            {
                //date last updated
                $out[$type]['date'] = date("j M Y",strtotime($info[1]['modified_on']));

                //process the result
                foreach($info as $question_id => $value)
                {
                    switch ($value['answer'])
                    {
                        case "not specified":
                            $not_specified++;
                            break;

                        case "yes":
                            $yes++;
                            //keep an array to get the information for the display
                            $yes_array[$question_id] = $value['text'];
                            break;

                        case "no":
                            $no++;
                            break;
                    }
                }

                if($not_specified > 0)
                {
                    $out[$type]['result'] = 'NOT FINISHED';
                } else if($yes > 0) { //means there is stuff to review
                    $out[$type]['result'] = 'Review';
                } else {
                    $out[$type]['result'] = 'All Good';
                }

            } else {

                $out[$type] = array(
                    'date' => '-',
                    'result' => 'NOT STARTED',
                    'display' => false
                );

            }

        }

        return $out;
    }

    private function _getEnglishList($ab_id)
    {
        $out = array();

        $info = $this->personal_db->getEnglishList($ab_id);

        foreach($info as $key => $value)
        {
            $when = date('d M Y', strtotime($value['when']));

            $thumb = $value['filename'].'-thumb';

            $out[$key] = array(
                'type' => $value['type'],
                'overall' => $value['overall'],
                'breakdown' => $value['breakdown'],
                'when' => $when,
                'where' => $value['where'],
                'filename' => $value['filename'],
                'thumb' => $thumb,
                'status' => $value['status']
            );
        }

        return $out;
    }

    private function _checkInterviewSecurity($address_book_id, $job_application_principal){
        //get country from passport, no pasport get from address
        $job_db = new \core\modules\job\models\common\db();
        $this->personal_db = new \core\modules\personal\models\common\db();

        $job_application = $job_db->getJobApplication($address_book_id);
        $this->personal_db = new \core\modules\personal\models\common\db;
        $latest_passport = $this->personal_db->getLatestPassport($address_book_id);
        return $latest_passport;

        if($latest_passport){
            $country_code = $latest_passport['countryCode_id'];
        }else{
            $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
            $address = $this->address_book_db->getAddressBookAddressDetails($address_book_id);
            if($address){
                $country_code = $address['country'];
            }
        }
        //check for security check.
        $need_security_check = false;
        $this->security_checker_db = new \core\modules\workflow\models\common\security_checker_db();

        $need_security_check = $this->security_checker_db->checkNeedSecurityCheck($job_application_principal,$country_code);
        
        if($need_security_check){
            //insert security check
            $this->security_checker_db->insertInterviewSecurityCheck($address_book_id);
            $job_db->updateJobApplicationStatus($address_book_id,'security');
        }
    }

    private function _triggerWorkflow($job_application_id, $principal_code)
    {
        $workflow_master_db = new \core\modules\workflow\models\common\common;

        $workflow_master_db->triggerMasterWorkflow('job_application_id', $job_application_id, $principal_code, 'interview', ['step'=>'police']);
    }

    private function _sendEmailReminderToUser($ab){
        $generic_obj = \core\app\classes\generic\generic::getInstance();
        $fullname = $generic_obj->getName('per', $ab['entity_family_name'], $ab['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_name = $ab['title'].' '.$fullname;
        
        $to_email = $ab['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $subject = 'Reminder - Please Complete Profile and Personal Data';

        //message
        $message = '<h1>Hello, <b>'.$to_name.'</b></h1>';
        $message .= '<p>This email was sent because you are not finish update your profile and personal data.</p>';
        $message .= '<p>Why you should complete your profile and personal data?</p>';
        $message .= '<ul>
        <li>You will able to access available job positions.</li>  
        <li>You will able to generate and download your CV</li>  
        <li>You will be notified when there any update about job position and our other programs.</li>  
        </ul>';
        $message .= 'Please visit this link to login : <a href="https://members.speedy.global/security">https://members.speedy.global/security<a>';
        $message .= '<br>If you need tutorial how to update the profile, please visit : <a href="https://members.speedy.global/about">https://members.speedy.global/about<a>';
        $message .= '<br><p>Thank You</p>';
        //cc
        $cc = '';

        //bcc
        if(SYSADMIN_BCC_NEW_USERS)
        {
            $bcc = SYSADMIN_EMAIL;
        } else {
            $bcc = '';
        }

        //html
        $html = true;
        $fullhtml = false;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $generic = \core\app\classes\generic\generic::getInstance();
        $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);

        return;
	}

}
?>