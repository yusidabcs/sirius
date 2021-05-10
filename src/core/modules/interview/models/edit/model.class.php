<?php
namespace core\modules\interview\models\edit;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'edit';
    protected $processPost = false;

    public function __construct()
    {
        parent::__construct();
        $this->core_db = new \core\app\classes\core_db\core_db();
        $this->partner_db = new \core\modules\partner\models\common\db();
        $this->interview_db = new \core\modules\interview\models\common\db();
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

        if(empty($this->page_options[0])){
            die('No id set');
            exit();
        }
        $this->location = $this->interview_db->getInterviewLocationById($this->page_options[0]);
        if(!$this->location){
            die('No interview location found for id '.$this->page_options[0]);
            exit();
        }
        $this->partners = $this->partner_db->getListPartner();
        $this->countries = $this->core_db->getAllCountryCodes();
        $this->subcountries = $this->core_db->getSubCountryCodes($this->location['countryCode_id']);
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('edit');
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
        $this->view_variables_obj->addViewVariables('location',$this->location);
        $this->view_variables_obj->addViewVariables('subcountries',$this->subcountries);
        $this->view_variables_obj->addViewVariables('show_partner',$this->show_partner);

        return;
    }

}
?>
