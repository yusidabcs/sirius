<?php
namespace core\modules\partner\ajax;

/**
 * Final countrysubcodes class.
 *
 * @final
 * @package 	partner
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee module_admin
 */
final class enable extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true; //we must have an option to work

    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter

    public function run()
    {
        $this->authorizeAjax();
        $out = null;

        if($this->option){
            $common_db = new \core\modules\partner\models\common\db;
            $rs = $common_db->editPartnerStatus([
                'status' => 1,
                'address_book_id' => $this->option
            ]);
            if($rs){
                $out['message'] = 'Successfully enable partner';
            }
        }



        if(!empty($out))
        {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        } else {
            return ;
        }
    }

}
?>