<?php
namespace core\modules\pages\models\common;

/**
 * Final pages_common class.
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class common {
	
	private $_contentArray = array(); //Content from the content.in file used to change interfaces on editing
	
	public function __construct()
	{
		$pages_db_ns = NS_MODULES.'\\pages\\models\\common\\db';
		$this->pages_db = new $pages_db_ns();
		
		$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		$this->file_manager = $file_manager_ns::getInstance();
		
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$this->system_register = $system_register_ns::getInstance();

		return;
	}
	
	//!GETTERS
	
	public function getAllPageContentInfo($link_id)
	{
		$out = array();
		
		//get information from my tables
		$out = $this->pages_db->getPageInfo($link_id);
		
		//now get the file information
		$out['file_manager'] = $this->file_manager->getFilesArray($link_id);
		return $out;	
	}

    public function getPageContent($link_id,$content_id)
    {
        $out = array();

        //get information from my tables
        $out = $this->pages_db->getPageContent($link_id,$content_id);
        return $out;
	}
	
	public function getPageContentAjax($link_id,$content_id)
    {
        $out = array();

        //get information from my tables
        $out = $this->pages_db->getPageContentAjax($link_id,$content_id);
        return $out;
    }
	
	public function getTemplate($content_type)
	{
		if(empty($this->_contentArray))
		{
			$file = DIR_MODULE.'/models/common/content.ini';
			
			if(is_file($file))
		    {
		    	$this->_contentArray = parse_ini_file($file,false);   
			} else {
		    	$msg = "The INI file {$file} can not be found in the pages common directory!";
		    	throw new \RuntimeException($msg); 
		    }
		}
		
		if(isset($this->_contentArray[$content_type]))
		{
			$out = $this->_contentArray[$content_type];
		} else {
	    	$msg = "Error - their is no template for {$content_type}";
	    	throw new \RuntimeException($msg); 
	    }
		
		return $out;
	}
	
	//!SETTERS
		
	public function updateContentInfo($data)
	{
		return ($this->pages_db->updatePageContent($data) != -1) ? true : false;
	}
	
	public function updateContentSort($link_id,$sequence)
	{
		//update the sequence
		$this->pages_db->commitOff();
		
		foreach($sequence as $key => $value)
		{
			$value = ltrim($value,'entry-');
			$this->pages_db->updateSort($link_id,$key,$value);
		}
		
		$this->pages_db->commit();
		$this->pages_db->commitOn();
		
		return true;
	}
	
	public function deleteContentInfo($link_id,$content_id)
	{
		//delete the files first
		$this->file_manager->deleteLinkIds($link_id,'entry-'.$content_id);
		
		//delete the page_content
		$this->pages_db->deletePageContent($link_id,$content_id);
		return true;
	}
	
	//!FEEDBACK FUNCTIONS
	
	public function feedbackFormErrorsArray($feedback_name,$feedback_email,$feedback_text)
	{	
		$errors = array();
		
		//name
		if(empty($feedback_name)) $errors['Name'] = $this->system_register->site_term('FEEDBACK_NAME_BLANK_ERROR');
		
		//email
		if(empty($feedback_email)) $errors['Email'] = $this->system_register->site_term('FEEDBACK_EMAIL_BLANK_ERROR');
		
		if(filter_var($feedback_email, FILTER_VALIDATE_EMAIL))
		{
			//ok now do an mx check on the domain
			list($email_name,$domain) = explode('@',$feedback_email);
			if(!checkdnsrr($domain,'MX'))
			{
				$errors['Email'] = $this->system_register->site_term('FEEDBACK_EMAIL_BAD_ERROR');
			} 
		} else {
			$errors['Email'] = $this->system_register->site_term('FEEDBACK_EMAIL_BAD_ERROR');
		}
		
		//text
		if(empty($feedback_text)) $errors['Feedback'] = $this->system_register->site_term('FEEDBACK_TEXT_BLANK_ERROR');
				
		return $errors;
	}

	public function sendFeedback($feedback_name,$feedback_email,$feedback_phone,$feedback_text,$link_id,$content_id,$reCAPTCHA_score = '')
	{
		$submit_array = $this->pages_db->getContactFormInfo($link_id,$content_id);
		
		$to_name = empty($submit_array['to_name']) ? $this->system_register->siteEmailName() : $submit_array['to_name'];
		$to_email = empty($submit_array['to_email']) ? $this->system_register->siteEmailAddress() : $submit_array['to_email'];
		$to_subject = empty($submit_array['to_subject']) ? $this->system_register->siteEmailSubject() : $submit_array['to_subject'];
		
		$from_name = $feedback_name;
		$from_email = $feedback_email;
		$from_phone = $feedback_phone;
		
		$feedback = $feedback_text;
		
		$submitted_heading = empty($submit_array['submitted_heading']) ? $this->system_register->site_term('FEEDBACK_DEFAULT_HEADING') : $submit_array['submitted_heading'];
		$submitted_sdesc = empty($submit_array['submitted_sdesc']) ? $this->system_register->site_term('FEEDBACK_DEFAULT_SDESC') : $submit_array['submitted_sdesc'];
		$submitted_content = empty($submit_array['submitted_content']) ? $this->system_register->site_term('FEEDBACK_DEFAULT_CONTENT') : $submit_array['submitted_content'];
		
		//used in the templace
		$out['submitted_heading'] = $submitted_heading;
		$out['submitted_sdesc'] = $submitted_sdesc;
		$out['submitted_content'] = $submitted_content;
		
		$this->_sendFeedbackEmail($to_name,$to_email,$to_subject,$from_name,$from_email,$from_phone,$feedback,$reCAPTCHA_score);
		
		return $out;
	}
	
	private function _sendFeedbackEmail($to_name,$to_email,$subject,$from_name,$from_email,$from_phone,$feedback,$reCAPTCHA_score)
	{	
		$mailing_common = new \core\modules\send_email\models\common\common;
		
		$site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');

		if ($site_a['GEO_IP'] == 1) {
			# code...
			$geoip = new \core\app\classes\geoip\geoip('city',$_SERVER['REMOTE_ADDR']);
			$location = $geoip->getLocationDetails();
		}

		$template = $mailing_common->renderEmailTemplate('feedback', [
			'subject' => $subject,
			'from_name' => $from_name,
			'from_email' => $from_email,
			'from_phone' => $from_phone,
			'reCAPTCHA_score' => $reCAPTCHA_score,
			'feedback' => $feedback,
			'location' => $location
		]);
		
		//subject
		$ticket = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10 );
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