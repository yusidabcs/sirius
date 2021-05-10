<?php
namespace core\modules\interview\ajax;

final class interview_security extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('interview_security');
        if ($this->option)
        {

            $this->interview_db = new \core\modules\interview\models\common\db();
            $type = $this->option;

            if ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                    'principal_code' => 'required',
                    'countryCode_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $rs = $this->interview_db->insertInterviewSecurity($data);
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully insert interview security.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert interview security.']
                ],400);

            }
            else if ( $type == 'delete' )
            {
                $data = $_POST;
                $rule = [
                    'checker_id' => 'required',
                    'principal_code' => 'required',
                    'countryCode_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $rs = $this->interview_db->deleteInterviewSecurity($data);
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully delete interview security.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully delete interview security.']
                ],400);

            }

            else if ( $type == 'list' ){
                $result = $this->interview_db->getInterviewSecurityDatatable();
                return $this->response($result);

            }
        }
    }

}
?>