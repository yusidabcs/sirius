<?php
namespace core\modules\principal\models\create;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'create';
    protected $processPost = true;


    public function __construct()
    {
        parent::__construct();
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
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
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->addViewVariables('principal_link', '/'.$this->menu_register->getModuleLink('principal'));
        return;
    }

}
?>