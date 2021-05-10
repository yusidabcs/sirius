<?php
namespace core\modules\recruitment\models\common;

/**
 * Final recruitment common class.
 *
 * @final
 * @package		recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 23 Nov 2018
 */
final class common {
		
	public function __construct()
	{					
		return;
	}

    public function checkPrescreenStatus($job_application_id)
    {
        $out = [];
        $need_review = false;
        $valid = true;
        $core_db = new \core\app\classes\core_db\core_db;
        $recruitment_db = new db();
        $questions = $core_db->getPreIntreviewQuestion();
        $answers = $recruitment_db->getJobApplicationInterviewAnswer($job_application_id);

        if (empty($answers))
        {
            return;
        }

        foreach ($questions as $question)
        {
            if ($question['type'] != 'heading')
            {
                if ($question['type'] == 'tf')
                {
                    if (isset($answers[$question['question_id']]))
                    {
                        $answer_data = $answers[$question['question_id']];
                        $bool_answer = ($answer_data['answer'] == 'yes') ? 1 : 0;
                        if ($question['more'] == $bool_answer){
                            $need_review = true;
                            if (empty($answer_data['text'])){
                                $valid = false;
                            }
                        }
                    }else{
                        $valid = false;
                    }

                }elseif ($question['type'] == 'sa'){

                    if (isset($answers[$question['question_id']]))
                    {
                        if (empty($answers[$question['question_id']]['text'])){
                            $valid = false;
                        }
                    }else{
                        $valid = false;
                    }

                }
            }
        }
        //valid is whether already answered the pre screen form or not,
        //need review is whether has more option found in the answered pre screen from
        $out['valid'] = $valid;
        $out['need_review'] = $need_review;
        return $out;
    }

    public function getPrescreenInterviewData($job_application_id)
    {
        $out = array();
        $recruitment_db = new db();
        $out = $recruitment_db->getJobApplicationInterviewData($job_application_id);
        return $out;
    }
}
?>