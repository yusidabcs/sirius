<?php
namespace core\modules\personal\models\common;

use verification;

/**
 * Final personal common class.
 *
 * @final
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class common {
	public function __construct()
	{
		
		$this->personal_db = new db;			
		return;
	}
	
	public function getProfileInfo($address_book_id)
	{
		$profileData = $this->personal_db->getProfileData($address_book_id);
		$profileInfo = [];		
		foreach($profileData as $key => $value)
		{
            $profileInfo[$key] = [
                'value' => $value
            ];
			switch ($key) 
			{
			    case 'education':
                    $profileInfo[$key]['icon'] = 'fa-user-graduate';
			        break;
                case 'employment':
                    $profileInfo[$key]['icon'] = 'fa-store';
                    break;
                case 'language':
                    $profileInfo[$key]['icon'] = 'fa-language';
                    break;
                case 'passport':
                    $profileInfo[$key]['icon'] = 'fa-passport';
                    break;
                case 'visa':
                    $profileInfo[$key]['icon'] = 'fa-passport';
                    break;
                case 'medical':
                    $profileInfo[$key]['icon'] = 'fa-file-medical';
                    break;
                case 'reference':
                    $profileInfo[$key]['icon'] = 'fa-handshake';
                    break;
                case 'idcard':
                    $profileInfo[$key]['icon'] = 'fa-id-card';
                    break;
                case 'idcheck':
                    $profileInfo[$key]['icon'] = 'fa-user-check';
                    break;
                case 'tattoo':
                    $profileInfo[$key]['icon'] = 'fa-child';
                    break;
                case 'checklist':
                    $profileInfo[$key]['icon'] = 'fa-tasks';
                    break;
                case 'english_test':
                    $profileInfo[$key]['icon'] = 'fa-book';
                    break;
                case 'vaccination':
                    $profileInfo[$key]['icon'] = 'fa-user-md';
                    break;
			       
			    default:
                    $profileInfo[$key]['icon'] = 'fa-school';
			}
		}
		
		return $profileInfo;
	} 
	
	public function deactivateAll()
	{
		$functions_array = array('deactiveatePassport','deactiveateVisa','deactiveateIDCard');
		
		$out = "Deactivating all items that are out of date.";
		
		foreach($functions_array as $method)
		{
			$this->personal_db->$method();
		}
		
		return $out;
	}
	
	public function deletePassport($passport_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkPassportExists($passport_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-passport
			$rp_removed = $this->personal_db->deletePassport($passport_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The passport did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'passport',$passport_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

    public function deletePolice($police_id,$personal_id,$filename)
    {
        $out = false;

        //first check that the thing exists
        $exists = $this->personal_db->checkPoliceExists($police_id,$personal_id);

        if($exists)
        {
            //delete it from personal-passport
            $rp_removed = $this->personal_db->deletePolice($police_id,$personal_id);

            if(!$rp_removed)
            {
                $msg = "The passport did not delete from the personal db!";
                throw new \RuntimeException($msg);
            }

            //delete it from address book file and entry if there is a file
            if($filename)
            {
                //delete the file
                $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
                $filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);

                //delete the address book entry
                $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
                $affected = $address_book_db->deleteAddressBookFile($personal_id,'passport',$police_id);

            }

            $out = true;

        }

        return $out;
	}
	
	public function deleteSeaman($seaman_id,$personal_id,$filename)
    {
        $out = false;

        //first check that the thing exists
        $exists = $this->personal_db->checkSeamanExists($seaman_id,$personal_id);

        if($exists)
        {
            //delete it from personal-passport
            $rp_removed = $this->personal_db->deleteSeaman($seaman_id,$personal_id);

            if(!$rp_removed)
            {
                $msg = "The seaman did not delete from the personal db!";
                throw new \RuntimeException($msg);
            }

            //delete it from address book file and entry if there is a file
            if($filename)
            {
                //delete the file
                $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
                $filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);

                //delete the address book entry
                $address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
                $affected = $address_book_db->deleteAddressBookFile($personal_id,'seaman',$seaman_id);

            }

            $out = true;

        }

        return $out;
    }
	
	public function deleteVisa($visa_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkVisaExists($visa_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-visa
			$rp_removed = $this->personal_db->deleteVisa($visa_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The visa did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'visa',$visa_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

	public function deleteFlight($flight_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkFlightExists($flight_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-flight
			$rp_removed = $this->personal_db->deleteFlight($flight_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The flight did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'flight',$flight_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

	public function deleteOktb($oktb_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkOktbExists($oktb_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-oktb
			$rp_removed = $this->personal_db->deleteOktb($oktb_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The oktb did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'oktb',$oktb_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}
	
	public function deleteIDCard($idcard_id,$personal_id,$filename,$filename_back)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkIDCardExists($idcard_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-idcard
			$rp_removed = $this->personal_db->deleteIDCard($idcard_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The idcard did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			if($filename || $filename_back)
			{
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$delete_abook_entry = true;
				
				//delete it from address book file and entry if there is a file
				if($filename)
				{
					//delete the file
					$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
					
					//delete the address book entry
					$affected = $address_book_db->deleteAddressBookFile($personal_id,'idcard',$idcard_id);
					$delete_abook_entry = false;
					
				}
				
				//delete it from address book file and entry if there is a file
				if($filename_back)
				{
					//delete the file
					$filename_back = $address_book_common->deleteAddressBookFile($filename_back,$personal_id);
					
					if($delete_abook_entry)
					{
						//delete the address book entry
						$affected = $address_book_db->deleteAddressBookFile($personal_id,'idcard',$idcard_id);
					}
				}

			}
			
			$out = true;
			
		}
		
		return $out;
	}
	
	public function deleteEnglish($english_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkEnglishExists($english_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-english
			$rp_removed = $this->personal_db->deleteEnglish($english_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The english did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'english',$english_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}
	
	public function deleteEmployment($employment_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkEmploymentExists($employment_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-employment
			$rp_removed = $this->personal_db->deleteEmployment($employment_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The employment did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'employment',$employment_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

	public function deleteEducation($education_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkEducationExists($education_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-education
			$rp_removed = $this->personal_db->deleteEducation($education_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The education did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'education',$education_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}
	
	public function deleteTattoo($tattoo_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkTattooExists($tattoo_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-tattoo
			$rp_removed = $this->personal_db->deleteTattoo($tattoo_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The tattoo did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'tattoo',$tattoo_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}
	
	public function deleteReference($reference_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkReferenceExists($reference_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-reference
			$rp_removed = $this->personal_db->deleteReference($reference_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The reference did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'reference',$reference_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

	public function deleteMedical($medical_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkMedicalExists($medical_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-medical
			$rp_removed = $this->personal_db->deleteMedical($medical_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The medical did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'medical',$medical_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}
	
	public function deleteVaccination($vaccination_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkVaccinationExists($vaccination_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-vaccination
			$rp_removed = $this->personal_db->deleteVaccination($vaccination_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The vaccination did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'vaccination',$vaccination_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

	public function deleteIdcheck($idcheck_id,$personal_id,$filename)
	{
		$out = false;
		
		//first check that the thing exists
		$exists = $this->personal_db->checkIdcheckExists($idcheck_id,$personal_id);
		
		if($exists)
		{
			//delete it from personal-idcheck
			$rp_removed = $this->personal_db->deleteIdcheck($idcheck_id,$personal_id);
			
			if(!$rp_removed)
			{
				$msg = "The idcheck did not delete from the personal db!";
				throw new \RuntimeException($msg);
			}
			
			//delete it from address book file and entry if there is a file
			if($filename)
			{
				//delete the file
				$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
				$filename = $address_book_common->deleteAddressBookFile($filename,$personal_id);
				
				//delete the address book entry
				$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
				$affected = $address_book_db->deleteAddressBookFile($personal_id,'idcheck',$idcheck_id);
				
			}
			
			$out = true;
			
		}
		
		return $out;
	}

	public function requestVerification($personal_id,$data=array())
	{
		$out = false;
		
		//insert new verification request
		$verification_info='';
		if(isset($data['info_request'])) {
			$verification_info = $data['info_request'];
		}
		$affected_rows = $this->personal_db->insertVerification($personal_id,'request',$verification_info);
		if($affected_rows != 1)
		{
			$msg = "There was a major issue with insertVerification in personal verification for address id {$personal_id}. Affected was {$affected_rows}";
			throw new \RuntimeException($msg);
		}
		$out = true;
		//send mail to LP
        $ab_db = new \core\modules\address_book\models\common\address_book_db_obj();
        $ab = $ab_db->getAddressBookMainDetails($personal_id);
        $connection = $ab_db->getAddressBookConnection($personal_id, 'lp');
        if($connection){
            $partner = $ab_db->getAddressBookMainDetails($connection['connection_id']);
            $this->_sendEmailToLP($ab,$partner);
        } else {
			$this->_sendEmailToAdmin($ab);
		}

        //insert recruitment workflow
        $recruitment_workflow_db = new \core\modules\workflow\models\common\recruitment_db();
		$recruitment_workflow_db->deleteTracker($personal_id);
		$recruitment_workflow_db->insertTracker($personal_id);

		
		return $out;
	}

	private function _sendEmailToLP($ab, $partner){

		$common_email = new \core\modules\send_email\models\common\common;

        $to_name = empty($partner['entity_family_name']) ? $partner['number_given_name'] : $partner['entity_family_name'];
        $to_email = $partner['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $system_register->site_info('SITE_EMAIL_ADD');
		
		$generic_obj = \core\app\classes\generic\generic::getInstance();
		$full_name = $generic_obj->getName('per',$ab['entity_family_name'], $ab['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

		$data = array(
			'full_name' => $full_name,
			'main_email' => $ab['main_email']
		);

		$template = $common_email->renderEmailTemplate('request_verification', $data);

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
	
	private function _sendEmailToAdmin($ab){
		//from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $system_register->site_info('SITE_EMAIL_ADD');
		
        $to_name = $from_name;
        $to_email = $from_email;

        $generic_obj = \core\app\classes\generic\generic::getInstance();
		$full_name = $generic_obj->getName('per',$ab['entity_family_name'], $ab['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

		$data = array(
			'full_name' => $full_name,
			'main_email' => $ab['main_email']
		);
		$common_email = new \core\modules\send_email\models\common\common;
		$template = $common_email->renderEmailTemplate('request_verification', $data);
		
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

	/**
     * to send verified notification email
     *
	 * @param $status
     * @param $to_name
     * @param $to_email
     * @param $from_name
     * @param $from_email
     * @param $siteURL
     */
    public function sendVerifiedNotificationEmail($status, $to_name, $to_email, $from_name, $from_email, $note)
	{
		//need a reset code
		$random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0 , 10);
		$resetCode = md5($random_string);
		
		//insert the site
		$security_db_ns = NS_MODULES.'\security\models\common\security_db';
		$security_db = new $security_db_ns;
		$security_db->setResetCode($resetCode,$to_email);
		$common_email = new \core\modules\send_email\models\common\common;
		
		$template = $common_email->renderEmailTemplate('personal_verification', array('to_name' => $to_name, 'status' => ucfirst($status), 'note' => $note));
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
		//var_dump($message);
		$generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
		
		return;
	}

	public function checkJobQualification($address_book_id)
	{
		$data = array();
		$employments = array();
		$stcw_document = $work_exp = $english_exp = $max_education = 0;

		$profile_info = $this->getProfileInfo($address_book_id);
        if($profile_info)
        {
            //check stcw
			$stcw_document = count($this->personal_db->getEducationSTCW($address_book_id));

            //check education
            $all_educations = $this->personal_db->getEducationList($address_book_id);
            $education_level = array(
                'school' => 1,
                'certificate' => 2,
                'diploma' => 3,
                'degree' => 4,
                'honours' => 5,
                'masters' => 6,
                'doctorate' => 7
            );
			
			$max_level = 0;
            foreach ($all_educations as $key => $item)
            {
                //get max education
                if ( $item['level'] != 'stcw' && ($education_level[$item['level']] >= $max_level) )
                {
                    $max_level = $education_level[$item['level']];
                }
			}
			$max_education = $max_level;

            //check experience
			$all_works = $this->personal_db->getEmploymentList($address_book_id);
			
			if (!empty($all_works))
			{
				
				foreach ($all_works as $key => $item)
				{
					//calculate total experience based on same category in each employment data
					$work_exp = $employments[$item['job_speedy_category_id']]['experience'] ?? 0;
					$d1 = new \DateTime($item['from_date']);
					if($item['active'] == 'active')
					{
						$d2 = new \DateTime();
					}else{
						$d2 = new \DateTime($item['to_date']);
					}
					$dif = $d1->diff($d2);
					$work_exp += $dif->m + ($dif->y * 12);
					$employments[$item['job_speedy_category_id']]['experience'] = $work_exp;
				}
			}

			//check english experience
			
			$all_language = $this->personal_db->getLanguage($address_book_id);
			if (!empty($all_language))
			{
				foreach ($all_language as $key => $item)
				{
					if($key == 'en')
					{
						$english_exp = $item['experience'];
						break;
					}
				}
			}

		}

		/*if (!empty($employments))
		{
			//convert total month experience to year(s)
			foreach ($employments as $key => $employment){
				$employments[$key]['experience_month'] = $employments[$key]['experience'] % 12;
				$employments[$key]['experience'] = intdiv($employments[$key]['experience'], 12);
			}
		}*/
		
		$data = array(
			'stcw_count' => $stcw_document,
			'english_exp' => $english_exp,
			'max_education' => $max_education,
			'employment' => $employments
		);
		return $data;
		
	}

}
?>