<?php
namespace core\modules\interview\ajax;

final class question extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('question');
        if ($this->option)
        {
            $this->interview_db = new \core\modules\interview\models\common\db();

            $type = $this->option;

            if ( $type == 'list' )
            {
                $rs = $this->interview_db->getIntreviewQuestionDatatable();
                return $this->response($rs);

            }
            elseif ( $type == 'others' )
            {
                $rs = $this->interview_db->getOthersIntreviewQuestion();
                return $this->response($rs);

            }
            elseif ( $type == 'get' )
            {
                $id = $this->page_options[1];
                $out = $this->interview_db->getOneIntreviewQuestion($id);
                if($out['type'] == 'specific'){
                    $out['question_job'] = $this->interview_db->getQuestionJobs($out['question_id']);
                }
            }
            elseif ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'question' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                ];
                if ($data['type'] == 'specific')
                {
                    $rule['job_speedy_code'] = 'required|min:1';
                }
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $question_id = $this->interview_db->insertIntreviewQuestion($data);
                if ($data['type'] == 'specific')
                {
                    $this->interview_db->deleteQuestionJob($question_id);
                    foreach ($data['job_speedy_code'] as $job_code){
                        $this->interview_db->insertQuestionJob([
                            'question_id' => $question_id,
                            'job_speedy_code' => $job_code,
                        ]);
                    }
                }

                if($question_id > 0)
                {
                    $out['message'] = 'Successfully insert question';
                    $out['json'] = json_encode($data);
                }else{
                    $out['message'] = 'Unsuccessfully insert question';
                    return $this->response($out,500);
                }
            }
            elseif ( $type == 'update' )
            {
                $data = $_POST;
                $rule = [
                    'question' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                ];
                if ($data['type'] == 'specific')
                {
                    $rule['job_speedy_code'] = 'required|min:1';
                }
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $affected_rows = $this->interview_db->updateIntreviewQuestion($data);
                if ($data['type'] == 'specific')
                {
                    $this->interview_db->deleteQuestionJob($data['question_id']);
                    foreach ($data['job_speedy_code'] as $job_code){
                        $this->interview_db->insertQuestionJob([
                            'question_id' => $data['question_id'],
                            'job_speedy_code' => $job_code,
                        ]);
                    }
                }
                if($affected_rows != -1)
                {
                    $out['message'] = 'Successfully update data';
                }else{
                    $out['message'] = 'Something wrong, please try again later. ';
                    return $this->response($out,500);
                }

            }
            elseif ( $type == 'delete' )
            {
                $id = $this->page_options[1];
                $affected_rows = $this->interview_db->deleteIntreviewQuestion($id);

                if($affected_rows)
                {
                    $out['response'] = 'Success';
                    $out['message'] = 'Success deleting data ';
                }else{
                    $out['message'] = 'Problem in delete question.';
                    return $this->response($out,500);
                }
            }

            elseif ($type = 'updateSequence') {
                $data = $_POST;
                $rule = [
                    'id' => 'required|int',
                    'parent_id' => 'required',
                    'index' => 'required',
                ];

                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response(400,$validator->getValidationErrors());
                }


                $affected_rows = $this->interview_db->updateSequencePreIntreviewQuestion($data);

                if($affected_rows != -1)
                {
                    $out['message'] = 'Update sequence success';
                }else{
                    $out['message'] = 'Problem in update sequence.';
                    return $this->response($out, 500);
                }
            }

            return $this->response($out);
        }
    }

}
?>