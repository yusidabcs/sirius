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
final class validatecode extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $out = [];
        if ( (isset($this->page_options[0])) && (isset($this->page_options[1])) ){
            $type = $this->page_options[0];
            $code = $this->page_options[1];
            $adress_book = (isset ($this->page_options[2]))? $this->page_options[2] : false;
            $principal_db = new \core\modules\principal\models\common\db();

            if($type == 'principal')
            {
                $out['duplicate'] = $principal_db->checkPrincipalCode($code, $adress_book);
            }elseif($type == 'brand'){
                $out['duplicate'] = $principal_db->checkBrandCode($code);
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