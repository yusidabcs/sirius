<?php
namespace core\modules\principal\ajax;

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
        $out = [];
        if ( isset($_POST) )
        {
            $data = $_POST;
            $principal_db = new \core\modules\principal\models\common\db();
            $affected_row = $principal_db->updatePrincipalAB($data) ;
            if ($affected_row == 1)
                $out['message'] = 'Successfully update address book.';
            else
                $out['message'] = 'Problem in update address book principal';
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