<?php
namespace core\modules\pages\ajax;

/**
 * Final feedback class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class feedback extends \core\app\classes\module_base\module_ajax {
	
	protected $catchaRequired = true; //we must see a catcha
	protected $errors = array(); //need this for catcha
	
	public function run()
	{
		//set variables (it has been sanitzed by the base class)
		$feedback_name = trim($_POST['feedback_name']);
		$feedback_email = trim($_POST['feedback_email']);
		$feedback_phone = trim($_POST['feedback_phone']);
		$feedback_text = trim($_POST['feedback_text']);
		$link_id = trim($_POST['link_id']);
		$content_id = trim($_POST['content_id']);
		//recaptcha
		$reCAPTCHA_Token = isset($_POST['reCAPTCHA_Token']) ? $_POST['reCAPTCHA_Token'] : '';
		
		$reCAPTCHA_score = '';
		
		if(!empty($reCAPTCHA_Token))
		{
			$reCAPTCHA = new \core\app\classes\recaptcha\recaptcha($reCAPTCHA_Token);
			
			if($reCAPTCHA->getSucess())
			{
				$reCAPTCHA_score = $reCAPTCHA->getScore();
			
				//$this->errors['reCAPTCHA Score'] = $reCAPTCHA->getScore();
				if($reCAPTCHA_score < 0.5)
				{
					$this->errors['reCAPTCHA Score'] = 'Sorry your reCAPTCHA Score is too low';
				}
				
				
			} else {
				
				$this->errors['reCAPTCHA Error '] = 'Sorry we can not submit your form at this time';
				
				/*
				foreach($reCAPTCHA->getErrorArray() as $key => $error)
				{
					$this->errors['reCAPTCHA Error '.$key] = $error;
				}
				*/
			}
		}

		//run error checking
		$pages_common = new \core\modules\pages\models\common\common();

		$feedbackErrors = $pages_common->feedbackFormErrorsArray($feedback_name,$feedback_email,$feedback_text);

		$errors = array_merge($this->errors,$feedbackErrors);
		if(empty($errors))
		{
			//send an email
			$pages_common->sendFeedback($feedback_name,$feedback_email,$feedback_phone,$feedback_text,$link_id,$content_id,$reCAPTCHA_score);
			unset($_SESSION['Md5_GenCode_CATCHA']); //now we have submitted we need to clear the catcha
			$out['success'] = true;
			
		} else {
			$out['success'] = false;
			
			//note
			$note = '';
			foreach($errors as $key => $info)
			{
				$note .= $key.": ".$info."\n\n";
			}
			$out['message'] = $note;
		}

				
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}

}
?>