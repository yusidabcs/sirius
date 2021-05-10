<?php

namespace core\modules\offer_letter\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends        module_ajax
 * @package    offer_letter
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 18 May 2020
 */
final class reports extends \core\app\classes\module_base\module_ajax
{

    protected $optionRequired = true;

    public function run()
    {
        $out = null;
        $offer_letter_db = new \core\modules\offer_letter\models\common\db();
        switch ($this->option) {
            case 'list':
                $out = $offer_letter_db->getReportsDatatable();
                return $this->response($out);
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
                $rs = $offer_letter_db->insertReports($data);
                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully insert offer letter report.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert offer letter report.']
                ], 400);
                break;

            case 'delete':
                if (empty($this->page_options[1])) {
                    return $this->response([
                        'errors' => ['Address book id not found.']
                    ], 400);
                }
                $address_book_id = $this->page_options[1];

                $rs = $offer_letter_db->deleteReports($address_book_id);

                if ($rs > 0) {
                    return $this->response([
                        'message' => 'Successfully delete offer letter report.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully delete offer letter report.']
                ], 400);

                break;

            default:
                throw new \Exception('Unsupported operation: ' . $this->option);
                break;
        }

    }

}

?>