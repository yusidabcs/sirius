<?php
namespace core\modules\reference_check\models\process;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 January 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'process';
	
	//my variables
	protected $redirect;
	protected $nextModel;

	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		$workflow_db = new \core\modules\workflow\models\common\db();

        if (isset($this->page_options[0])) {
            $hash = $this->page_options[0];
            $personal_db = new \core\modules\personal\models\common\db();
            $this->reference_check = $personal_db->getReferenceCheckByHash($hash);
            if (empty($this->reference_check)) {
                // no partner with the partner code embedded
                $htmlpage_ns = NS_HTML . '\\htmlpage';
                $htmlpage = new $htmlpage_ns(404);
                exit();
            }
            $this->questions = $personal_db->getReferenceQuestions($this->reference_check['question_type']);
        }
        $data = $_POST;

        foreach ($this->questions as $q){
            if(!array_key_exists($q['question_id'], $data['answer'])){
                $this->addError('Question no ','Answer for the question is required.');
            }
		}
		$reference = $personal_db->getReference($this->reference_check['reference_id']);
		
		if ($this->reference_check['question_type'] === 'work') {
			$tablename = 'workflow_profesional_reference_tracker';
		} else {
			$tablename = 'workflow_personal_reference_tracker';
		}

		if ($workflow_db->getActiveWorkflow($tablename, 'reference_check_id', $this->reference_check['id'])) {
			# code...
			$tracker = $workflow_db->updateReferenceTracker($tablename, $this->reference_check['id'], [
				'completed_on' => date('Y-m-d H:i:s'),
				'notes' => 'reference answers was subbmited',
				'level' => 1,
				'status' => 'review'
			]);
		}

		
		if(!$this->hasErrors())
        {
            foreach ($this->questions as $q){
                if(array_key_exists($q['question_id'], $data['answer'])){
                    $personal_db->insertReferenceCheckAnswer($q['answer_type'],[
                        'reference_check_id' => $this->reference_check['id'],
                        'question_id' => $q['question_id'],
                        'answer' => $data['answer'][$q['question_id']],
                    ]);
                }
			}

            $personal_db->completedReferenceCheck($this->reference_check['id']);
			//send email to LP
			$candidate_name = $data['candidate_name'];
			$partner = $personal_db->getLocalPartnerDataByReferenceId($this->reference_check['reference_id']);
			$this->_sendEmailtoLP($partner,$candidate_name,$this->reference_check['reference_id']);
            $this->redirect = $this->baseURL.'/submitted';
        }
		return;
		
	}

	
	private function _sendEmailtoLP($partner,$candidate_name,$reference_id)
	{
		$to_name = $partner['entity_family_name'];
		$to_email = $partner['email'];

		$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
		$menu_register = $menu_register_ns::getInstance();
		$link = HTTP_TYPE.SITE_WWW.'/'.$menu_register->getModuleLink('personal').'/reference_check/'.$reference_id;
		
		//from the system info
		$from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
		$reply_to = $this->system_register->system_info('REFERENCE_REPLY_TO');

		$mailing_common = new \core\modules\send_email\models\common\common;
        $mailing_db = new \core\modules\send_email\models\common\db;
		
		//subject
		$template = $mailing_common->renderEmailTemplate('reference_check_submit', [
			'candidate_name' => $candidate_name,
			'link' => $link,
		]);
		
		if ($template) {
			$subject = $template['subject'];
		} else {
			$subject = 'Email reference for '.$candidate_name.'';
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
		
		//html
		$html = true;
		$fullhtml = false;
		
		//unsubscribe link
		$unsubscribelink = false;
				
		//generic for the sendmail
		$generic = \core\app\classes\generic\generic::getInstance();
		$generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink,'','',$reply_to);
		
		return;
	}
		
	
}
?>