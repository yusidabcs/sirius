<?php
namespace core\modules\education_application\models\common;


final class common extends \core\app\classes\module_base\module_db{
	public function __construct()
	{	
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;	
    }

    public function senEmailRequestCourse($address_book_id,$course) {
        //send mail to LP
        $ab_db = new \core\modules\address_book\models\common\address_book_db_obj();
        $ab = $ab_db->getAddressBookMainDetails($address_book_id);
        $connection = $ab_db->getAddressBookConnection($address_book_id, 'lp');
        if($connection){
            $partner = $ab_db->getAddressBookMainDetails($connection['connection_id']);
            $this->_sendEmailToLP($ab,$partner);
        }
        
        //send email to user
        if($ab['main_email']!='') {
            $this->_sendEmailToUser($ab,$course);
        }

    }

    private function _sendEmailToLP($ab, $partner){
        $common_email = new \core\modules\send_email\models\common\common;
        $to_name = empty($partner['entity_family_name']) ? $partner['number_given_name'] : $partner['entity_family_name'];
        $to_email = $partner['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $template = $common_email->renderEmailTemplate('request_course', array('user_name'=>$ab['entity_family_name'].' '.$ab['number_given_name']));

        //subject
        $subject = $template['subject'];
        $message = $template['html'];
       
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
    
    private function _sendEmailToUser($ab,$course){
        $common_email = new \core\modules\send_email\models\common\common;
        $to_name = empty($ab['entity_family_name']) ? $ab['number_given_name'] : $ab['entity_family_name'].' '.$ab['number_given_name'];
        $to_email = $ab['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');

        $data = array(
            'user'=>$ab['entity_family_name'].' '.$ab['number_given_name'],
            'course_name'=>$course['course_name']
        );
        $template = $common_email->renderEmailTemplate('info_join_course', $data);

        //subject
        
        $subject = $template['subject'];
        
        $message = $template['html'];
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