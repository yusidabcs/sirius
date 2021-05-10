<?php
namespace core\modules\interview\ajax;

final class interviewer extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('interviewer');
        if ($this->option)
        {

            $this->interview_db = new \core\modules\interview\models\common\db();
            $type = $this->option;

            if ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $rs = $this->interview_db->insertInterviewer($data);
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully insert interviewer.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert interviewer.']
                ],400);

            }
            else if ( $type == 'delete' )
            {
                $interviewer_id = $this->page_options[1];

                $rs = $this->interview_db->deleteInterviewer($interviewer_id);
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully insert interviewer.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert interviewer.']
                ],400);

            }

            else if ( $type == 'list' ){
                $result = $this->interview_db->getListInterviewer();
                return $this->response($result);

            }
        }
    }

}
?>