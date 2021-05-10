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
final class all extends \core\app\classes\module_base\module_ajax {

    public function run()
    {

        switch ($this->option){
            case 'array':
                break;

            default:
                $out = [];
                $principal_db = new \core\modules\principal\models\common\db();
                $out = $principal_db->getAllPrincipalDatatable();

                if ( !empty($out) )
                {
                    header('Content-Type: application/json; charset=utf-8');
                    return json_encode($out);
                }else{
                    return ;
                }
                break;
        }
    }
    
}
?>