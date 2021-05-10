<?php
namespace core\modules\principal\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 10 July 2017
 */
final class addbrand extends \core\app\classes\module_base\module_ajax {

    public function run()
    {
        $out = [];
        if ( isset($_POST) )
        {
            $data = $_POST;
            $principal_db = new \core\modules\principal\models\common\db();
            $out = $principal_db->insertPrincipalBrand($data);
            $out['message'] = 'Successfully insert new brand.';
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