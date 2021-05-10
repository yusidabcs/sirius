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
final class recruitment extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = false;

    public function run()
    {
        $this->authorizeAjax('recruitment');
        $db_ns = NS_MODULES.'\\recruitment\\models\\common\\db';
        $db = new $db_ns();

        if($this->useEntity)
            $data = $db->getAllRecruitmentOptimized($this->entity['address_book_ent_id']);
        else
            $data = $db->getAllRecruitmentOptimized();


        $generic_obj = \core\app\classes\generic\generic::getInstance();
        foreach ($data['data'] as $key => $item){
            $fullname = $generic_obj->getName('per', $item['entity_family_name'],  $item['number_given_name'] . ' ' . $item['middle_names'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            $data['data'][$key]['fullname'] = $item['title'].' '.$fullname;
        }

        //check user security level
        $level_admin = $this->system_register->getSecurityLevel('ADMIN');
        if(!( (isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $level_admin) ||
          (isset($_SESSION['user_security_level']) && $_SESSION['user_security_level'] >= $level_admin)))
        {
            //if security level not sufficient, hide change partner button
            $data['hide'] = 'hide';
        }

        return $this->response($data);
    }


}
?>