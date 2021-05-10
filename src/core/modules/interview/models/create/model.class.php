<?php
namespace core\modules\interview\models\create;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'create';
    protected $processPost = false;

    public function __construct()
    {
        parent::__construct();
        $this->core_db = new \core\app\classes\core_db\core_db();
        $this->partner_db = new \core\modules\partner\models\common\db();
        $this->ab_db = \core\modules\address_book\models\common\address_book_db::getInstance();
        return;
    }

    //required function
    protected function main()
    {   
        $this->authorize();
        $this->show_partner=true;
        if($this->useEntity) {
            $this->show_partner = false;
        }
        $this->partners = $this->partner_db->getListPartner();
        $this->countries = $this->core_db->getAllCountryCodes();
        $this->address_books = $this->ab_db->getListAdminAddressBook();
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('create');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useFlatpickr();
        $this->view_variables_obj->useMoment();

        $this->view_variables_obj->addViewVariables('back_link',$this->baseURL.'/location');
        $this->view_variables_obj->addViewVariables('partners',$this->partners);
        $this->view_variables_obj->addViewVariables('countries',$this->countries);
        $this->view_variables_obj->addViewVariables('address_books',$this->address_books);
        $this->view_variables_obj->addViewVariables('show_partner',$this->show_partner);
        return;
    }

}
?>
