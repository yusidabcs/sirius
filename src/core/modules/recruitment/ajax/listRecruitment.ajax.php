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
final class listRecruitment extends \core\app\classes\module_base\module_ajax {

    protected $optionRequired = true;

    public function run()
    {
        $this->authorizeAjax('listRecruitment');
        $this->address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();

        $this->address_book_db->setAddressbookJoin($this->paginateInfoArray['join']);
        $this->address_book_db->setAddressbookWhere($this->paginateInfoArray['where']);
        $this->address_book_db->setAddressbookOrderby($this->paginateInfoArray['orderby']);
        $this->address_book_db->setAddressbookOffset($this->paginateInfoArray['page_start_record']);
        $this->address_book_db->setAddressbookRowcount($this->paginateInfoArray['pagination_number']);

        return $this->address_book_db->getAddressBookArray();

    }

}
?>