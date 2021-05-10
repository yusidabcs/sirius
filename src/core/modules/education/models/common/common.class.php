<?php
namespace core\modules\finance\models\common;

/**
 * Final finance common class.
 *
 * @final
 * @package		finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 15 Jun 2020
 */
final class common {
		
	public function __construct()
	{
        $this->db = new db();
		return;
	}

    public function updateTrackerLevel($table){
        $data = $this->db->selectTrackerLevel($table);
        $now = time();
        foreach ($data as $index => $item){
            //calculate warning level
            if($item['reference_milestone'] == ''){
                $data[$index]['level'] = 'normal';
                continue;
            }

            $reference_date = $item[$item['reference_milestone']];

            if($item['reference_direction'] == 'after')
            {
                if( $now > strtotime($reference_date.' + '.$item['deadline'].' days') )
                {
                    $level = '4';
                } else if( $now > strtotime($reference_date.' + '.$item['hard_warning'].' days') ) {
                    $level = '3';
                } else if( $now > strtotime($reference_date.' + '.$item['soft_warning'].' days') ) {
                    $level = '2';
                } else {
                    $level = '1';
                }

            } else {

                if( $now > strtotime($reference_date.' - '.$item['deadline'].' days') )
                {
                    $level = '4';
                } else if( $now > strtotime($reference_date.' - '.$item['hard_warning'].' days') ) {
                    $level = '3';
                } else if( $now > strtotime($reference_date.' - '.$item['soft_warning'].' days') ) {
                    $level = '2';
                } else {
                    $level = '1';
                }
            }

            $this->db->updateTrackerLevel($table,$item['job_application_id'], $level);

        }
    }

    public function sendPSFTrackerReport(){
        $list = $this->db->getReportsArray();
        $trackers = $this->db->getTotalPsfTrackerByLevel();
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

        $template = $mailing_common->renderEmailTemplate('psf_tracker_report', [
            'soft_warning' => $this->soft_warning,
            'hard_warning' => $this->hard_warning,
            'deadline' => $this->deadline
        ]);
        
        $title = $template['title'];

        if ($template) {
            $subject = $template['subject'];
        } else {
            $subject = $title .' - Warning Level Notification - '.SITE_WWW;
        }


        $message = $template['html'];


        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        
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
	
}
?>