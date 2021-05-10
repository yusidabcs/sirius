<?php
namespace core\modules\personal\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class review_police extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        $data = $_POST;

        $rule = [
            'police_id' => 'required',
            'status' => 'required',
        ];
        $validator = new \core\app\classes\validator\validator($data, $rule);
        if($validator->hasErrors()){
            return $this->response($validator->getValidationErrors(),400);
        }

        $personal_db = new \core\modules\personal\models\common\db();
        $personal_db->reviewPolice($data['police_id'], $data['status']);

        return $this->response([
            'message' => 'Successfully review police with status '.$data['status']
        ]);
    }


}