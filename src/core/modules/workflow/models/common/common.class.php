<?php
namespace core\modules\workflow\models\common;

/**
 * Final workflow common class.
 *
 * @final
 * @package		workflow
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 Jul 2020
 */
final class common {

    public function __construct()
    {
        $this->security_check_db = new security_check_db();
        $this->recruitment_db = new recruitment_db();
        return;
    }

    public function updateTrackerLevel(){
        $this->security_check_db->updateTrackerLevel();
    }

    public function sendRequestVerificationTrackerReport(){
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow('recruitment');

        $trackers = $this->recruitment_db->getTotalTrackerByLevel();
        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        array_filter($trackers, function ($item){
            if($item['level'] == 2){
                $this->soft_warning = $item['total'];
            }
            if($item['level'] == 3){
                $this->hard_warning =  $item['total'];
            }
            if($item['level'] == 4){
                $this->deadline =  $item['total'];
            }
        });
        if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
            return;
        }

        $message = '';

        $message .= '<h1>Request Verification Reports</h1>';
        $message .= '<table>
                        <tr>
                            <td>Level</td>
                            <td></td>
                            <td>Total</td>
                        </tr>
                        <tr>
                            <td>Soft Warning</td>
                            <td></td>
                            <td>'.$this->soft_warning.'</td>
                        </tr>
                        <tr>
                            <td>Hard Warning</td>
                            <td></td>
                            <td>'.$this->hard_warning.'</td>
                        </tr>
                        <tr>
                            <td>Deadline</td>
                            <td></td>
                            <td>'.$this->deadline.'</td>
                        </tr>
        </table>';
        

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        //subject
        $subject = 'Request Verification Report Notification - '.SITE_WWW;
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

        foreach ($list as $user){

            if($this->deadline == 0 && $this->hard_warning == 0){
                if($user['level'] == 'team'){
                    $to_name = $user['number_given_name'].' '.$user['entity_family_name'];
                    $to_email = $user['main_email'];
                    $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
                }
            }
            else if($this->deadline == 0 && $this->hard_warning > 0){
                if($user['level'] == 'team' || $user['level'] == 'management'){
                    $to_name = $user['number_given_name'].' '.$user['entity_family_name'];
                    $to_email = $user['main_email'];
                    $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
                }
            }else if($this->deadline > 0){
                $to_name = $user['number_given_name'].' '.$user['entity_family_name'];
                $to_email = $user['main_email'];
                $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
            }

        }

    }

    public function sendTrackerReport($workflow,$heading) {
        $tracker_db = new db();
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow($workflow);

        $arr_tracker = [];
        $trackers = $tracker_db->getTotalTrackerByLevelPartner($workflow);

        foreach ($trackers as $item) {
            $arr_tracker[$item['connection_id']][$item['level']] = $item['total'];
        }

        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        
        $arr_staff = [];
        foreach ($list as $user){
            if($user['partner_id']!=0 && $user['partner_id']!='') {
                if(in_array($user['partner_id'],array_keys($arr_tracker))) {
                    
                    $this->soft_warning = isset($arr_tracker[$user['partner_id']][2])?$arr_tracker[$user['partner_id']][2]:0;
                    $this->hard_warning = isset($arr_tracker[$user['partner_id']][3])?$arr_tracker[$user['partner_id']][3]:0;
                    $this->deadline = isset($arr_tracker[$user['partner_id']][4])?$arr_tracker[$user['partner_id']][4]:0;
                    
                    if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
                        continue;
                    }

                    $data = [
                        'heading' => $heading,
                        'entity_family_name' =>  $user['entity_family_name'],
                        'number_given_name' =>  $user['number_given_name'],
                        'main_email' =>  $user['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($user['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($user['level'] == 'team' || $user['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                } //end if in array
            } else { 
                //end if check partner id
                $arr_staff[]=$user;
            }    
        } // end for

        //send email to staff also
        if(count($arr_staff)>0) {
            $this->soft_warning = array_sum(array_column($arr_tracker,'2'));
            $this->hard_warning = array_sum(array_column($arr_tracker,'3'));
            $this->deadline = array_sum(array_column($arr_tracker,'4'));
            

            if($this->soft_warning != 0 || $this->hard_warning != 0 || $this->deadline != 0){
                foreach ($arr_staff as $staff) {

                    $data = [
                        'heading' => $heading,
                        'entity_family_name' =>  $staff['entity_family_name'],
                        'number_given_name' =>  $staff['number_given_name'],
                        'main_email' =>  $staff['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($staff['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($staff['level'] == 'team' || $staff['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                }
            }
            
        }
        
    }

    private function _sendEmailTracker($data) {
        //generic for the sendmail
        $generic = \core\app\classes\generic\generic::getInstance();
        $mailing_common = new \core\modules\send_email\models\common\common;

        $to_name = $generic->getName('per', $data['entity_family_name'], $data['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $data['main_email'];

        $content = '<table>
                        <tr>
                            <td><b>Level</b></td>
                            <td></td>
                            <td><b>Total</b></td>
                        </tr>
                        <tr>
                            <td>Soft Warning</td>
                            <td>:</td>
                            <td>'.$data['soft_warning'].'</td>
                        </tr>
                        <tr>
                            <td>Hard Warning</td>
                            <td>:</td>
                            <td>'.$data['hard_warning'].'</td>
                        </tr>
                        <tr>
                            <td>Deadline</td>
                            <td>:</td>
                            <td>'.$data['deadline'].'</td>
                        </tr>
        </table>
        <p>Thank You</p>';
        
        $template = $mailing_common->renderEmailTemplate('tracker_report', [
            'heading' => $data['heading'],
            'content' => $content
        ]);

        if ($template) {
            $subject = $template['subject'];
            $message = $template['html'];
        } else {
            $subject = $data['heading'].' Report Notification - '.SITE_WWW;
            $message = $content;
        }

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        //subject
        
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
    
        $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);

    }

    public function sendSecurityTrackerReport(){
        $report_db = new report_db();
        $list = $report_db->getReportArray();

        $trackers = $this->security_check_db->getTotalTrackerByLevel();
        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        array_filter($trackers, function ($item){
            if($item['level'] == 2){
                $this->soft_warning = $item['total'];
            }
            if($item['level'] == 3){
                $this->hard_warning =  $item['total'];
            }
            if($item['level'] == 4){
                $this->deadline =  $item['total'];
            }
        });
        if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
            return;
        }

        ob_start();
        include(DIR_MODULES . '/interview_security_tracker/views/mail/report.php');
        $message = ob_get_contents();
        ob_end_clean();


        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        //subject
        $subject = 'Security Check - Warning Level Notification - '.SITE_WWW;
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

        foreach ($list as $user){

            if($this->deadline == 0 && $this->hard_warning == 0){
                if($user['level'] == 'team'){
                    $to_name = $user['number_given_name'].' '.$user['entity_family_name'];
                    $to_email = $user['main_email'];
                    $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
                }
            }
            else if($this->deadline == 0 && $this->hard_warning > 0){
                if($user['level'] == 'team' || $user['level'] == 'management'){
                    $to_name = $user['number_given_name'].' '.$user['entity_family_name'];
                    $to_email = $user['main_email'];
                    $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
                }
            }else if($this->deadline > 0){
                $to_name = $user['number_given_name'].' '.$user['entity_family_name'];
                $to_email = $user['main_email'];
                $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
            }

        }

    }

    public function sendWorkflowSecurityTrackerReport(){
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow('security');

        $trackers = $this->security_check_db->getTotalTrackerByLevelPartner();
        
        $arr_tracker=[];
        foreach ($trackers as $item) {
            $arr_tracker[$item['connection_id']][$item['level']] = $item['total'];
        }

        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        
        $arr_staff = [];
        foreach ($list as $user){
            if($user['partner_id']!=0 && $user['partner_id']!='') {
                if(in_array($user['partner_id'],array_keys($arr_tracker))) {
                    
                    $this->soft_warning = isset($arr_tracker[$user['partner_id']][2])?$arr_tracker[$user['partner_id']][2]:0;
                    $this->hard_warning = isset($arr_tracker[$user['partner_id']][3])?$arr_tracker[$user['partner_id']][3]:0;
                    $this->deadline = isset($arr_tracker[$user['partner_id']][4])?$arr_tracker[$user['partner_id']][4]:0;
                    
                    if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
                        continue;
                    }

                    $data = [
                        'heading' => 'Security Check',
                        'entity_family_name' =>  $user['entity_family_name'],
                        'number_given_name' =>  $user['number_given_name'],
                        'main_email' =>  $user['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($user['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($user['level'] == 'team' || $user['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                } //end if in array
            } else { 
                //end if check partner id
                $arr_staff[]=$user;
            }    
        } // end for

        //send email to staff also
        if(count($arr_staff)>0) {
            $this->soft_warning = array_sum(array_column($arr_tracker,'2'));
            $this->hard_warning = array_sum(array_column($arr_tracker,'3'));
            $this->deadline = array_sum(array_column($arr_tracker,'4'));
            

            if($this->soft_warning != 0 || $this->hard_warning != 0 || $this->deadline != 0){
                foreach ($arr_staff as $staff) {

                    $data = [
                        'heading' => 'Security Check',
                        'entity_family_name' =>  $staff['entity_family_name'],
                        'number_given_name' =>  $staff['number_given_name'],
                        'main_email' =>  $staff['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($staff['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($staff['level'] == 'team' || $staff['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                }
            }
            
        }
        

    }

    public function sendWorkflowPsfTrackerReport(){
        $psf_db = new psf_tracker_db();
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow('psf');

        $arr_tracker=[];
        $trackers = $psf_db->getTotalPsfTrackerByLevelPartner();
        
        foreach ($trackers as $item) {
            $arr_tracker[$item['connection_id']][$item['level']] = $item['total'];
        }

        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        
        $arr_staff = [];
        foreach ($list as $user){
            if($user['partner_id']!=0 && $user['partner_id']!='') {
                if(in_array($user['partner_id'],array_keys($arr_tracker))) {
                    
                    $this->soft_warning = isset($arr_tracker[$user['partner_id']][2])?$arr_tracker[$user['partner_id']][2]:0;
                    $this->hard_warning = isset($arr_tracker[$user['partner_id']][3])?$arr_tracker[$user['partner_id']][3]:0;
                    $this->deadline = isset($arr_tracker[$user['partner_id']][4])?$arr_tracker[$user['partner_id']][4]:0;
                    
                    if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
                        continue;
                    }

                    $data = [
                        'heading' => 'PSF',
                        'entity_family_name' =>  $user['entity_family_name'],
                        'number_given_name' =>  $user['number_given_name'],
                        'main_email' =>  $user['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($user['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($user['level'] == 'team' || $user['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                } //end if in array
            } else { 
                //end if check partner id
                $arr_staff[]=$user;
            }    
        } // end for

        //send email to staff also
        if(count($arr_staff)>0) {
            $this->soft_warning = array_sum(array_column($arr_tracker,'2'));
            $this->hard_warning = array_sum(array_column($arr_tracker,'3'));
            $this->deadline = array_sum(array_column($arr_tracker,'4'));
            

            if($this->soft_warning != 0 || $this->hard_warning != 0 || $this->deadline != 0){
                foreach ($arr_staff as $staff) {

                    $data = [
                        'heading' => 'PSF',
                        'entity_family_name' =>  $staff['entity_family_name'],
                        'number_given_name' =>  $staff['number_given_name'],
                        'main_email' =>  $staff['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($staff['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($staff['level'] == 'team' || $staff['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                }
            }
            
        }
        
    }

    public function sendWorkflowPrincipalTrackerReport(){
        $principal_db = new principal_tracker_db();
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow('principal');

        $arr_tracker=[];
        $trackers = $principal_db->getTotalPrincipalTrackerByLevel();
        
        foreach ($trackers as $item) {
            $arr_tracker[$item['connection_id']][$item['level']] = $item['total'];
        }

        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        
        $arr_staff = [];
        foreach ($list as $user){
            if($user['partner_id']!=0 && $user['partner_id']!='') {
                if(in_array($user['partner_id'],array_keys($arr_tracker))) {
                    
                    $this->soft_warning = isset($arr_tracker[$user['partner_id']][2])?$arr_tracker[$user['partner_id']][2]:0;
                    $this->hard_warning = isset($arr_tracker[$user['partner_id']][3])?$arr_tracker[$user['partner_id']][3]:0;
                    $this->deadline = isset($arr_tracker[$user['partner_id']][4])?$arr_tracker[$user['partner_id']][4]:0;
                    
                    if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
                        continue;
                    }

                    $data = [
                        'heading' => 'Principal Invoice',
                        'entity_family_name' =>  $user['entity_family_name'],
                        'number_given_name' =>  $user['number_given_name'],
                        'main_email' =>  $user['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($user['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($user['level'] == 'team' || $user['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                } //end if in array
            } else { 
                //end if check partner id
                $arr_staff[]=$user;
            }    
        } // end for

        //send email to staff also
        if(count($arr_staff)>0) {
            $this->soft_warning = array_sum(array_column($arr_tracker,'2'));
            $this->hard_warning = array_sum(array_column($arr_tracker,'3'));
            $this->deadline = array_sum(array_column($arr_tracker,'4'));
            

            if($this->soft_warning != 0 || $this->hard_warning != 0 || $this->deadline != 0){
                foreach ($arr_staff as $staff) {

                    $data = [
                        'heading' => 'Principal Invoice',
                        'entity_family_name' =>  $staff['entity_family_name'],
                        'number_given_name' =>  $staff['number_given_name'],
                        'main_email' =>  $staff['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($staff['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($staff['level'] == 'team' || $staff['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                }
            }
            
        }
        
        

    }

    public function sendWorkflowTravelpackTrackerReport(){
        $travelpack_db = new travelpack_db();
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow('travelpack');

        $arr_tracker=[];
        $trackers = $travelpack_db->getTotalTravelpackTrackerByLevelPartner();

        
        foreach ($trackers as $item) {
            $arr_tracker[$item['connection_id']][$item['level']] = $item['total'];
        }

        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        
        $arr_staff = [];
        foreach ($list as $user){
            if($user['partner_id']!=0 && $user['partner_id']!='') {
                if(in_array($user['partner_id'],array_keys($arr_tracker))) {
                    
                    $this->soft_warning = isset($arr_tracker[$user['partner_id']][2])?$arr_tracker[$user['partner_id']][2]:0;
                    $this->hard_warning = isset($arr_tracker[$user['partner_id']][3])?$arr_tracker[$user['partner_id']][3]:0;
                    $this->deadline = isset($arr_tracker[$user['partner_id']][4])?$arr_tracker[$user['partner_id']][4]:0;
                    
                    if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
                        continue;
                    }

                    $data = [
                        'heading' => 'Travelpack Invoice',
                        'entity_family_name' =>  $user['entity_family_name'],
                        'number_given_name' =>  $user['number_given_name'],
                        'main_email' =>  $user['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($user['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($user['level'] == 'team' || $user['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                } //end if in array
            } else { 
                //end if check partner id
                $arr_staff[]=$user;
            }    
        } // end for

        //send email to staff also
        if(count($arr_staff)>0) {
            $this->soft_warning = array_sum(array_column($arr_tracker,'2'));
            $this->hard_warning = array_sum(array_column($arr_tracker,'3'));
            $this->deadline = array_sum(array_column($arr_tracker,'4'));
            

            if($this->soft_warning != 0 || $this->hard_warning != 0 || $this->deadline != 0){
                foreach ($arr_staff as $staff) {

                    $data = [
                        'heading' => 'Travelpack Invoice',
                        'entity_family_name' =>  $staff['entity_family_name'],
                        'number_given_name' =>  $staff['number_given_name'],
                        'main_email' =>  $staff['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($staff['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($staff['level'] == 'team' || $staff['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                }
            }
            
        }
        
        

    }

    public function sendWorkflowEducationTrackerReport(){
        $education_db = new education_db();
        $report_db = new report_db();
        $list = $report_db->getReportByWorkflow('education');

        $arr_tracker=[];
        $trackers = $education_db->getTotalEducationTrackerByLevelPartner();

        
        foreach ($trackers as $item) {
            $arr_tracker[$item['connection_id']][$item['level']] = $item['total'];
        }

        $this->soft_warning = 0;
        $this->hard_warning = 0;
        $this->deadline = 0;
        
        $arr_staff = [];
        foreach ($list as $user){
            if($user['partner_id']!=0 && $user['partner_id']!='') {
                if(in_array($user['partner_id'],array_keys($arr_tracker))) {
                    
                    $this->soft_warning = isset($arr_tracker[$user['partner_id']][2])?$arr_tracker[$user['partner_id']][2]:0;
                    $this->hard_warning = isset($arr_tracker[$user['partner_id']][3])?$arr_tracker[$user['partner_id']][3]:0;
                    $this->deadline = isset($arr_tracker[$user['partner_id']][4])?$arr_tracker[$user['partner_id']][4]:0;
                    
                    if($this->soft_warning == 0 && $this->hard_warning == 0 && $this->deadline == 0){
                        continue;
                    }

                    $data = [
                        'heading' => 'Education',
                        'entity_family_name' =>  $user['entity_family_name'],
                        'number_given_name' =>  $user['number_given_name'],
                        'main_email' =>  $user['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($user['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($user['level'] == 'team' || $user['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                } //end if in array
            } else { 
                //end if check partner id
                $arr_staff[]=$user;
            }    
        } // end for

        //send email to staff also
        if(count($arr_staff)>0) {
            $this->soft_warning = array_sum(array_column($arr_tracker,'2'));
            $this->hard_warning = array_sum(array_column($arr_tracker,'3'));
            $this->deadline = array_sum(array_column($arr_tracker,'4'));
            

            if($this->soft_warning != 0 || $this->hard_warning != 0 || $this->deadline != 0){
                foreach ($arr_staff as $staff) {

                    $data = [
                        'heading' => 'Education',
                        'entity_family_name' =>  $staff['entity_family_name'],
                        'number_given_name' =>  $staff['number_given_name'],
                        'main_email' =>  $staff['main_email'],
                        'soft_warning' => $this->soft_warning,
                        'hard_warning' => $this->hard_warning,
                        'deadline' => $this->deadline,
                    ];

                    if($this->deadline == 0 && $this->hard_warning == 0){
                        if($staff['level'] == 'team'){
                            $this->_sendEmailTracker($data);
                        }
                    }
                    else if($this->deadline == 0 && $this->hard_warning > 0){
                        if($staff['level'] == 'team' || $staff['level'] == 'management'){
                           $this->_sendEmailTracker($data);
                        }
                    }else if($this->deadline > 0){
                        $this->_sendEmailTracker($data);
                    }
                }
            }
            
        }
        
        

    }

    public function sendRequestFile($job_application_id){

        $job_db = new \core\modules\job\models\common\db();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $job_application = $job_db->getJobApplication($job_application_id);
        $address_book = $address_book_db->getAddressBookMainDetails($job_application['address_book_id']);

        $to_name = $address_book['number_given_name'].' '.$address_book['entity_family_name'];
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $subject = 'Security Check - Request Passport File - '.SITE_WWW;

        //message
        $message = '';

        $message .= '<h1>Please send us the full passport file for security check</h1>';
        $message .= '<p>Hello Mr/Ms '.$to_name.'</p>';
        $message .= '<p>We need you to send us a full copy of your passport';
        $message .= '<p>Thank you.</p>';

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

    public function sendPersonalRequestFile($address_book_id, $term)
    {   
        $mailing_common = new \core\modules\send_email\models\common\common;
         $generic = \core\app\classes\generic\generic::getInstance();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $to_name = $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('tracker_'.strtolower($term).'_request_file', [
            'to_name' => $to_name
        ]);

        if ($template) {
            $subject = $template['subject'];
            $message = $template['html'];
        } else {
            //subject
            $subject = ucwords($term) . ' Check - Request '.ucwords($term).' File - '.SITE_WWW;

            //message
            $message = '';

            $message .= '<h1>Please send us the full '.strtolower($term).' file for '.strtolower($term).' check</h1>';
            $message .= '<p>Hello Mr/Ms '.$to_name.'</p>';
            $message .= '<p>We need you to send us a full copy of your '.strtolower($term);
            $message .= '<p>Thank you.</p>';
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

    public function sendMedicalAppointmentDate($address_book_id)
    {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $generic = \core\app\classes\generic\generic::getInstance();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('tracker_medical_appointment', [
            'to_name' => $to_name
        ]);

        if ($template) {
            $subject = $template['subject'];
            $message = $template['html'];
        } else {
            //subject
            $subject = ' Request Medical Appointment - Request Medical Appointment Date'.SITE_WWW;

            //message
            $message = '';

            $message .= '<h1>Please make medical appointment</h1>';
            $message .= '<p>Hello Mr/Ms '.$to_name.'</p>';
            $message .= '<p>We need you to make a medical appointment for medical check';
            $message .= '<p>Thank you.</p>';
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

    public function sendVaccineAppointmentDate($address_book_id)
    {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $generic = \core\app\classes\generic\generic::getInstance();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('tracker_vaccination_appointment', [
            'to_name' => $to_name
        ]);

        if ($template) {
            $subject = $template['subject'];
            $message = $template['html'];
        } else {
            //subject
            $subject = ' Request Vaccination Appointment - Request Vaccination Appointment Date'.SITE_WWW;

            //message
            $message = '';

            $message .= '<h1>Please make vaccination appointment</h1>';
            $message .= '<p>Hello Mr/Ms '.$to_name.'</p>';
            $message .= '<p>We need you to make a vaccination appointment for vaccination check';
            $message .= '<p>Thank you.</p>';
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

    public function sendAdminConfirmationAppointment($address_book_id, $candidate_id, $term, $date)
    {
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $candidate = $address_book_db->getAddressBookMainDetails($candidate_id);
        $candidate_name = $candidate['number_given_name'].' '.$candidate['entity_family_name'];

        $to_name = $address_book['number_given_name'].' '.$address_book['entity_family_name'];
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $subject = ' Request '.ucfirst($term).' Appointment Date Has Been Set '.SITE_WWW;

        //message
        $message = '';

        $message .= '<h1>Please make vaccination appointment</h1>';
        $message .= '<p>Candidate in the name of '.$candidate_name.' has been set appointment date on '.$date.'</p>';
        $message .= '<p>After appointment date overdue status will updated to request file</p>';
        $message .= '<p>Thank you.</p>';

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

    public function sendBgcNotification($address_book_id)
    {
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $to_name = $address_book['number_given_name'].' '.$address_book['entity_family_name'];
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $subject = ' BGC Notification ' . SITE_WWW;

        $message = '';
        $message .= '<h1>Confirm BGC</h1>';
        $message .= '<p>Mr/Mrs '.$to_name.' please confirm your BGC confirmation on your personal page.</p>';

        $message .= '<p>Thank you.</p>';

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
    
    public function sendAdminConfirmationDocsApplication($address_book_id, $candidate_id, $docs_application, $date)
    {
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $candidate = $address_book_db->getAddressBookMainDetails($candidate_id);
        $candidate_name = $candidate['number_given_name'].' '.$candidate['entity_family_name'];
        
        $to_name = $address_book['number_given_name'].' '.$address_book['entity_family_name'];
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $subject = ' CM Docs Application '.SITE_WWW;

        //message
        $message = '';

        $sex = 'his';

        if ($candidate['sex'] === 'female') {
            $sex = 'her';
        }

        $message .= '<h1>'.$candidate_name.' Has Been Upload '.$sex.' Docs Application For Visa Registration</h1>';
        $message .= '<p>Candidate in the name of '.$candidate_name.' has been docs application date on '.$date.'</p>';
        $message .= '<p>Thank you.</p>';

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

    public function sendAdminConfirmationBgc($address_book_id, $candidate_id)
    {
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $to_name = $address_book['number_given_name'].' '.$address_book['entity_family_name'];
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $candidate = $address_book_db->getAddressBookMainDetails($candidate_id);
        $candidate_name = $candidate['number_given_name'].' '.$candidate['entity_family_name'];

        $subject = ' BGC Confirmation - CM Was Confirm BGC '.SITE_WWW;

        $message = '';

        $message .= '<h1>BGC Confirmation</h1>';
        $message .= '<p>Candidate in the name of '.$candidate_name.' has been confirm BGC</p>';

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

    public function sendAdminConfirmationInterviewDate($address_book_id, $candidate_id, $term, $date)
    {
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $candidate = $address_book_db->getAddressBookMainDetails($candidate_id);
        $candidate_name = $candidate['number_given_name'].' '.$candidate['entity_family_name'];
        
        $to_name = $address_book['number_given_name'].' '.$address_book['entity_family_name'];
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $subject = ' CM Docs Application '.SITE_WWW;

        //message
        $message = '';

        $sex = 'his';

        if ($candidate['sex'] === 'female') {
            $sex = 'her';
        }

        $message .= '<h1>'.$candidate_name.' Has Been Set '.$sex.' Interview Date For Visa Registration</h1>';
        $message .= '<p>Candidate in the name of '.$candidate_name.' has been '.$term.' date on '.$date.'</p>';
        $message .= '<p>Thank you.</p>';

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

    public function sendVisaRegistrationDate($address_book_id)
     {
        $mailing_common = new \core\modules\send_email\models\common\common;
         $generic = \core\app\classes\generic\generic::getInstance();
         $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
         $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);
        
         $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

         $to_email = $address_book['main_email'];
 
         //from the system info
         $system_register = \core\app\classes\system_register\system_register::getInstance();
         $from_name = $system_register->site_info('SITE_EMAIL_NAME');
         $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('tracker_visa_register', [
            'to_name' => $to_name
        ]);

        if ($template) {
            $subject = $template['subject'];
        } else {
            $subject = 'Register Visa Notification : ' . SITE_WWW;
        }

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
         return $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
     }

     public function sendDocsApplicationReminder($address_book_id, $docs_application_date)
     {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $generic = \core\app\classes\generic\generic::getInstance();
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $address_book = $address_book_db->getAddressBookMainDetails($address_book_id);

        $to_name = $to_name = $generic->getName('per', $address_book['entity_family_name'], $address_book['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $address_book['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $mailing_common->renderEmailTemplate('tracker_visa_docs_application', [
            'to_name' => $to_name,
            'docs_application_date' => date('M d, Y', strtotime($docs_application_date))
        ]);

        if ($template) {
            $subject = $template['subject'];
        } else {
            $subject = 'Visa Docs Application : ' . SITE_WWW;
        }

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
        return $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
    }

    public function sendRequestClearance($job_application_id){

        $workflow_db = new security_check_db();

        $security_checks = $workflow_db->getInterviewSecurityCheckByArray($job_application_id);
        $results = [];
        foreach ($security_checks as $key => $item){
            $results[$item['principal_code']][] = $item;
        }

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        //subject
        $subject = 'Security Check - Request Security Clearance - '.SITE_WWW;


        foreach ($results as $group_check){
            $to_name = $group_check[0]['number_given_name'].' '.$group_check[0]['entity_family_name'];
            $to_email = $group_check[0]['main_email'];

            ob_start();
            include(DIR_MODULES . '/interview/views/security_check/mail/request_clearance.php');
            $message = ob_get_contents();
            ob_end_clean();

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
            $rs = $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
            if($rs){
                foreach ($group_check as $item){
                    $workflow_db->updateInterviewSecurityCheckRequestClearance($item['job_application_id']);
                }
            }
        }
    }

    public function triggerMasterWorkflow($reference_primary_field, $reference_primary_value, $principal_code, $type, $data = array())
    {
        $steps = [];
        $master_workflow_db = new master_workflow_db();
        $workflow_db = new db();

        if (empty($principal_code)) {
            $workflows = $master_workflow_db->getDeploymentWorkflow();
        } else {
            $workflows = $master_workflow_db->checkWorkflow($principal_code, $type, $data['step']);
        }


        if (!$workflows) {
            return false;
        }

        foreach ($workflows as $key => $workflow) {
            $reference = $master_workflow_db->getFromReferenceTable($workflow['reference_table'], $reference_primary_field, $reference_primary_value);
            if (!$reference) {
                throw new \RuntimeException("Error Processing Request");
            }

            $tracker_table = 'workflow_'.$workflow['step'].'_tracker';
            $primary_field_tracker = 'address_book_id';
            $primary_value_tracker = $reference['address_book_id'];

            if ($workflow['step'] === 'travelpack') {
                $primary_field_tracker = 'job_application_id';
                $primary_value_tracker = $data['job_application_id'];
            }
            
            $reserved_date = $this->getReserveDate($reference[$workflow['reference_milestone']], $workflow['reference_direction'], $workflow['days']);
            
            switch ($workflow['step']) {
                case 'oktb':
                    $workflow_db->insertToQueue($primary_field_tracker, $primary_value_tracker, $tracker_table, $workflow['type'], $data['deploy_date'], $reserved_date, [
                        'oktb_types' => $data['oktb_types'],
                    ]);
                break;

                case 'visa':
                    $workflow_db->insertToQueue($primary_field_tracker, $primary_value_tracker, $tracker_table, $workflow['type'], $data['deploy_date'], $reserved_date, [
                        'visa_types' => $data['visa_types'],
                    ]);
                break;

                case 'stcw':
                    $workflow_db->insertToQueue($primary_field_tracker, $primary_value_tracker, $tracker_table, $workflow['type'], $data['deploy_date'], $reserved_date, [
                        'stcw_types' => $data['stcw_types'],
                    ]);
                break;

                case 'medical':
                    $workflow_db->insertToQueue($primary_field_tracker, $primary_value_tracker, $tracker_table, $workflow['type'], $data['deploy_date'], $reserved_date, [
                        'medical_types' => $data['medical_types'],
                    ]);
                    break;

                case 'vaccination':
                    $workflow_db->insertToQueue($primary_field_tracker, $primary_value_tracker, $tracker_table, $workflow['type'], $data['deploy_date'], $reserved_date, [
                        'vaccine_types' => $data['vaccine_types'],
                    ]);
                    break;
                
                default:
                    $workflow_db->insertToQueue($primary_field_tracker, $primary_value_tracker, $tracker_table, $workflow['type'], $data['deploy_date'], $reserved_date);
                break;
            }
            
        }
    }

    public function getReserveDate($time_milestone, $reference_direction, $days)
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $day_timestamps = (60 * 60 * 24) * $days;
        
        if ($reference_direction === 'after') {
            $t = strtotime($time_milestone) + $day_timestamps;
        }

        if($reference_direction === 'before') {

            $t = strtotime($time_milestone) - $day_timestamps;
        }

        return date('Y-m-d H:i:s', $t);
    }

    public function triggerQueue()
    {
        $workflow_master_db = new master_workflow_db();
        $workflow_db = new db();

        $queues = $workflow_master_db->getQueueToWork();
        $isSuccess = false;

        if (count($queues) === 0) {
            return false;
        }
        
        foreach ($queues as $key => $queue) {

            if ($workflow_db->isQueueActive($queue['tracker_table'], $queue['primary_field'], $queue['primary_value'], ['deployment_type' => 'deployment'])) {
                # code...
                if ($queue['deployment_type'] === 'deployment') {
                    if ($queue['oktb_type'] !== '') {
                        $oktb_types = explode(',', $queue['oktb_type']);

                        foreach ($oktb_types as $key => $oktb) {
                            # code...
                            $workflow_db->insertOktbTracker($queue['primary_value'], $queue['deployment_date'], $oktb);
                        }
                        $isSuccess = true;
                    } else if($queue['visa_type'] !== '') {
                        $visa_types = explode(',', $queue['visa_type']);

                        foreach ($visa_types as $key => $visa) {
                            $workflow_db->insertVisaTracker($queue['primary_value'], $queue['deployment_date'], $visa);
                        }

                        $isSuccess = true;
                    } else if($queue['vaccine_type'] !== '') {
                        $vaccine_types = explode(',', $queue['vaccine_type']);

                        foreach ($vaccine_types as $key => $vaccine) {
                            # code...
                            $workflow_db->insertVaccineTracker($queue['primary_value'], $queue['deployment_date'], $vaccine);
                        }

                        $isSuccess = true;
                    } else if($queue['medical_type'] !== '') {
                        $medical_types = explode(',', $queue['medical_type']);

                        foreach ($medical_types as $key => $medical) {
                            $workflow_db->insertMedicalTracker($queue['primary_value'], $queue['deployment_date'], $medical);
                        }

                        $isSuccess = true;
                    } else if($queue['stcw_type'] !== '') {
                        $stcw_types = explode(',', $queue['stcw_type']);

                        foreach ($stcw_types as $key => $stcw) {
                            $isSuccess = $workflow_db->insertStcwTracker($queue['primary_value'], $queue['deployment_date'], $stcw);
                        }
                    } else {
    
                        $isSuccess = $workflow_db->insertDeploymentTracker($queue['tracker_table'], $queue['primary_field'], $queue['primary_value'], $queue['deployment_date']);
                    }
        
                }
            }
    
            if ($isSuccess) {
                $workflow_master_db->updateQueueStatus($queue['id'], 'success');
            } else {
                $workflow_master_db->updateQueueStatus($queue['id'], 'failed');
            }

            $workflow_master_db->updateQueueAttempts($queue['id'], (int)($queue['attempts'] + 1));
        }
        
    }

    public function triggerVaccination()
    {
        $db = new vaccine_db();

        $db->updateVaccineAppointmentDue();
    }

    public function triggerMedical()
    {
        $db = new medical_db();

        $db->updateMedicalAppointmentDue();
    }
	
}
?>