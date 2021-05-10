<?php
namespace core\modules\offer_letter\models\common;

/**
 * Final offer_letter common class.
 *
 * @final
 * @package		offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 May 2020
 */
final class common {
		
	public function __construct()
	{					
		return;
	}

	public function sendEndorsement($job_applications){
        $mailing_db = new \core\modules\send_email\models\common\db;
        $mailing_common = new \core\modules\send_email\models\common\common;

        $offer_letter_db = new db();

        $security_checks = $offer_letter_db->getOfferLetterTrackerArray($job_applications);
        $results = [];
        foreach ($security_checks as $key => $item){
            $results[$item['job_master_id']][] = $item;
        }
        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        //subject

        foreach ($results as $endorser){

            

            $to_name = $endorser[0]['number_given_name'].' '.$endorser[0]['entity_family_name'];
            $to_email = $endorser[0]['main_email'];

            ob_start();
            include(DIR_MODULES . '/offer_letter/views/trackers/mail/request_endorsement.php');
            $content = ob_get_contents();
            ob_end_clean();

            $template = $mailing_common->renderEmailTemplate('request_endorsement', [
                'content' => $content
            ]);
            $subject = $template['subject'];

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
            $rs = $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
            if($rs){
                foreach ($endorser as $item){
                    $offer_letter_db->updateTrackerRequestEndorsement($item['job_application_id']);
                }
            }
        }
    }

    public function sendOfferLetterReminder($job_application_id)
    {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $menu_register = \core\app\classes\menu_register\menu_register::getInstance();

        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
        
        $job_db = new \core\modules\job\models\common\db();
        $job_application = $job_db->getJobApplication($job_application_id);
        $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $ab = $address_book_db->getAddressBookMainDetails($job_application['address_book_id']);
		
        $to_name = $from_name;
        $to_email = $from_email;

        $generic_obj = \core\app\classes\generic\generic::getInstance();
		$full_name = $generic_obj->getName('per',$ab['entity_family_name'], $ab['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

		$data = array(
			'name' => $full_name,
			'link' => HTTP_TYPE . SITE_WWW . $menu_register->getModuleLink('job_application')
		);

		$template = $mailing_common->renderEmailTemplate('offer_letter_reminder', $data);
		
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
	
}
?>