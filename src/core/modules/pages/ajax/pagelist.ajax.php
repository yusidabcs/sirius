<?php
namespace core\modules\pages\ajax;

/**
 * Final filelist class.
 *
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class pagelist extends \core\app\classes\module_base\module_ajax {


    public function __construct()
    {

        return;
    }

    public function run()
    {
        $this->authorizeAjax('pagelist');
        $page_core_ns = NS_APP_CLASSES.'\\page_core\\page_core';
        $page_core = $page_core_ns::getInstance();

        //now get all the files for this link_id
        $data = $page_core->getAllPageCore();

        if(!empty($data))
        {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($data);
        } else {
            return ;
        }
    }

}
?>