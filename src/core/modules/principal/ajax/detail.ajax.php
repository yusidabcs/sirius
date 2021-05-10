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
final class detail extends \core\app\classes\module_base\module_ajax {

    public function run()
    {
        $out = [];
        if ( isset($this->page_options[0]) )
        {
            $id = $this->page_options[0];
            $principal_db = new \core\modules\principal\models\common\db();
            $out = $principal_db->getPrincipalDetail($id);
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