<?php
namespace core\modules\security\models\common;

/**
 * Final security_base class.
 * 
 * @final
 * @package security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 12 December 2015
 */
final class security_base {
	
	public function __construct()
	{
		return;
	}

    /**
     * to send password reset link to userr
     *
     * @param $to_name
     * @param $to_email
     * @param $from_name
     * @param $from_email
     * @param $siteURL
     * @param $forgotLink
     */
    public function sendPasswordRestEmail($to_name, $to_email, $from_name, $from_email, $siteURL, $forgotLink)
	{
		$common_email = new \core\modules\send_email\models\common\common;
		//need a reset code
		$random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
		$resetCode = md5($random_string);
		
		//insert the site
		$security_db_ns = NS_MODULES.'\security\models\common\security_db';
		$security_db = new $security_db_ns;
		$security_db->setResetCode($resetCode,$to_email);

		$template = $common_email->renderEmailTemplate('password_reset', array('reset_link' => HTTP_TYPE.$siteURL.'/'.$forgotLink.'/'.$resetCode));
		
		//subject		
		$subject = $template['subject'];
		
		//message
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