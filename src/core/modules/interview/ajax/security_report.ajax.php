<?php
namespace core\modules\interview\ajax;

final class security_report extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('security_report');
        if ($this->option)
        {

            $this->interview_db = new \core\modules\interview\models\common\db();
            $type = $this->option;

            if ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                    'level' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $rs = $this->interview_db->insertInterviewSecurityReport($data);
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
                    'address_book_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }

                $rs = $this->interview_db->deleteInterviewSecurityReport($data['address_book_id']);
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
                $result = $this->interview_db->getInterviewSecurityReportDatatable();
                return $this->response($result);

            }
        }
    }

}
?>