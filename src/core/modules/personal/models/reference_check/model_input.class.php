<?php
namespace core\modules\personal\models\reference_check;

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
		$this->authorize();
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
            $this->redirect = $this->baseURL.'/submitted';
        }
		return;
		
	}

	
	private function _sendRegisterEmail($hash,$family_name,$given_name,$main_email)
	{
		$to_name = empty($family_name) ? $given_name : $given_name.' '.$family_name;
		$to_email = $main_email;
		
		//from the system info
		$from_name = $this->system_register->site_info('SITE_EMAIL_NAME');
		$from_email = $this->system_register->site_info('SITE_EMAIL_ADD');
		
		//subject
		$subject = 'Registration Submission: '.SITE_WWW;
		
		//message
		$message  = '<h1>You are nearly registered with '.$this->system_register->site_info('SITE_TITLE').'</h1>';
		$message .= '<p>If you have made a request to register then click this link within 24 hours of getting this email ';
		$message .= '<strong> '.HTTP_TYPE.SITE_WWW.$this->baseURL.'/process/'.$hash.' </strong></p>';
		$message .= '<p>Please ignore this email if you do not wish to register.</p>';
		
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
		$generic->sendEmail($to_name,$to_email,$from_name,$from_email,$subject,$message,$cc,$bcc,$html,$fullhtml,$unsubscribelink);
		
		return;
	}
		
	
}
?>