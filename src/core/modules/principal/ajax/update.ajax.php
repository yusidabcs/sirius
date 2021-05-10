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
final class update extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        $this->authorizeAjax('update');
        $out = [];
        if ( isset($_POST) )
        {
            $data = $_POST;
            $principal_db = new \core\modules\principal\models\common\db();
            $out['update'] = $principal_db->updatePrincipal($data);
            if($out){
                $out['message'] = 'Successfully update principal.';
            }
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