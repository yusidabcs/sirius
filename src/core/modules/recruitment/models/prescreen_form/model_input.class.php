<?php
namespace core\modules\recruitment\models\prescreen_form;

/**
 * Final model_input class.
 *
 * @final
 * @extends		module_model_input
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'prescreen_form';
	
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
		$data = $_POST;
        $this->interview_db = new \core\modules\interview\models\common\db();
		
		// check type, jobapplication id
		if (empty($data['job_application_id']))
			$this->addError('Job Application Id', 'Cannot be empty');
		
		if (empty($data['type']))
			$this->addError('Type', 'Cannot be empty');

		
		//check bulk data
		foreach ($data['pre_screen'] as $key => $answers)
		{
            if(isset($answers['text'])){
                $answers['text'] = trim($answers['text']);
            }else{
                $answers['text'] = '';
            }
			if (isset($answers['more']))
			{
				if (!isset($answers['answer']))
				{
					$this->addError($answers['question_text'] , 'Must select one of the options');
					$data['pre_screen'][$key]['answer'] = '';
				}else{
					//true false question
					$bool_answer = ($answers['answer'] == 'yes')? 1 : 0;
					//if need more answer // ignore if has child
					if ($answers ['more'] == $bool_answer && (isset($answers['child']) && $answers['child'] == 0))
					{
						//insert to answer text
						if (empty($answers['text']))
							$this->addError($answers['question_text'] , 'Details cannot be empty');	
					}
				}
				
			}else{
				//insert plain answer text for short answer question
				if (empty($answers['text']))
					$this->addError($answers['question_text'], 'Cannot be empty');

			}
		}

		if(count($this->errors) > 0)
        {	
			$this->addInput('answers',$data['pre_screen']);
			return;
		}

		//if everything check, insert to database
		foreach ($data['pre_screen']  as $key => $answers)
		{
			// init data default template
			$insertData = array(
				'job_application_id' => $data['job_application_id'],
				'type' =>  $data['type'],
				'question_id' => $key
			);
			if(isset($answers['text'])){
                $answers['text'] = trim($answers['text']);
            }else{
                $answers['text'] = '';
            }
			//check if has more options
			if (isset($answers['more']))
			{
				$answers['answer'] = trim($answers['answer']);
				//true false question
				$bool_answer = ($answers['answer'] == 'yes')? 1 : 0;
				$insertData['answer'] = $answers['answer'];
				$this->interview_db->insertJobApplicationInterviewAnswer($insertData, 'prescreen');	

				//if need more answer
				if ($answers ['more'] == $bool_answer)
				{
					//insert to answer text
					$insertData['text'] = $answers['text'];
					$this->interview_db->insertJobApplicationInterviewAnswerText($insertData, 'prescreen');		
				}else{
					//delete previous more answer if not needed;
					$this->interview_db->deleteJobApplicationInterviewAnswerText($key);
				}
				
			}else{
				//insert plain answer text for short answer question
				$insertData['text'] = $answers['text'];
				$this->interview_db->insertJobApplicationInterviewAnswerText($insertData, 'prescreen');	
			}
		}
		//check if there is redirect option, and check if in allowerd redirect		
		if ( isset($_POST['redirect_to'])  && in_array($_POST['redirect_to'],['rec']) )
		{
			$redirect_to = $_POST['redirect_to'];
			if ($redirect_to == 'rec')
			{
				$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
				$this->menu_register = $menu_register_ns::getInstance();
				$this->redirect = '/'.$this->menu_register->getModuleLink('recruitment').'/applicant';
			}
		}
		return;
	}
}
?>