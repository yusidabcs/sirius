<?php namespace core\app\classes\generic;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * Final generic_obj class.
 * 
 * This is the actual generic object itself.  Changes to its behaviour go here
 * generic_obj class is class to do generic function in system
 * @final
 * @package 	generic
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class generic_obj {

	public function __construct()
	{
		return;
	}
	
	//!String Processing
	
	public function safeFilename($str)
	{
		//trim and convert to lower case
		$str = $this->makeSafeString($str);
				
		//lowercase the string
		$str = strtolower($str);
		
		//convert spaces to _
		$str = str_replace(' ', '_', $str);
		
		return $str;
	}

	public function safeLinkId($str)
	{
		//trim and convert to lower case
		$str = $this->makeSafeString($str);
				
		//lowercase the string
		$str = strtolower($str);
		
		//convert spaces to _
		$str = str_replace(' ', '-', $str);
		
		return $str;
	}
	
	public function safeUserId($str)
	{
		//trim and convert to lower case
		$str = $this->makeSafeString($str);
		
		//lowercase the string
		$str = strtolower($str);

		//remove the spaces
		$str = str_replace(' ', '', $str);
		
		return $str;
	}
	
	public function makeSafeString($str)
	{
		//trim
		$str = trim($str);
		
		//remove excess whitespace, leaving only a single space between words. 
	    $str = preg_replace('/\s+/', ' ', $str);
	    
	    //remove any special html characters for example apostrophe &#39; 
		$str = preg_replace('/&.{2,3};/', '', $str);
		
		/** Normalize a string so that it can be compared with others without being too fussy.
		*   e.g. " dr���l n " would return "adrenaline"
		*   Note: Some letters are converted into more than one letter, 
		*   e.g. " " becomes "sz", or " " becomes "ae"
		*/
		$str = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($str, ENT_COMPAT, 'UTF-8'));
		
		//finally only keep words, digits, - and _
		$str = preg_replace("([^\w\d\s\-_])", '', $str);
				
		return $str;
	}
	
	public function removeBS($str)
	{  
		$strArr = str_split($str); 
		$newStr = '';
		
		foreach ($strArr as $char) 
		{    
			$charNo = ord($char);
			
			if ($charNo == 163) { $newStr .= $char; continue; } // keep
			
			if ($charNo > 31 && $charNo < 127) 
			{
				$newStr .= $char;   
			}
		}  
		
		return $newStr;
	}
	
	/* Get Address Book Name */
	
	public function getName($type, $entity_family_name, $number_given_name, $format_per, $format_ent)
	{
		if($type == 'per')
		{
			$name = $this->_buildPerName($format_per,$entity_family_name,$number_given_name);
		} else if($type == 'ent') {
			$name = $this->_buildEntName($format_ent,$entity_family_name,$number_given_name);
		} else {
			$msg = "The getName type was not recognise.";
			throw new \RuntimeException($msg); 
		}
		
		return trim($name);
	}
	
	private function _buildPerName($format_per,$entity_family_name,$number_given_name)
	{
		/*
			Switch based on ADDRESS_BOOK_OUTPUT_PER_NAME
		
			FFCC,Family Name First Capitalised with Comma
			FFCN,Family Name First Capitalised with No-comma
			FFNC,Family Name First with Comma
			FFNN,Family Name First with No-comma
			FLCN,Family Name Last Capitalized with No-comma
			FLNN,Family Name Last with No-comma
		*/
		
		if(empty($entity_family_name))
		{
			
			switch ($format_per) 
			{
			    case 'FFCC':
			        $name = $number_given_name;
			        break;
			    case 'FFCN':
			        $name = $number_given_name;
			        break;
			    case 'FFNC':
			        $name = $number_given_name;
			        break;
			    case 'FFNN':
			        $name = $number_given_name;
			        break;
			    case 'FLCN':
			        $name = $number_given_name;
			        break;  
			    case 'FLNN':
			        $name = $number_given_name;
			        break;
			    default:
			       $name = 'Bad format code given for person';
			}
			
		} else {
			
			switch ($format_per) 
			{
			    case 'FFCC':
			        $name = strtoupper($entity_family_name).', '.$number_given_name;
			        break;
			    case 'FFCN':
			        $name = strtoupper($entity_family_name).' '.$number_given_name;
			        break;
			    case 'FFNC':
			        $name = $$entity_family_name.', '.$number_given_name;
			        break;
			    case 'FFNN':
			        $name = $entity_family_name.' '.$number_given_name;
			        break;
			    case 'FLCN':
			        $name = $number_given_name.' '.strtoupper($entity_family_name);
			        break;  
			    case 'FLNN':
			        $name = $number_given_name.' '.$entity_family_name;
			        break;
			    default:
			       $name = 'Bad format code given for person';
			}
		
		}
		
		return $name;
	}
	
	private function _buildEntName($format_ent,$entity_family_name,$number_given_name)
	{
		/*
			Switch based on ADDRESS_BOOK_OUTPUT_ENT_NAME
		
			ENO,Entity Name Only
			ENB,Entity Name with Entity Number in Brackets
		*/
		if(empty($number_given_name))
		{
			switch ($format_ent) 
			{
			    case 'ENO':
			        $name = $entity_family_name;
			        break; 
			    case 'ENB':
			        $name = $entity_family_name;
			        break;
			    default:
			       $name = 'Bad format code given for entity';
			}
		} else {
			switch ($format_ent) 
			{
			    case 'ENO':
			        $name = $entity_family_name;
			        break; 
			    case 'ENB':
			        $name = $entity_family_name.' ('.$number_given_name.')';
			        break;
			    default:
			       $name = 'Bad format code given for entity';
			}
		}
		
		return $name;
	}
	
	//!Date Related
	
	public function tsDiffStr($ts_1,$ts_2,$format)
	{
		if(empty($ts_1) || empty($ts_2))
		{
			return '-';
		}
		
		$diff = abs($ts_1 - $ts_2);

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
		
		if($format == 1)
		{
			$out = sprintf("%d years", $years, $months, $days);
		} else if($format == 2) {
			$out = sprintf("%d years, %d months", $years, $months, $days);
		} else {
			$out = sprintf("%d years, %d months, %d days", $years, $months, $days);
		}
		
		return $out;
	}

	
	//!Security
	public function generateRandomString($length = 8)
    {
		return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , $length );
	}
	
	public function generateRandomPassword($length = 10)
    {
		return substr(str_shuffle('abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0 , $length );
	}
	
	//!Email

	function sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink,$attachment='',$attachment_title = '',$reply_to='')
	{
	    return $this->sendEmailSES($to_name,$to_email,$subject,$message,$cc,$bcc,$unsubscribelink,$attachment,$attachment_title,$reply_to);

		//error checking
		if(empty($from_email) || empty($to_email))
		{
			//we don't send mail if we don't have both email addresses!
			$msg = "We don't send mail if we don't have both email addresses!";
			throw new \RuntimeException($msg);
		}
		
		//fix up issues
		if(empty($from_name))
		{
			$from_name = $from_email;
		} else {
			$from_name = htmlspecialchars_decode($from_name,ENT_QUOTES); //just in case someone sent some bogus crap
		}
		
		if(empty($to_name))
		{
			$to = $to_email;
		} else {
			$to = '"'.htmlspecialchars_decode($to_name,ENT_QUOTES).'"<'.$to_email.'>';
		}
		
		if(empty($subject)) $subject = "Email from ".$_SERVER['SERVER_NAME'];
		
		//server email
		if(defined('WEBSERVER_EMAIL'))
		{
			$server_email = '-f '.WEBSERVER_EMAIL;
		} else {
			$server_email = '-f '.$from_email;
		}

		//set main headers
		$emailHeaders[] = 'MIME-Version: 1.0';
		$emailHeaders[] = 'Content-type: text/html; charset=UTF-8';
		$emailHeaders[] = 'X-Mailer: PHP/'.phpversion();
		
		//set additional headers
		//$emailHeaders[] = "To: {$to}";
		//$emailHeaders[] = 'From: "'.$from_name.'"<'.$from_email.'>';
		//$emailHeaders[] = "Reply-To: {$from_name} <{$from_email}>";
		//$emailHeaders[] = "Return-Path: {$from_email}";

        $emailHeaders[] = 'From: "'.$from_name.'"<www@merlot.iow.com.au>';
        $emailHeaders[] = "Reply-To: {$from_name} <{$from_email}>";
		
		if(!empty($cc))
		{
			$emailHeaders[] = 'Cc: '.trim($cc);
		}
		
		if(!empty($bcc))
		{
			$emailHeaders[] = 'Bcc: '.trim($bcc);
		}

		//subject for email
		$emailSubject = $subject;
		
		//make email message
		$emailMessage = '';
		
		if($html)
		{
			if($fullhtml)
			{
				$emailMessage = $message;
			} else {
				
				$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
				$system_register = $system_register_ns::getInstance();
				
				if($system_register->getModuleIsInstalled('send_email'))
				{
					ob_start();
						include(DIR_MODULES.'/send_email/views/template/email_template.php');
						$emailMessage = ob_get_contents();
					ob_end_clean();
				} else {			
					$emailMessage .= "<html><head><title>{$emailSubject}</title></head><body>";
					$emailMessage .= "<br />".$message."<br /></body></html>";
				}
			}
			
		} else {
			
			$message = nl2br(htmlspecialchars(stripslashes($message)));
			
			$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
			$system_register = $system_register_ns::getInstance();
			
			if($system_register->getModuleIsInstalled('send_email'))
			{
				ob_start();
					include(DIR_MODULES.'/send_email/views/template/email_template.php');
					$emailMessage = ob_get_contents();
				ob_end_clean();
			} else {
				$emailMessage .= "<html><head><title>$emailSubject</title></head><body>";
				$emailMessage .= "<br />".$message."<br /></body></html>";
			}
		}
		
		if( !@mail($to, $emailSubject, $emailMessage, implode("\r\n", $emailHeaders), $server_email) )
		{
			die("Failed to send");
			return false;
		}

		return true;
	}

	function sendEmailSES($to_name,$to_email,$subject,$message,$cc,$bcc,$unsubscribelink,$attachment = '',$attachment_title = '',$reply_to='')
	{
		//generate a random string for the subject line
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$unique_code = '';
		for ($i = 0; $i < 8; $i++) {
			$unique_code .= $characters[rand(0, $charactersLength - 1)];
		}
		if(empty($subject)) $subject = "Email from ".$_SERVER['SERVER_NAME'];
		$subject = $subject.' ['.$unique_code.']';

        $site_a = parse_ini_file(DIR_SECURE_INI.'/site_config.ini');
		$sender = $site_a['SITE_EMAIL_ADD'];
		$senderName = $site_a['SITE_EMAIL_NAME'];
		$usernameSmtp = $site_a['SITE_EMAIL_SMTP_USERNAME'];
		$passwordSmtp = $site_a['SITE_EMAIL_SMTP_PASSWORD'];
		$host = $site_a['SITE_EMAIL_SMTP'];
		$port = $site_a['SITE_EMAIL_SMTP_PORT'];

		$mail = new PHPMailer(true);
		try {
			// Specify the SMTP settings.
			$mail->isSMTP();
			$mail->setFrom($sender, $senderName);
			$mail->Username   = $usernameSmtp;
			$mail->Password   = $passwordSmtp;
			$mail->Host       = $host;
			$mail->Port       = $port;
			$mail->SMTPAuth   = true;
			$mail->SMTPSecure = 'tls';
		
			// Specify the message recipients.
			$mail->addAddress($to_email, $to_name);

			if (!empty($reply_to)) {
				$mail->addReplyTo($reply_to);
			}
            // You can also add CC, BCC, and additional To recipients here.
			if(!empty($cc))
			{
				$mail->addCC(trim($cc));
			}
			
			if(!empty($bcc))
			{
				$mail->addBCC(trim($bcc));
			}

			if (!empty($attachment)) {
				if (!empty($attachment_title)) { 
					$mail->addAttachment($attachment,$attachment_title);
				} else {
					$mail->addAttachment($attachment);
				}
			}

			$emailMessage = '';

            $mail->isHTML(true);
            $system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
            $system_register = $system_register_ns::getInstance();

            if($system_register->getModuleIsInstalled('send_email') && !empty($message))
            {
                $emailMessage .= $message;
            } else {
                $emailMessage .= "<html><head><title>{$subject}</title></head><body>";
                $emailMessage .= "<br />".$message."<br /></body></html>";
            }

			$mail->Subject    = $subject;
			$mail->Body       = $emailMessage;
			$mail->Send();
			return true;
		} catch (phpmailerException $e) {
		    echo $e->errorMessage();
			return [
			    'message' => $e->errorMessage()
            ];
		} catch (Exception $e) {
            echo $mail->ErrorInfo;
            return [
                'message' => $mail->ErrorInfo
            ];
		}
		return false;
	}

}
?>