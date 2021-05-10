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
final class psf extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $out = null;
        $this->finance_db = new \core\modules\finance\models\common\db();
        switch($this->option)
        {
            case 'list':

                $out = $this->finance_db->getFinancePSFDatatable();

                break;

            case 'generate_invoice':

                $data = $_POST;
                $rule = [
                    'invoice_expected_on' => 'required',
                    'job_application_id' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->finance_db->updateFinancePsfGenerateInvoice($data);
                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully update finance psf tracker.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update finance psf tracker.']
                ], 400);

                break;

            case 'pay_invoice':

                $data = $_POST;
                $rule = [
                    'notes' => 'required',
                    'job_application_id' => 'required',
                    'status' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if ($validator->hasErrors()) {
                    return $this->response($validator->getValidationErrors(), 400);
                }
                $rs = $this->finance_db->updateFinancePsfPayInvoice($data);
                if ($rs > 0) {
                    if($data['status'] == 'cancelled'){
                        $job_db = new \core\modules\job\models\common\db();
                        $job_db->updateJobApplicationStatus($data['job_application_id'], 'canceled');
                    }
                    return $this->response([
                        'message' => 'Successfully update finance psf tracker.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully update finance psf tracker.']
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