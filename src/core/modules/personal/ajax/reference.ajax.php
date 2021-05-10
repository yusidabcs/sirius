<?php
namespace core\modules\personal\ajax;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class reference extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        if($this->option == 'resend'){
            $workflow_db = new \core\modules\workflow\models\common\db();
            $personal_db = new \core\modules\personal\models\common\db();
            $reference = $personal_db->getReference($this->page_options[1]);
            $reference_check = $personal_db->getLatestReferenceCheck($this->page_options[1]);
            $ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();
            $person = $ab_db->getAddressBookMainDetails($reference['address_book_id']);

           //check if has partner
           $partner = $personal_db->getLocalPartnerDataByReferenceId($this->page_options[1]);

            $this->_sendReferenceEmail($partner, $person['title'].' '.$person['number_given_name'].' '.$person['entity_family_name'], $reference_check['hash'], $reference['family_name'], $reference['given_names'], $reference['email']);

            if ($reference['type'] === 'work') {
                $tablename = 'workflow_profesional_reference_tracker';
            } else {
                $tablename = 'workflow_personal_reference_tracker';
            }

            if ($workflow_db->getActiveWorkflow($tablename, 'reference_check_id', $reference_check['id'])) {
                // update tracker
                $tracker = $workflow_db->updateReferenceTracker($tablename, $reference_check['id'], [
                    'request_on' => date('Y-m-d H:i:s'),
                    'request_by' => $_SESSION['user_id'],
                    'notes' => 'Resend link to person who referenced to ',
                    'level' => 1,
                    'status' => 'request'
                ]);
            } else {
                //insert tracker
                if ($reference['type'] === 'work') {
                    $workflow_db->insertProfessionalReferenceTracker($reference_check['id'], $reference['address_book_id']);
                } else {
                    $workflow_db->insertPersonalReferenceTracker($reference_check['id'], $reference['address_book_id']);
                }
            }

            return $this->response([
                'message' => 'Successfully resend the email!'
            ]);
        }
        elseif($this->option == 'confirm'){

            $personal_db = new \core\modules\personal\models\common\db();
            $workflow_db = new \core\modules\workflow\models\common\db();
            $reference = $personal_db->getReference($this->page_options[1]);
            $reference_check = $personal_db->getLatestReferenceCheck($this->page_options[1]);

            if ($reference['type'] === 'work') {
                $tablename = 'workflow_profesional_reference_tracker';
            } else {
                $tablename = 'workflow_personal_reference_tracker';
            }

            if ($workflow_db->getActiveWorkflow($tablename, 'reference_check_id', $reference_check['id'])) {
                # code...
                $tracker = $workflow_db->updateReferenceTracker($tablename, $reference_check['id'], [
                    'accepted_on' => date('Y-m-d H:i:s'),
                    'accepted_by' => $_SESSION['user_id'],
                    'notes' => 'reference has been accepted',
                    'level' => 1,
                    'status' => 'accepted'
                ]);
    
                if ($tracker !== 1) {
                    return $this->response( [
                        'message' => 'Unsuccessfully confirm reference check!',
                        'type' => 'error'
                    ], 400);
                }
            }


            $confirm = $personal_db->confirmReferenceCheck($reference_check['id']);
            if($confirm == 1){
                return $this->response([
                    'message' => 'Successfully confirm reference check!',
                    'type' => 'success'
                ]);
            }else{
                return $this->response( [
                    'message' => 'Unsuccessfully confirm reference check!',
                    'type' => 'error'
                ], 400);
            }

        }elseif($this->option == 'status'){
            $personal_db = new \core\modules\personal\models\common\db();
            $confirm = $personal_db->getReferenceCheckStatus($this->page_options[1]);

            if(!empty($confirm)){
                $confirm['message'] = 'Successfully check reference status';
                return $this->response($confirm);
            }else{
                return $this->response([
                    'message' => 'Unsuccessfully check reference status!'
                ],400);
            }

        } else if($this->option == 'reject') {
            $personal_db = new \core\modules\personal\models\common\db();
            $workflow_db = new \core\modules\workflow\models\common\db();
            $reference = $personal_db->getReference($this->page_options[1]);
            $reference_check = $personal_db->getLatestReferenceCheck($this->page_options[1]);

            if ($reference['type'] === 'work') {
                $tablename = 'workflow_profesional_reference_tracker';
            } else {
                $tablename = 'workflow_personal_reference_tracker';
            }

            if ($workflow_db->getActiveWorkflow($tablename, 'reference_check_id', $reference_check['id'])) {
                # code...
                $tracker = $workflow_db->updateReferenceTracker($tablename, $reference_check['id'], [
                    'rejected_on' => date('Y-m-d H:i:s'),
                    'rejected_by' => $_SESSION['user_id'],
                    'notes' => 'reference has been rejected, upload another reference',
                    'status' => 'rejected',
                    'level' => 1,
                ]);
    
                if ($tracker !== 1) {
                    return $this->response( [
                        'message' => 'Unsuccessfully confirm reference check!'
                    ], 400);
                }
            }


            $confirm = $personal_db->rejectReferenceCheck($reference_check['id']);
            $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
            $address_book = $address_book_db->getAddressBookMainDetails($reference['address_book_id']);

            if($confirm == 1){
                $reference_type = ($reference['type'] === 'personal') ? 'personal reference':'profesional reference';
                $this->sendRejectReference($reference_type, $address_book['entity_family_name'], $address_book['number_given_name'], $address_book['main_email']);
                return $this->response([
                    'message' => 'Successfully reject reference check!'
                ]);
            }else{
                return $this->response( [
                    'message' => 'Unsuccessfully reject reference check!'
                ], 400);
            }
        } else if($this->option == 'export') {
            $personal_db = new \core\modules\personal\models\common\db();
            $job_db = new \core\modules\job\models\common\db();
            $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
            $generic_obj = \core\app\classes\generic\generic::getInstance();
            
            $reference = $personal_db->getReference($this->page_options[1]);
            $reference_check = $personal_db->getLatestReferenceCheck($this->page_options[1]);
            $candidate_details = $address_book_common->getAddressBookMainDetails($reference['address_book_id']);
            $user_confirmed = $personal_db->getUserBy('user_id', $reference_check['confirmed_by']);
            $user_confirmed_details = $address_book_db->getAddressBookMainDetailsByEmail($user_confirmed['email']);
            
            $candidate_applied_position = $job_db->getActiveJobApplication($reference['address_book_id']);
            if (empty($candidate_applied_position)) {
                return $this->response([
                    'type' => 'warning',
                    'message' => 'Please apply for job before exporting.'
                ], 406);
            }

            $candidate_full_name = $generic_obj->getName('per',$candidate_details['entity_family_name'], $candidate_details['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            $user_confirmed_full_name = $generic_obj->getName('per', $user_confirmed_details['entity_family_name'], $user_confirmed_details['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            $answers = $personal_db->getReferenceCheckAnswer($reference_check['id']);
            $questions = $personal_db->getReferenceQuestions($reference['type']);

            $reference_check_completed_date = date('d M Y', strtotime($reference_check['completed_on']));
            $signature_file = $address_book_db->getAddressBookFileArray($user_confirmed_details['address_book_id'], 'signature');
            $signature_directory = $this->_checkAddressBookFileDirectory($user_confirmed_details['address_book_id']);
            
            if ($signature_file) {
                $type = pathinfo($signature_directory . '/' . $signature_file[0]['filename'], PATHINFO_EXTENSION);
                $data_file = file_get_contents($signature_directory . '/' . $signature_file[0]['filename']);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data_file);
            }


            $reference_type = 'Personal Reference';
            if ($reference['type'] === 'work') {
                $reference_type = 'Profesional Reference';
            }

            $html = "<style>div.page_break{
                page-break-before: always;
            }</style>";

            $html .= '<h1 align="center">Speedy Global '.$reference_type.' Form </h1>';
            $html .= "<hr>";
            if (!$signature_file) {
                $html .= "<p style='font-style: italic'>Signature still empty, please upload on your general page</p>";
            }
            $html .= '<h3 align="left">Applicant\'s Details</h3>';
            $html .= "<table border='0' width='100%'>
                <tr>
                    <td width='40%'>Full Name (as on passport) with title : </td>
                    <td>$candidate_full_name</td>
                </tr>
                <tr>
                    <td width='40%'>Date of Birth : </td>
                    <td>".$candidate_details['dob']."</td>
                </tr>
                <tr>
                    <td width='40%'>Position Applied For : </td>
                    <td>".$candidate_applied_position['job_title']."</td>
                </tr>
            </table><p></p>";

            $html .= '<h3 align="left">Reference Details (filled in by Applicant and as used in Online Application)</h3>';
            $html .= "<table border='0' width='100%'>
                <tr>
                    <td width='40%'>Contact Name (with title) : </td>
                    <td>".$reference['given_names']. ' ' . $reference['family_name']."</td>
                </tr>
                <tr>
                    <td width='40%'>Relationship to Applicant : </td>
                    <td>".$reference['relationship']."</td>
                </tr>
                <tr>
                    <td width='40%'>Telephone no : </td>
                    <td>".$reference['number']."</td>
                </tr>
                <tr>
                    <td width='40%'>Email : </td>
                    <td>".$reference['email']."</td>
                </tr>
            </table><p></p>";

            $html .= '<h3 align="left">Reference Check Completed By</h3>';
            $html .= "<table border='0' width='100%'>
                <tr>
                    <td width='40%'>Contact Name (with title) : </td>
                    <td>$user_confirmed_full_name</td>
                </tr>
                <tr>
                    <td width='40%'>Telephone no : </td>
                    <td>".$user_confirmed_details['number']."</td>
                </tr>
                <tr>
                    <td width='40%'>Email : </td>
                    <td>".$user_confirmed_details['main_email']."</td>
                </tr>
            </table><p>&nbsp;</p><p>&nbsp;</p>";
            $html .= '<h3 align="left">Notes</h3>';
            $html .= "<ul>";
            $html .= "<li>ALL fields must be completed on the questionnaire.</li>";
            $html .= "<li>Reference Form is for Applicant’s professional/personal references.</li>";
            $html .= "<li>Ensure that the Reference details are EXACTLY the same as the details the Applicant used to complete Online Application.</li></ul>";
            
            $html .= "<div style='page-break-before: always' class='page_break'></div>";

            $html .= '<h2 align="center" style="text-transform: uppercase">QUESTIONNAIRE FOR APPLICANT\'S '.$reference_type.'</h2>';
            $html .= "<hr>";
            $html .= '<table style="width: 500px; margin: auto" border="1">';

            $index = 1;
            
            foreach ($questions as $key => $value) {
                $html .= '<tr style="width: 100%">';
                $html .= '<td style="padding: 7px; background-color: #ccc; width:60%">'.$index.'. ' . $value['question']. '</td>';
                $html .= '<td style="padding: 7px; width: 40%">' . $answers[$value['question_id']]['answer']. '</td>';
                $html .= '</tr>';
                $index++;
            }

            $html .= '</table>';

            $html .= '<h2 style="text-transform: uppercase">DECLARATION :</h2>';
            $html .= '<p>The above Verification Form (a.k.a. Reference Check) is true and correct and was taken by me on this date.</p>';
            $html .= '<p>&nbsp;</p>';

            if ($signature_file) {
                $html .= '<p>Signature <img src="'.$base64.'" style="max-width: 100px"> DATE : '.$reference_check_completed_date.' </p>';
            } else {
                $html .= '<p>Signature <span style="text-decoration: underline">'.$user_confirmed_full_name.'</span> DATE : '.$reference_check_completed_date.' </p>';
            }

            $options = new Options();
            $options->set('defaultPaperSize', 'A4');
            $options->set('defaultFont', 'arial');
            $options->set('isRemoteEnabled', true);

            $pdf = new Dompdf($options);
            $pdf->loadHtml($html);

            $pdf->render();

            $outputFile = $pdf->output();
            

            //save the file
            $directory = $this->_checkAddressBookFileDirectory($_SESSION['personal']['address_book_id']);
            $filename = $address_book_db->uniqueAddressBookFileName();
            $dst_file = $directory.'/'.$filename;
            file_put_contents($dst_file, $outputFile);

            $model_code = 'reference';
            $model_sub_code = 'pdf';

            $file_reference =  $address_book_db->getAddressBookFileArray($reference['address_book_id'],$model_code,$model_sub_code);
            $file_current = !empty($file_reference)? $file_reference[0]['filename'] : '';

            if($file_current)
            {
                
                //delete the current premium service image
                $address_book_common->deleteAddressBookFile($file_current,$reference['address_book_id']);

                //insert also saves the image in the address book folder
                $affected_rows = $address_book_db->updateAddressBookFile($filename,$reference['address_book_id'],$model_code,0,$model_sub_code,1);

                if($affected_rows != 1)
                {
                    $msg = "There was a major issue with process premium service form for address id {$reference['address_book_id']}. Affected was {$affected_rows}";
                    throw new \RuntimeException($msg);
                }

            } else {
                //insert also saves the image in the address book folder
                $affected_rows = $address_book_db->insertAddressBookFile($filename,$reference['address_book_id'],$model_code,0,$model_sub_code,1);

                if($affected_rows != 1)
                {
                    $msg = "There was a major issue with process premium service form for address id {$reference['address_book_id']}. Affected was {$affected_rows}";
                    throw new \RuntimeException($msg);
                }

            }

            return $this->response([
                'type' => 'success',
                'message' => 'PDF has been generated!',
                'url' => HTTP_TYPE.SITE_WWW . '/ab/show/' . $filename
            ]);
        }

        $data = $_POST;
        $personal_db = new \core\modules\personal\models\common\db();
        $workflow_db = new \core\modules\workflow\models\common\db();

        if($data['contact_method'] == 'phone'){

            
            
            $personal_db->insertReferenceCheck($check);
            
            $reference = $personal_db->getReference($data['reference_id']);
            $reference_check = $personal_db->getLatestReferenceCheck($data['reference_id']);
            if(count($reference_check)>0) {
                $data_to_update = [
                    'reference_id' => $data['reference_id'],
                    'contact_method' => $data['contact_method'],
                    'status' => 'completed',
                    'hash' => '',
                    'question_type' => $data['question_type'],
                    'completed_on' => date('Y-m-d H:i:s'),
                    'completed_by' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0
                ];
                $personal_db->updateReferenceCheck($reference_check['id'],$data_to_update);
            } else {
                $check = [
                    'reference_id' => $data['reference_id'],
                    'contact_method' => $data['contact_method'],
                    'status' => 'completed',
                    'hash' => '',
                    'question_type' => $data['question_type'],
                    'completed_on' => date('Y-m-d H:i:s'),
                    'completed_by' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0
                ];

                $personal_db->insertReferenceCheck($check);
                $reference_check = $personal_db->getLatestReferenceCheck($data['reference_id']);
            }
            $reference = $personal_db->getReference($data['reference_id']);
            $questions = $personal_db->getReferenceQuestions($reference['type']);

            if ($data['question_type'] === 'work') {
                $tablename = 'workflow_profesional_reference_tracker';
            } else {
                $tablename = 'workflow_personal_reference_tracker';
            }

            if ($workflow_db->getActiveWorkflow($tablename, 'reference_check_id', $reference_check['id'])) {
                # code...
                $tracker = $workflow_db->updateReferenceTracker($tablename, $reference_check['id'], [
                    'request_on' => date('Y-m-d H:i:s'),
                    'request_by' => $_SESSION['user_id'],
                    'completed_on' => date('Y-m-d H:i:s'),
                    'completed_by' => $_SESSION['user_id'],
                    'notes' => 'reference answers has been submitted!',
                    'status' => 'review',
                    'level' => 1
                ]);
    
                if ($tracker !== 1) {
                    return $this->response([
                        'message' => 'Unsuccessfully update trackers',
                        'error' => $tracker
                    ],500);
                }
            } else {
                if ($data['question_type'] === 'work') {
                    $workflow_db->insertProfessionalReferenceTracker($reference_check['id'], $reference['address_book_id']);
                } else {
                    $workflow_db->insertPersonalReferenceTracker($reference_check['id'], $reference['address_book_id']);
                }
                $tracker = $workflow_db->updateReferenceTracker($tablename, $reference_check['id'], [
                    'request_on' => date('Y-m-d H:i:s'),
                    'request_by' => $_SESSION['user_id'],
                    'completed_on' => date('Y-m-d H:i:s'),
                    'completed_by' => $_SESSION['user_id'],
                    'notes' => 'reference answers has been submitted!',
                    'status' => 'review'
                ]);
            }
            

            if(isset($data['answer'])){
                foreach ($questions as $key => $question){

                    $personal_db->insertReferenceCheckAnswer($question['answer_type'],[
                        'reference_check_id' => $reference_check['id'],
                        'question_id' => $question['question_id'],
                        'answer' => $data['answer'][$question['question_id']],
                    ]);
                }
            }
            return $this->response([
                'message' => 'Successfully insert reference check!'
            ]);
        }else if($data['contact_method'] == 'email'){
            $hash = md5($data['reference_id'].date('Y-m-d H:i:s'));
            $reference = $personal_db->getReference($data['reference_id']);
            
            $check = [
                'reference_id' => $data['reference_id'],
                'contact_method' => $data['contact_method'],
                'status' => 'request',
                'hash' => $hash,
                'question_type' => $data['question_type'],
                'completed_on' => '',
                'completed_by' => '',
                'requested_on' => date('Y-m-d H:i:s'),
                'requested_by' => $_SESSION['user_id']
            ];

            $personal_db->insertReferenceCheck($check);

            //send email part
            $reference = $personal_db->getReference($data['reference_id']);
            $reference_check = $personal_db->getLatestReferenceCheck($data['reference_id']);

            if ($data['question_type'] === 'work') {
                $workflow_db->insertProfessionalReferenceTracker($reference_check['id'], $reference['address_book_id']);
            } else {
                $workflow_db->insertPersonalReferenceTracker($reference_check['id'], $reference['address_book_id']);
            }

            $ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();

            $person = $ab_db->getAddressBookMainDetails($reference['address_book_id']);
            $partner = $personal_db->getLocalPartnerDataByReferenceId($data['reference_id']);

            $this->_sendReferenceEmail($partner, $person['title'].' '.$person['number_given_name'].' '.$person['entity_family_name'], $hash, $reference['family_name'], $reference['given_names'], $reference['email']);
            return $this->response([
                'message' => 'Successfully sent reference check!'
            ]);
        }

    }

    private function _sendReferenceEmail($partner, $user_name, $hash,$family_name,$given_name,$main_email)
    {
        $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
        $menu_register = $menu_register_ns::getInstance();
        $link_id = $menu_register->getModuleLink('reference_check');

        $mailing_db = new \core\modules\send_email\models\common\db;
        $mailing_common = new \core\modules\send_email\models\common\common;

        $to_name = empty($family_name) ? $given_name : $given_name.' '.$family_name;
        $to_email = $main_email;

        //from the system info
        $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
        $reply_to = $this->system_register->system_info('REFERENCE_REPLY_TO');

        //subject
        $template = $mailing_common->renderEmailTemplate('reference_check', [
            'to_name' => $to_name,
            'user_name' => $user_name,
            'link' => HTTP_TYPE.SITE_WWW.'/'.$link_id .'/process/'.$hash
        ]);
        if ($template) {
            # code...
            $subject = $template['subject'];
        } else {
            $subject = 'Reference Check - '.SITE_WWW;
        }

        //message
        $message = '';
        //check if has partner
        $partner_db = new \core\modules\partner\models\common\db;
        
        $message = $template['html'];
        

        //cc
        $cc ='';

        //bcc
        if(SYSADMIN_BCC_NEW_USERS)
        {
            $bcc = SYSADMIN_EMAIL;
        } else {
            $bcc = '';
        }

        //html
        $html = true;
        $fullhtml = true;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $generic = \core\app\classes\generic\generic::getInstance();
        $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink,'','',$reply_to);

        return;
    }

    private function sendRejectReference($reference_type,$family_name,$given_name,$main_email)
    {
        $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
        $menu_register = $menu_register_ns::getInstance();
        $link_id = $menu_register->getModuleLink('personal');

        $mailing_db = new \core\modules\send_email\models\common\db;
        $mailing_common = new \core\modules\send_email\models\common\common;

        $to_name = empty($family_name) ? $given_name : $given_name.' '.$family_name;
        $to_email = $main_email;

        //from the system info
        $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
        $reply_to = $this->system_register->system_info('REFERENCE_REPLY_TO');

        //subject
        $template = $mailing_common->renderEmailTemplate('reference_rejected', [
            'name' => $to_name,
            'reference_type' => $reference_type,
            'link' => HTTP_TYPE.SITE_WWW.'/'.$link_id
        ]);
        if ($template) {
            # code...
            $subject = $template['subject'];
        } else {
            $subject = 'Reference Check - '.SITE_WWW;
        }

        //message
        $message = '';
        
        $message = $template['html'];
        

        //cc
        $cc ='';

        //bcc
        if(SYSADMIN_BCC_NEW_USERS)
        {
            $bcc = SYSADMIN_EMAIL;
        } else {
            $bcc = '';
        }

        //html
        $html = true;
        $fullhtml = true;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $generic = \core\app\classes\generic\generic::getInstance();
        $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink,'','',$reply_to);

        return;
    }

    private function _checkSignature()
    {
        $personal_db = new \core\modules\personal\models\common\db;
        $address_book_common = \core\modules\address_book\models\common\address_book_db::getInstance();

        $signature_file = $address_book_common->getAddressBookFileArray($_SESSION['personal']['address_book_id'], 'signature');

        if (count($signature_file) <= 0) {
            return false;
        }

        $general = $personal_db->getGeneral($_SESSION['personal']['address_book_id']);

        if (empty($general['signature_filename'])) {
            return false;
        }

        return true;
    }

    private function _checkAddressBookFileDirectory($address_book_id)
	{
		if( empty($address_book_id) || $address_book_id < 1 )
		{
			$msg = "Bad Address Book Id! You need an address book id not ({$address_book_id})!";
			throw new \RuntimeException($msg);
		}
		
		//check the directory
		//make a folder if one is not there already for this address book 
		$directory = DIR_LOCAL_UPLOADS.'/address_book/'.$address_book_id;
		
		if(!is_dir($directory))
		{
			if(!@mkdir($directory,0770,true))
			{
				$msg = "The address_book {$address_book_id} directory could not be set up!";
				throw new \RuntimeException($msg);
			}
		}
		
		return $directory;
	}


}