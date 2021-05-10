<?php
namespace core\modules\job_application\models\common;

use createPDF;
use PDF_HTML;
use verification;

/**
 * Final job_application common class.
 *
 * @final
 * @package 	job_application
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 6 September 2017
 */
final class common {
	public function __construct()
	{		
		return;
	}
	
	/**
     * to send premium service email
     *
	 * @param $address_book_id
     * @param $hash
	 * @param $type ('offer','thankyou','late?') 
	 * @param $rate ('early','late') 
     */
	public function sendPremiumServiceEmail($address_book_id, $hash = false, $type = 'offer', $rate = 'early', $psf_file = '')
    {
		$ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        $partner_db = new \core\modules\partner\models\common\db;
		$person = $ab_db->getAddressBookMainDetails($address_book_id);
		$ab_connection = $ab_db->getAddressBookConnection($address_book_id,'lp');

		$mailing_common = new \core\modules\send_email\models\common\common;
		$mailing_db = new \core\modules\send_email\models\common\db;

        $img = '';
		if($ab_connection){
            $partner_file = $partner_db->getPartnerFile($ab_connection['connection_id']);
            if($partner_file){
                 $img = '<img class="" src="'.HTTP_TYPE.SITE_WWW.'/ao/show/'.$partner_file['filename'].'" alt="partner banner">';
            }
        }


		$family_name = $person['entity_family_name'];
		$given_name = $person['number_given_name'];
		$main_email = $person['main_email'];

        $menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
		$menu_register = $menu_register_ns::getInstance();

        $to_name = empty($family_name) ? $given_name : $given_name.' '.$family_name;
        $to_email = $main_email;

		//from the system info
		
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$system_register = $system_register_ns::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $system_register->site_info('SITE_EMAIL_ADD');
		
        //message
		$content  = $img;
		$content  .= '<p>Hello Mr/Ms. '.$to_name.'</p>';

		if ($type == 'verification' || $type == 'early')
		{
			$link_id = $menu_register->getModuleLink('job_application').'/premium';
			$content .= '<p>Thank you for your interest in one of our job</p>';
			$content .= '<p>We want to offer you premium services with '.$rate.' rate</p><br/><br/>';

			$content .= ' <p>Please click link here to accept the premium service</p>';
			$content .= '<p><a href="'.HTTP_TYPE.SITE_WWW.'/'.$link_id .'/accept/'.$hash.'" style="border: 1px solid #22f563;
    padding: 5px 10px;
    background: #6dff9a;
    width: 131px;">Accept</a></p>';

			$content .= ' <p>Please click link here to reject the premium service </p>';
			$content .= '<p><a href="'.HTTP_TYPE.SITE_WWW.'/'.$link_id .'/reject/'.$hash.'" style="border:1px solid #fb3;padding:5px 10px;background: #fb3;"> Reject </a></p><br>';
			$content .= '<p>Once you agree or not, we will continue your application.</p>';

			$content .= '<p>Thank you.</p>';

		}
		else if ($type == 'late')
		{
			$content .= '<p>Inform late premium service.</p>';
		}
		else if ($type == 'verified')
		{
            $content .= '<p>Thank you for your confirmation for our premium service offer.</p>';
			$content .= '<p>We will process your request and when we confirm the premium service result, we will send another email confirmation.</p>';
		}
        else if ($type == 'confirmed')
        {
            $content .= '<p>Your premium service have been confirmed.</p>';
			$content .= '<p>We will send the premium service aggrement in this email.</p>';
        }
		$cc ='';

		$template = $mailing_common->renderEmailTemplate('premium_service', [
			'content' => $content
		]);

		//subject
		$subject = $template['subject'];
		
		$message = $template['html'];

        //bcc
        if(SYSADMIN_BCC_NEW_USERS)
        {
            $bcc = SYSADMIN_EMAIL;
        } else {
            $bcc = '';
        }$bcc='';$cc='';
        //html
        $html = true;
        $fullhtml = false;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
		$generic = \core\app\classes\generic\generic::getInstance();
        $generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink,$psf_file);

        return;
	}

	/**
     * to send premium service notification email to partner
     *
	 * @param $address_book_id
     * @param $status ('pending','accepted')
     */
	public function sendEmailtoLP($address_book_id,$status)
	{
		//first get the partner name and email from user
        $personal_db = new \core\modules\personal\models\common\db;
		$mailing_common = new \core\modules\send_email\models\common\common;
		$mailing_db = new \core\modules\send_email\models\common\db;
		$partner = $personal_db->getLocalPartnerDataByAddressBookId($address_book_id);

		//check if there is no partner, then no need to go further
		if (!isset($partner['email']) || $partner['email'] == '' )
			return;
			
		$to_name = $partner['entity_family_name'];
		$to_email = $partner['email'];

		$candidate_name = empty($partner['user_family_name'])? $partner['user_given_name'] : $partner['user_given_name'].' '.$partner['user_family_name'];
		$candidate_email = $partner['user_email'];
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$system_register = $system_register_ns::getInstance();
		$from_name = $system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $system_register->site_info('SITE_EMAIL_ADD');

		$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
		$menu_register = $menu_register_ns::getInstance();
		$link = HTTP_TYPE.SITE_WWW.'/'.$menu_register->getModuleLink('recruitment').'/applicant/';
		
		//subject
		$template = $mailing_common->renderEmailTemplate('premium_service_confirmation', [
			'candidate_name' => $candidate_name,
			'candidate_email' => $candidate_email,
			'status' => $status,
			'link' => $link
		]);

		if ($template) {
			$subject = $template['subject'];
		} else {
			$subject = 'Premium Service already confirmed by  '.$candidate_name.'';
		}

		//message
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
		$bcc='';$cc='';
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
     * Generate Premium Service
     *
	 * @param $address_book_id
	 * @param $view ('true','false')
     */
	public function generatePSF($address_book_id,$view = false)
	{
		$job_db = new  \core\modules\job\models\common\db();
		$premium = $job_db->getJobPremiumServiceByABId($address_book_id);
		
		// var_dump($premium);
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

		//check passport or idcard
		if (isset($premium['passport_id']))
		{
			$no = $premium['passport_id'];
			$doi = date( 'd / m / Y', strtotime($premium['passport_doi']));
			$poi = $premium['passport_poi'];
		}else{
			if (isset($premium['idcard_safe']))
			{		
				$no = $premium['idcard_safe'];
				$doi = date( 'd / m / Y', strtotime($premium['idcard_doi']));
				$poi = $premium['idcard_poi'];
			}
		}
		$accepted_on = date( 'd / m / Y', strtotime($premium['confirmed_on']));;
		$html = '';
		if ($premium['verified'] == 'accepted')
		{
			$html .= '
			<h4 align="center">STATEMENT TO ACCEPT THE</h4><h3 align="center"><b>PREMIUM SERVICE</b></h3>
			<h4 align="center">OFFERED BY SPEEDY GLOBAL</h4>
			<br2>
			<b>To: Speedy Global</b>
			<br2>
			<br2>
			Candidate\'s Full Name:  <d align="data">'.$premium['number_given_name'].' '.$premium['entity_family_name'].'</d><br2>
			ID Card No./Passport No.: <d align="data"> '.$no.' </d><br2>
			Date of issue: <d align="fit"> '.$doi.' </d><br2>
			Place of issue: <d align="data"> '.$poi.' </d><br2>
			Residential address: <d align="data"> '.$premium['address'].' </d><br2>
			<d align="data"></d><br2>
			Phone No: <d align="data">'.$premium['phone_number'].'</d><br2><br>
			I have read the Speedy global Services Document as found on the Speedy Global website:<br>
			<p align="center"><a href="http://speedy.global/mlc-compliant-manning-agent">http://speedy.global/mlc-compliant-manning-agent </a></p>
			A Speedy Global consultant has also given me a full explanation of the Normal Services and the<br2>
			Professional Services as offered by Speedy Global. I understand that all Normal Services are<br2>
			free of charge and that the Professional Services are optional at my discretion. I understand <br2>
			that my job offer, with the Principal, is secure even if I do not take a Professional Service. 
			<br><br><d align="right">(Please cross out as applicable)</d><br>
			
			'.(($premium['type'] == 'early')? 'V': '-').'  I would like to take the Premium Service with the Early Bird discount for 650 USD
			<br2>
			
			'.(($premium['type'] == 'late') ? 'V': '-').'  I would like to take the Premium Service at the full rate of 1,250 USD
			<br>
			<br>
			I understand that accepting the Premium Service will increase my chances of successfully<br2>
			completing all of my responsibilities promptly but that it will not guarantee me success with any<br2>
			or all of the processes. I understand I will receive a company receipt from Speedy Global for all<br2>
			payments I make to Speedy Global including payment for this Premium Service.';	

			$html2 = '
			I understand Speedy Global will be supporting me with <b>all the paperwork and procedures</b> that<br2>
			I am required to do to be successfully deployed to my Principal\'s job placement. Further,<br2>
			Speedy Global will, to the best of its capacity, ensure <b>that all paperwork is correct, that I am<br2>
			booked into appointments on time, that I am adequately prepared for any training courses<br2>
			and interviews and that I complete each stage within the Principals required time frame.</b><br2>
			I understand that there is a <b>refund policy</b> in the event that I am not successful with one of the<br2>
			stages of the process and I cannot be deployed to the Principal. The refund policy is:<br2><br>
			
			-   75% refund if I fail the Medical<br2>
			
			-   60% refund if I fail the Police Check<br2>
			
			-   35% refund if I fail the Visa<br2>
			
			-   Nil after my Visa is issued<br2><br2><br>
			I understand that I am responsible for all of the outlays for my process, which may include things<br2>
			like: police check, medial costs, visa costs, flights, seaman documents and seaman training<br2>
			expenses. If Speedy Global pays an outlay on my behalf then I may be charge by Speedy<br2>
			Global an additional processing fee of up to 12% of the outlay.<br><br>
			I understand that as a result of my decision Speedy Global will support me and consult to me<br2>
			with regard to all of <b><i>my requirements</i></b> to meet the Principals processing needs. I make this<br2>
			statement of my own free will and after careful consideration of all of the information as<br2>
			provided to me by Speedy Global..<br2><br2>
			<br2><br2>
				Mr/Mrs. '.$premium['number_given_name'].' <d align="right_signature">'.$accepted_on.'</d>
			<d align="left_signature">(Signature, full name)</d>
			<d align="right_signature">(Date)</d>
			<br2>
			<br2>
				<b>SPEEDY GLOBAL Acknowledgement</b>
			<br2>
			<br2><br2><br2><d align="right_signature">'.$accepted_on.'</d>
			<d align="left_signature">(Signature, full name)</d>
			<d align="right_signature">(Date)</d>';
		}
		elseif ($premium['verified'] == 'rejected')
		{
			$html .= '<h4 align="center">STATEMENT NOT TO ACCEPT THE</h4>
			<h3 align="center"><b>PREMIUM SERVICE '.(($premium['type'] == 'early')? 'EARLY BIRD' : 'FULL RATE').'</b></h3>
			<h4 align="center">OFFERED BY SPEEDY GLOBAL</h4>
			<br2>
			<b>To: Speedy Global </b>
			<br2>
			<br2>
			Candidate\'s Full Name:  <d align="data">'.$premium['number_given_name'].' '.$premium['entity_family_name'].'</d><br2>
			ID Card No./Passport No.: <d align="data"> '.$no.' </d><br2>
			Date of issue: <d align="fit"> '.$doi.' </d><br2>
			Place of issue: <d align="data"> '.$poi.' </d><br2>
			Residential address: <d align="data"> '.$premium['address'].' </d><br2>
			<d align="data"></d><br2>
			Phone No: <d align="data">'.$premium['phone_number'].'</d><br2><br>
			I have read the Speedy global Services Document as found on the Speedy Global website:<br>
			<p align="center"><a href="http://speedy.global/mlc-compliant-manning-agent">http://speedy.global/mlc-compliant-manning-agent </a></p>
			A Speedy Global consultant has also given me a full explanation of the Normal Services and the<br2>
			Professional Services as offered by Speedy Global. I understand that all Normal Services are<br2>
			free of charge and that the Professional Services are optional.<br><br>

			After careful consideration I <b>do not</b> wish to take the Premium Service, at this time, and take<br2>
			advantage of the Early Bird rate I was offered. I will take full responsibility for the progress <br2>
			myself and I am prepared to accept the consequences of this, whatever they are.<br><br>

			<i><b>I will not hold Speedy Global liable in any way shape or form in the event that I do not do <br2>
			a process correctly or I do not complete a process within the Principals required time<br2>
			frame. I accept that in the worse case either of these things could result in the<br2>
			cancellation of my job offer with the Principal.</b></i>';	

			$html2 = '
			Speedy Global is not responsible for supporting me with any paperwork or any procedures<br2>
			except with the documents and information, as provided by the Principal, to support my<br2>
			employment andprocesses required of Speedy Global by the Principal.<br><br>

			I understand that some of my requirements include but are not limited to:<br><br>
			<t>[V]  <b>Police check;</b> including documents translation and notarization<br2>
			<t>[V]  <b>Medical check(s)</b> and vaccinations in accordance with the standards required by the<br2>
			<t2>Principal; including airfare, accommodation, health care fee, transportation and<br2>
			<t2>acquisition of the results.<br2>
			<t>[V]  <b>Seafarer\'s documents and STCW courses;</b> including scheduling classes with a<br2>
			<t2>school approved by the government, completing the necessary procedures to<br2>
			<t2>receive documents and deliver of the documents to candidates.<br2>
			<t>[V]  <b>All Visa requirements</b> including:<br2>
			<t2>[V]   Filling in online/ paper application,<br2>
			<t2>[V]   Making appointment(s) for the interview and<br2>
			<t2>[V]   Preparing all information and documents to support my application.<br2>

			<d align="right">(Please tick all of the above)</d><br>
			I understand that as a result of my decision Speedy Global will not support me or consult to me<br2>
			with regard to <b><i>my requirements</i></b> unless I am willing pay for the full Premium Service at a future<br2>
			time. I make this statement of my own free will and after careful consideration of all of the<br2>
			information as provided to me by Speedy Global.<br2><br2>
			<br2><br2>
				Mr/Mrs. '.$premium['number_given_name'].' <d align="right_signature">'.$accepted_on.'</d>
			<d align="left_signature">(Signature, full name)</d>
			<d align="right_signature">(Date)</d>
			<br2><br2>
			<br2>
				<b>SPEEDY GLOBAL Acknowledgement:</b>
			<br2>
			<br2><br2><br2><d align="right_signature">'.$accepted_on.'</d>
			<d align="left_signature">(Signature, full name)</d>
			<d align="right_signature">(Date)</d>
			';
		}	

		require (DIR_LIB.'/fpdf/html2pdf.php');
		$pdf = new PDF_HTML('');
		$pdf->SetMargins(10, 25 ,10);
		$pdf->AliasNbPages();
		$pdf->SetFont('Arial','',16);
		$pdf->AddPage();
		$pdf->WriteHTML($html);
		$pdf->AddPage();
		$pdf->WriteHTML($html2);
		if ($view){
			$pdf->Output();
			exit();
		}

		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

		//save the file
		$directory = $this->_checkAddressBookFileDirectory($address_book_id);
		$filename = $address_book_db->uniqueAddressBookFileName();
		$dst_file = $directory.'/'.$filename;
		$pdf->Output('F',$dst_file);

		$model_code = 'employment';
		$model_sub_code = 'premium';

		$file_premium =  $address_book_db->getAddressBookFileArray($address_book_id,$model_code,$model_sub_code);
		$file_current = !empty($file_premium)? $file_premium[0]['filename'] : '';

        if($file_current)
        {
			
			$address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();
            //delete the current premium service image
            $address_book_common->deleteAddressBookFile($file_current,$address_book_id);

            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->updateAddressBookFile($filename,$address_book_id,$model_code,0,$model_sub_code,1);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with process premium service form for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        } else {
            //insert also saves the image in the address book folder
            $affected_rows = $address_book_db->insertAddressBookFile($filename,$address_book_id,$model_code,0,$model_sub_code,1);

            if($affected_rows != 1)
            {
                $msg = "There was a major issue with process premium service form for address id {$address_book_id}. Affected was {$affected_rows}";
                throw new \RuntimeException($msg);
            }

        }
		//update job_premium_service filename
		$data = array(
			'filename' => $filename,
			'address_book_id' => $address_book_id
		);

		$job_db = new  \core\modules\job\models\common\db();
		$job_db->updateJobPremiumServiceFile($data);

		return $dst_file;
	}

	private function _checkAddressBookFileDirectory($address_book_id)
	{
		if( empty($address_book_id) || $address_book_id < 1 )
		{
			$msg = "Bad Address Book Id! You need an address book id not ({$address_book_id})!";
			throw new \RuntimeException($msg);
		}
		
		//check the directory
		//make a folder if one is not there already for this address book 
		$directory = DIR_LOCAL_UPLOADS.'/address_book/'.$address_book_id;
		
		if(!is_dir($directory))
		{
			if(!@mkdir($directory,0770,true))
			{
				$msg = "The address_book {$address_book_id} directory could not be set up!";
				throw new \RuntimeException($msg);
			}
		}
		
		return $directory;
	}

}
?>