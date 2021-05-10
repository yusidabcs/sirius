<?php
namespace core\modules\recruitment\models\prescreen;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'prescreen';
    protected $processPost = false;

    public function __construct()
    {

        $this->core_db = new \core\app\classes\core_db\core_db;
        $this->principal_db = new \core\modules\principal\models\common\db();
        parent::__construct();
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        //get the countryCodes
        $this->countryCodes = $this->core_db->getAllCountryCodes();
        $this->principals = $this->principal_db->getPrincipalArray();

        if(isset($_SESSION['entity'])){
            $this->partners = [];
        }else{
            $recruitment_db = new \core\modules\recruitment\models\common\db;
            $this->partners = $recruitment_db->getListPartner();
        }

        $this->list_status = ['pending','sending','accepted','revision'];
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('prescreen');
        return;
    }

    //required function
    protected function setViewVariables()
    {

        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();

        $this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);
        $this->view_variables_obj->addViewVariables('principals',$this->principals);
        $this->view_variables_obj->addViewVariables('partners',$this->partners);
        $this->view_variables_obj->addViewVariables('list_status',$this->list_status);
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);

    }

}
?>
