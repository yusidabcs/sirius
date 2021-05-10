<?php

namespace core\modules\address_book\ajax;

/**
 * Final addmain class.
 *
 * @final
 * @extends        module_ajax
 * @package    address_book
 * @author        Martin O'Dee<martin@iow.com.au>
 * @copyright    Martin O'Deemodule_admin
 */
final class listaddressbook extends \core\app\classes\module_base\module_ajax
{

    protected $errors = array(); //an array of the errors

    protected $system_register; //we should have access to the regsiter


    public function run()
    {
        $this->authorizeAjax('listaddressbook');
        $out = null;

        $address_book_common = \core\modules\address_book\models\common\address_book_common::getInstance();

        if($this->useEntity){
            $out = $address_book_common->getListAddressBookDatatable(['ent' => $this->entity['address_book_ent_id']]);
        }else{
            $out = $address_book_common->getListAddressBookDatatable();
        }



        if (!empty($out)) {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($out);
        } else {
            return;
        }
    }

}

?>