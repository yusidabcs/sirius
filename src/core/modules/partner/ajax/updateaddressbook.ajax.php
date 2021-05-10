<?php
namespace core\modules\partner\ajax;

/**
 * Final updateaddressbook class.
 *
 * @final
 * @extends		module_ajax
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 10 July 2017
 */
final class updateaddressbook extends \core\app\classes\module_base\module_ajax {

    public function run()
    {
        $this->authorizeAjax();
        $out = [];
        if ( isset($_POST) )
        {
            $partner_db = new \core\modules\partner\models\common\db();
            $new_ab = $_POST['new_ab'];
            $old_ab = $_POST['old_ab'];

            $affected_row = $partner_db->updatePartnerAB($new_ab,$old_ab) ;
            if ($affected_row == 1)
                $out['message'] = 'Successfully update address book.';
            else
                $out['message'] = 'Problem in update address book partner';
        }
        
        if ( !empty($out) )
        {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        }else{
            return ;
        }
    }

}
?>