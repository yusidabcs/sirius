<?php
namespace core\modules\recruitment\ajax;

/**
 * Final partner class.
 * 
 * An ajax extension that allows ADMIN users to change partner of
 * any other user in recruitment.
 *
 * @final
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 10 December 2015
 */
final class partner extends \core\app\classes\module_base\module_ajax {
	
	public function run()
	{
		$this->authorizeAjax('partner');
		if ( (isset($_POST['action'])) && (isset($_POST['address_book_id'])) )
		{	
			$db_ns = NS_MODULES.'\\recruitment\\models\\common\\db';
			$rec_db = new $db_ns();
			
			$address_book_id = trim($_POST['address_book_id']);
			$action = $_POST['action'];

			if ( $action == 'delete' )
			{
				$type = $_POST['type'];
				$current_partner_id = trim($_POST['current_partner_id']);
				if($rec_db->deletePartner($address_book_id,$type))
				{
					//send email to LP, remove assigned
					$this->_sendEmailtoLP($address_book_id,$current_partner_id,'delete',$type);
					$out['good'] = true;
					$out['reply'] = 'deleted';
					$out['message'] = 'Successfully delete partner';
					if($type=='lep') {
						$out['message'] = 'Successfully delete License Education Partner';
					}
				} else {
					$out['good'] = false;
					$out['note'] = 'System error .. Partner delete failed.';

				}

			}else if ( $action == 'change' ){
				$type = $_POST['type'];
				$new_partner_id = trim($_POST['new_partner_id']);
				$current_partner_id = trim($_POST['current_partner_id']);
				if($rec_db->updatePartner($address_book_id,$new_partner_id,$type))
				{
					//send email to LP, new assigned
					$this->_sendEmailtoLP($address_book_id,$new_partner_id,'change',$type);
					if($current_partner_id!=null && $current_partner_id!='') {
						//send email to LP, remove assigned
						$this->_sendEmailtoLP($address_book_id,$current_partner_id,'delete',$type);
					}
					$out['good'] = true;
					$out['reply'] = $new_partner_id;
					$out['message'] = 'Successfully update License Partner';
					if($type=='lep') {
						$out['message'] = 'Successfully update License Education Partner';
					}
				} else {
					$out['good'] = false;
					$out['note'] = 'System error .. Partner update failed.';
				}

			}else if ( $action == 'get' ){

				$data_lp = $rec_db->getListPartner();
				$data_lep = $rec_db->getListPartner('lep');
				if($data_lp && $data_lep)
				{
					$data['data_lp']=$data_lp;
					$data['data_lep']=$data_lep;
					$out['good'] = true;
					$out['reply'] = $data;
					$out['message'] = 'Successfully fetch partner data';
				} else {
					$out['good'] = false;
					$out['note'] = 'System error .. fetch partner failed.';
				} 

			}else{
				//wrong action
				$out['good'] = false;
				$out['note'] = 'Wrong POST Action.';
			}
		}else{
			$out['good'] = false;
			$out['note'] = 'Error, action and address_book_id not set';
		}

		header('Content-Type: application/json; charset=utf-8');
		return json_encode($out);
		
	}

	private function _sendEmailtoLP($address_book_id,$new_partner_id,$action,$type) {
		$ab_db = new \core\modules\address_book\models\common\address_book_db_obj();
		$mailing_db = new \core\modules\send_email\models\common\db();
		$maililng_common = new \core\modules\send_email\models\common\common();
		

		$ab_candidate = $ab_db->getAddressBookMainDetails($address_book_id);
		$ab_partner = $ab_db->getAddressBookMainDetails($new_partner_id);

		$to_name = empty($ab_partner['entity_family_name']) ? $ab_partner['number_given_name'] : $ab_partner['entity_family_name'].' '.$ab_partner['number_given_name'];
        $to_email = $ab_partner['main_email'];

        //from the system info
        $system_register = \core\app\classes\system_register\system_register::getInstance();
        $from_name = $system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $system_register->site_info('SITE_EMAIL_ADD');
		
		$template = $maililng_common->renderEmailTemplate($type.'_assignment', [
			'entity_family_name' => $ab_candidate['entity_family_name'],
			'number_given_name' => $ab_candidate['number_given_name'],
			'main_email' => $ab_candidate['main_email']
		]);

		if($action=="change") {
			//subject
			if ($template) {
				$subject = $template['subject'];
			} else {
				$subject = 'License Partner Assignment - '.SITE_WWW;
			}

			//message
			$message = $template['html'];
			
		} else {
			$template = $maililng_common->renderEmailTemplate($type.'_removed', [
				'entity_family_name' => $ab_candidate['entity_family_name'],
				'number_given_name' => $ab_candidate['number_given_name'],
				'main_email' => $ab_candidate['main_email']
			]);
			//subject
			if ($template) {
				$subject = $template['subject'];
			} else {
				$subject = 'Removed License Partner - '.SITE_WWW;
			}

			//message
			$message = $template['html'];

			//message
			
		}

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