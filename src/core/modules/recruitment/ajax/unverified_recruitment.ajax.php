<?php
namespace core\modules\recruitment\ajax;

/**
 * Final main class.
 *
 * @final
 * @extends		module_ajax
 * @package 	recruitment
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 23 Nov 2018
 */
final class unverified_recruitment extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        $db_ns = NS_MODULES.'\\recruitment\\models\\common\\db';
        $db = new $db_ns();
        if($this->useEntity)
            $data = $db->getUnverifiedRecruitment($this->entity['address_book_ent_id']);
        else
            $data = $db->getUnverifiedRecruitment();
        header('Content-Type: application/json; charset=utf-8');
        return json_encode(
            $data
        );
    }


}
?>