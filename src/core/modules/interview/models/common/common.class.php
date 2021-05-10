<?php
namespace core\modules\interview\models\common;

/**
 * Final interview common class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class common {
    public function __construct()
    {
        $this->db = new db();
        return;
    }

    public function getInterviewLocationSummary($location_id){

        $total_hire = $this->db->getTotalHireInterview($location_id);
        $total_not_hire = $this->db->getTotalNotHireInterview($location_id);
        $job_group = $this->db->getTotalHireByJob($location_id);
        $data = [
            'total_hire' => $total_hire,
            'total_not_hire' => $total_not_hire,
            'total_withdraw' => 0,
            'job_groups' => $job_group,
        ];
        return $data;

    }

    public function sendEmailReminderInterview($data_interview,$type) {
        $generic = \core\app\classes\generic\generic::getInstance();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        $interviewer = [];
        $license_partner = [];
        
        $type_interview = $type=='location'?'Physical':'Online';
        foreach ($data_interview as $item) {
            $address_book = $address_book_db->getAddressBookMainDetails($item['address_book_id']);
            $ab_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            $ab_email = $address_book['main_email'];

            $arr_email = [];
            if($type_interview=='Online') {
                $text_email = "
                    Interview Date : ".$item['schedule_on']." ".$item['timezone']."
                ";

                $arr_email = ['type'=>'online','interview_date'=>$item['schedule_on']." ".$item['timezone'],'interviewer'=>'-'];
            } else {
                $text_email = "
                        Start on : ".$item['start_on']." <br />
                        Finish on : ".$item['finish_on']." <br />
                        Address : ".$item['address']." <br />
                        Maps : <a href='".$item['google_map']."'>".$item['google_map']."</a> <br />
                ";
                $arr_email = [
                    'type'=>'physical',
                    'start_on'=> $item['start_on'],
                    'finish_on'=> $item['finish_on'],
                    'address'=> $item['address'],
                    'maps'=> "<a href='".$item['google_map']."'>Open Maps</a>",

                ];
            }

            $this->_sendEmailInterviewreminder($ab_name, $ab_email, '<p>'.$text_email.'</p>', $type_interview);

            $arr_email['candidate'] = $ab_name;
            if($type_interview=='Online') {
                if($item['interviewer_id']!=0) {
                    if(isset($interviewer[$item['interviewer_id']])) {
                        $index = count($interviewer[$item['interviewer_id']]);
                        $interviewer[$item['interviewer_id']][$index] = $arr_email;
                    } else {
                        $interviewer[$item['interviewer_id']][0] = $arr_email;
                    }

                    $address_book = $address_book_db->getAddressBookMainDetails($item['interviewer_id']);
                    $interviewer_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                    $arr_email['interviewer'] = $interviewer_name;
                } else {
                    $arr_email['interviewer'] = '-';
                }
                
            }

            if(isset($license_partner[$item['connection_id']])) {
                $index = count($interviewer[$item['interviewer_id']]);
                $license_partner[$item['connection_id']][$index] = $arr_email;
            } else {
                $license_partner[$item['connection_id']][0] = $arr_email;
            }
            
        } //endfor

        if(count($interviewer)>0) {
            $arr_interviewer = array_keys($interviewer);
            foreach ($arr_interviewer as $item) {
                $address_book = $address_book_db->getAddressBookMainDetails($item);
                $interviewer_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $interviewer_email = $address_book['main_email'];

                $content_email = "
                <style>
                #table_interviewer, #table_interviewer td, #table_interviewer th {
                  border: 1px solid black;
                  padding-left : 3px;
                }
                
                #table_interviewer {
                  width: 100%;
                  border-collapse: collapse;
                }
                </style>

                    <table id='table_interviewer'>
                        <thead>
                            <tr>
                                <th>Candidate</th>";
                                if($type_interview=='Physical') {
                                    $content_email .= "
                                    <th>Start On</th>
                                    <th>Finish On</th>
                                    <th>Location</th>
                                    <th>Maps</th>";
                                    
                                } else {
                                    $content_email .= "
                                    <th>Interview Date</th>";
                                }
                    $content_email .= "</tr>
                        </thead>
                        <tbody>
                ";
                foreach ($interviewer[$item] as $content) {
                    $content_email .="
                    <tr>
                        <td>".$content['candidate']."</td>";
                        if($type_interview=='Physical') {
                            $content_email .= "
                                <td>".$content['start_on']."</td>
                                <td>".$content['finish_on']."</td>
                                <td>".$content['address']."</td>
                                <td>".$content['maps']."</td>";
                        } else {
                            $content_email .= "<td>".$content['interview_date']."</td>";
                            
                        }

                    $content_email .="</tr>";
                }
                    $content_email .="</tbody></table>";
                $text_email = "
                    <p>Hi <b>".$interviewer_name."</b>,</p>
                    <p>You have schedule interview for <b>".count($interviewer[$item])." candidates</b> </p>
                    <p>".$content_email."</p>
                ";

                $this->_sendEmailInterviewreminder($interviewer_name, $interviewer_email, $text_email, $type_interview);
            }
        }

        if(count($license_partner)>0) {
            $arr_license_partner = array_keys($license_partner);
            foreach ($arr_license_partner as $item) {
                $address_book = $address_book_db->getAddressBookMainDetails($item);
                $license_partner_name = $generic->getName('ent', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
                $license_partner_email = $address_book['main_email'];

                $content_email = "
                    <style>
                    #table_lp, #table_lp td, #table_lp th {
                    border: 1px solid black;
                    padding-left : 3px;
                    }
                    
                    #table_lp {
                    width: 100%;
                    border-collapse: collapse;
                    }
                    </style>
                    <table id='table_lp'>
                        <thead>
                            <tr>
                                <th>Candidate</th>";
                                if($type_interview=='Physical') {
                                    $content_email .= "
                                    <th>Start On</th>
                                    <th>Finish On</th>
                                    <th>Location</th>
                                    <th>Maps</th>";
                                    
                                } else {
                                    $content_email .= "
                                    <th>Interview Date</th>
                                    <th>Interviewer</th>";
                                    
                                }
                    $content_email .= "</tr>
                        </thead>
                        <tbody>
                ";
                foreach ($license_partner[$item] as $content) {
                    $content_email .="
                    <tr>
                        <td>".$content['candidate']."</td>";
                        if($type_interview=='Physical') {
                            $content_email .= "
                                <td>".$content['start_on']."</td>
                                <td>".$content['finish_on']."</td>
                                <td>".$content['address']."</td>
                                <td>".$content['maps']."</td>";
                        } else {
                            $content_email .= "<td>".$content['interview_date']."</td>";
                            $content_email .= "<td>".$content['interviewer']."</td>";
                        }

                    $content_email .="</tr>";
                }
                    $content_email .="</tbody></table>";

                $text_email = "
                    <p>Hi <b>".$license_partner_name."</b>,</p>
                    <p>You have schedule interview for <b>".count($license_partner[$item])." candidates</b> </p>
                    <p>".$content_email."</p>
                ";
                $this->_sendEmailInterviewreminder($license_partner_name, $license_partner_email, $text_email, $type_interview);
            }
        }
    }

    private function _sendEmailInterviewreminder($to_name, $to_email, $text_email, $type_interview) {
        $mailing_common = new \core\modules\send_email\models\common\common;

        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('interview_schedule_reminder', [
            'type' => $type_interview,
            'content' => $text_email
        ]);
        
        $subject = '';
        $message = '';
        if ($template) {
            $subject = $template['subject'];
            $message = $template['html'];
        }

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
        return $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);

    }
}
?>