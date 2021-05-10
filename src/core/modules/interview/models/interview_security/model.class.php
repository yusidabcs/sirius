<?php
namespace core\modules\interview\models\interview_security;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'interview_security';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->core_db = new \core\app\classes\core_db\core_db;
        $this->principal_db = new \core\modules\principal\models\common\db();
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        $this->countryCodes = $this->core_db->getAllCountryCodes();
        $this->principals = $this->principal_db->getPrincipalArray();
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('interview_security');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('principals',$this->principals);
        $this->view_variables_obj->addViewVariables('countryCodes',$this->countryCodes);

        return;
    }

}
?>
