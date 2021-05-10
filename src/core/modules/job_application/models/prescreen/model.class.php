<?php
namespace core\modules\job_application\models\prescreen;

use DateTime;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'prescreen';
	protected $processPost = false;
	
	public function __construct()
	{
		parent::__construct();

        $this->recruitment_db = new \core\modules\recruitment\models\common\db();
        $this->job_db = new \core\modules\job\models\common\db();
        $this->generic = \core\app\classes\generic\generic::getInstance();
        $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		return;
	}
	
	//required function
	protected function main()
	{
        $this->authorize();
        $this->_checkHash();
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('prescreen');
		return;
	}
	
	//required function
	protected function setViewVariables()
    {
        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);
        $this->view_variables_obj->addViewVariables('status',$this->status);
        
		return;
	}

    private function _checkHash()
    {
        if(isset($this->page_options[0]))
        {
            if(isset($this->page_options[1]))
            {
                $this->status = $this->page_options[1];
            } else {
                $msg = "No action specified! How did that happen?";
                throw new \RuntimeException($msg);
            }

            if(!in_array($this->status, ['accepted','revision']))
            {
                $msg = 'Wrong prescreen status';
                throw new \RuntimeException($msg);
            }
            $hash = $this->page_options[0];
        }else{
            $msg = "No email hash specified! How did that happen?";
            throw new \RuntimeException($msg);
        }

        //check and get data by hash
        $this->job_prescreen = $this->recruitment_db->getJobPrescreenByHash($hash);

        if ($this->job_prescreen == null)
        {
            $msg = 'No data found with hash '.$hash;
            throw new \RuntimeException($msg);
        }
        //check if not already confirmed
        if ($this->job_prescreen['status'] == 'accepted' || $this->job_prescreen['status'] == 'revision') {

            $msg = 'Hash no longer valid '.$hash;
            throw new \RuntimeException($msg);

        }

        if($this->status == 'accepted') {
            $this->_acceptPrescreen();
        }elseif($this->status == 'revision') {
            $this->_revisionPrescreen();
        }

        //send email to lp
        $job_application = $this->job_db->getJobApplication($this->job_prescreen['job_application_id']);
        $address_book_id = $job_application['address_book_id'];

        //get address book lp
        $user_db = new \core\modules\user\models\common\user_db;
        $this->user_info = $user_db->selectUserDetails($this->job_prescreen['created_by']);
        $this->user_info = $this->user_info[$this->job_prescreen['created_by']];
        $address_book_lp = $this->address_book_db->getPersonhAddressBookIdFromEmail($this->user_info['email']);
        // end get address book lp
            
        $prescreener_main = $this->address_book_db->getAddressBookMainDetails($address_book_lp);
        $main = $this->address_book_db->getAddressBookMainDetails($address_book_id);

        $this->_sendEmailToCandidate($this->status, $main['entity_family_name'],$main['number_given_name'],$main['main_email']);
        if($prescreener_main){
            $this->_sendEmailToLP($this->status, $prescreener_main['entity_family_name'],$prescreener_main['number_given_name'],$prescreener_main['main_email'], $main['main_email'],$main['entity_family_name'],$main['number_given_name']);
        }


    }
    private function _acceptPrescreen(){
	    //update status to accpt

        $rs  = $this->recruitment_db->acceptJobPrescreen($this->job_prescreen['job_application_id']);




    }
    private function _revisionPrescreen(){
        //update status to revision
        $this->recruitment_db->revisionJobPrescreen($this->job_prescreen['job_application_id']);

    }

    private function _sendEmailToCandidate($status,$family_name,$given_name,$main_email)
    {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;
        $this->generic = \core\app\classes\generic\generic::getInstance();

        $to_name = $this->generic->getName('per', $family_name, $given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
        $to_email = $main_email;

        //from the system info
        $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $template = $mailing_common->renderEmailTemplate('prescreen_result_candidate', [
            'to_name' => $to_name,
            'status' => $status,
            'additional' => ($status === 'revision') ? '<p>Please contact the License Partner immediately to make revision for pre-screen interview.</p>':'<p>The system keep the answers and can\'t be changed anymore. You can continue to interview stage.</p>'
        ]);
        if ($template) {
            $subject = $template['subject'];
        } else {
            $subject = 'Pre-Screen Result Confirmation : '.ucfirst($this->status).' : '.SITE_WWW;
        }

        //message
        $message = $template['html'];

        //cc
        $cc ='';

        $bcc = '';

        //html
        $html = true;
        $fullhtml = false;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $this->generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);

        return;
    }
    private function _sendEmailToLP($status,$family_name,$given_name,$main_email,$candidate_email,$candidate_family_name,$candidate_given_name)
    {
        $mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;
        $this->generic = \core\app\classes\generic\generic::getInstance();

        $to_name = $this->generic->getName('ent', $family_name, $given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);

        $to_email = $main_email;

        //from the system info
        $from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
        $from_email = $this->system_register->site_info('SITE_EMAIL_ADD');

        //subject
        $template = $mailing_common->renderEmailTemplate('prescreen_result_lp', [
            'to_name' => $to_name,
            'candidate_email' => $candidate_email,
            'candidate_name' => $this->generic->getName('per', $candidate_family_name, $candidate_given_name, ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME),
            'status' => $status,
            'additional' => ($status === 'revision') ? '<p>Please contact the candidate immediately to make revision for pre-screen interview.</p>':'<p>The system keep the answers and can\'t be changed anymore. You can continue the candidate to interview stage.</p>'
        ]);
        if ($template) {
            $subject = $template['subject'];
        } else {
            $subject = 'Pre-Screen Result Confirmation : '.ucfirst($this->status).' : '.SITE_WWW;
        }

        //message
        $message = $template['html'];

        //cc
        $cc ='';

        $bcc = '';

        //html
        $html = true;
        $fullhtml = false;

        //unsubscribe link
        $unsubscribelink = false;

        //generic for the sendmail
        $this->generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);

        return;
    }

    

}
?>