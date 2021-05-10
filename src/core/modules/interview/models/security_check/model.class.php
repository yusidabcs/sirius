<?php
namespace core\modules\interview\models\security_check;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'security_check';
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
        $this->status = ['request_file','request_clearance','accepted','denied'];
        $this->level = ['normal','soft','hard','deadline'];
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('security_check');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('status',$this->status);
        $this->view_variables_obj->addViewVariables('level',$this->level);

        return;
    }

}
?>
