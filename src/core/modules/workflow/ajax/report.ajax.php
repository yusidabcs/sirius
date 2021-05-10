<?php
namespace core\modules\workflow\ajax;

final class report extends \core\app\classes\module_base\module_ajax{

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('report');
        if ($this->option)
        {

            $this->report_db = new \core\modules\workflow\models\common\report_db();
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
                $rs = $this->report_db->insertInterviewSecurityReport($data);
                $this->report_db->deleteSecurityReportResponsible($data['address_book_id']);
                foreach ($data['workflow_tracker'] as $item){
                    $this->report_db->insertSecurityReportResponsible([
                        'address_book_id' => $data['address_book_id'],
                        'workflow' => $item,
                    ]);
                }
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully insert interview security.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert interview security.']
                ],400);

            }
            if ( $type == 'update' )
            {
                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                    'level' => 'required',
                    'workflow_tracker' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $rs = $this->report_db->updateInterviewSecurityReport($data);
                $this->report_db->deleteSecurityReportResponsible($data['address_book_id']);
                foreach ($data['workflow_tracker'] as $item){
                    $this->report_db->insertSecurityReportResponsible([
                        'address_book_id' => $data['address_book_id'],
                        'workflow' => $item,
                    ]);
                }
                if($rs != -1 ){
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

                $rs = $this->report_db->deleteInterviewSecurityReport($data['address_book_id']);
                $this->report_db->deleteSecurityReportResponsible($data['address_book_id']);
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
                $result = $this->report_db->getInterviewSecurityReportDatatable();
                return $this->response($result);

            }
        }
    }

}
?>