<?php
namespace core\modules\recruitment\ajax;


/**
 * Class summary
 * get personal summary
 * @package core\modules\recruitment\ajax
 */
final class verificationinfo extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        $db_ns = NS_MODULES.'\\personal\\models\\common\\db';
        $db = new $db_ns();

        header('Content-Type: application/json; charset=utf-8');
        return json_encode(
            $db->getLatestVerification($this->option)
        );
    }


}
?>