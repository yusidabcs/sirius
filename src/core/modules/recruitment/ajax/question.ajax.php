<?php
namespace core\modules\recruitment\ajax;

final class question extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('question');
        if ($this->option)
        {
            $this->core_db = new \core\app\classes\core_db\core_db();

            $type = $this->option;

            if ( $type == 'list' )
            {
                $out = [];
                $rs = $this->core_db->getPreIntreviewQuestion();
                foreach ($rs as $key => $item) {
                    if($item['parent_id'] == 0){
                        $out[$key] = $item;
                        foreach ($rs as $key2 => $item2) {
                            if($item2['parent_id'] == $item['question_id']){
                                $out[$key]['childs'][$key2] = $item2;
                            }

                        }
                    }

                }

            }
            elseif ( $type == 'get' )
            {
                $id = $this->page_options[1];
                $out = $this->core_db->getOnePreIntreviewQuestion($id);
            }
            elseif ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'question' => 'required',
                    'parent_id' => 'required',
                    'type' => 'required',
                    'relevance' => 'required',
                    'status' => 'required',
                ];
                if ($data['type'] == 'tf')
                {
                    $rule['more'] = 'required';
                }
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $affected_rows = $this->core_db->insertPreIntreviewQuestion($data);

                if($affected_rows != -1)
                {
                    $out['message'] = 'Add new pre screen question success';
                    $out['json'] = json_encode($data);
                }else{
                    $out['message'] = 'Problem in insert new pre screen question.';
                    return $this->response($out,500);
                }
            }
            elseif ( $type == 'update' )
            {
                $data = $_POST;
                $rule = [
                    'question_id' => 'required',
                    'question' => 'required',
                    'parent_id' => 'required',
                    'type' => 'required',
                    'relevance' => 'required',
                    'more' => 'required',
                    'help' => 'required',
                    'answer_heading' => 'required',
                    'status' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $affected_rows = $this->core_db->updatePreIntreviewQuestion($data);

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
                $affected_rows = $this->core_db->deletePreIntreviewQuestion($id);

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


                $affected_rows = $this->core_db->updateSequencePreIntreviewQuestion($data);

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