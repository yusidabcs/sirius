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
final class listpartners extends \core\app\classes\module_base\module_ajax {

    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter

    public function run()
    {
        $this->authorizeAjax('listpartners');
        $out = null;
        $common_db = new \core\modules\partner\models\common\db;
        $out = $common_db->getListPartner();

        if(!empty($out))
        {
            return $this->response($out);
        } else {
            return ;
        }
    }

}
?>