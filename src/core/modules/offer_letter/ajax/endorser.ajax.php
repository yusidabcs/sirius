<?php
namespace core\modules\offer_letter\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	offer_letter
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 18 May 2020
 */
final class endorser extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $out = null;

        if ($this->option)
        {

            $this->offer_letter_db = new \core\modules\offer_letter\models\common\db();
            $type = $this->option;

            if ( $type == 'insert' )
            {
                $data = $_POST;
                $rule = [
                    'job_master_id' => 'required',
                    'endorser_id' => 'required',
                    'allowance_days' => 'required',
                ];
                $validator = new \core\app\classes\validator\validator($data, $rule);

                if($validator->hasErrors())
                {
                    return $this->response($validator->getValidationErrors(),400);
                }
                $rs = $this->offer_letter_db->insertEndorser($data);
                if($rs > 0){
                    return $this->response([
                        'message' => 'Successfully insert offer letter endorser.'
                    ]);
                }
                return $this->response([
                    'errors' => ['Unsuccessfully insert offer letter endorser.']
                ],400);

            }
            else if ( $type == 'delete' )
            {
                if(empty($this->page_options[1])){
                    return $this->response([
                        'errors' => ['Job master id not found.']
                    ],400);
                }
                $job_master_id = $this->page_options[1];

                $rs = $this->offer_letter_db->deleteEndorser($job_master_id);

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
                $result = $this->offer_letter_db->getEndorserDatatable();
                return $this->response($result);

            }
        }

    }

}
?>