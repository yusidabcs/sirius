<?php
namespace core\modules\finance\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	finance
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 15 Jun 2020
 */
final class reports extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('reports');
        $out = null;
        $this->finance_db = new \core\modules\finance\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->finance_db->getReportsDatatable();

                break;

            case 'insert':

                $data = $_POST;
                $rule = [
                    'address_book_id' => 'required',
                    'level' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->finance_db->insertReports($data);
                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully insert finance report.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert finance report.']
                ], 400);

                break;

            case 'delete':
                if (empty($this->page_options[1])) {
                    return $this->response([
                        'errors' => ['Address book id not found.']
                    ], 400);
                }
                $address_book_id = $this->page_options[1];

                $rs = $this->finance_db->deleteReports($address_book_id);

                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully delete finance report.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully delete finance report.']
                ], 400);

                break;

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);
                break;
        }

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

}
?>