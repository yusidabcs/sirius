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
final class pagedetail extends \core\app\classes\module_base\module_ajax {


    public function run()
    {

        $this->authorizeAjax('pagedetail');
        $page_core_ns = NS_APP_CLASSES.'\\page_core\\page_core';
        $page_core = $page_core_ns::getInstance();

        $pages_common_ns = NS_MODULES.'\\pages\\models\\common\\common';
		$pages_common = new $pages_common_ns();

        //now get all the files for this link_id
        $data = $page_core->getPageCoreInfo($this->option);
        $pageContent = $pages_common->getPageContentAjax($this->option, 0);

        
        if(!empty($data))
        {
            $out['data'] = $data;
            $out['data']['content'] = $pageContent;

            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(404);
            
            return json_encode([
                'status' => 'Not found',
                'message' => 'Page not found!!'
            ]);
        }
    }

}
?>