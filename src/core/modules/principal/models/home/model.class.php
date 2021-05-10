<?php
namespace core\modules\principal\models\home;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'home';
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
        $this->view_variables_obj->setViewTemplate('home');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->addViewVariables('create_principal_link', '/'.$this->menu_register->getModuleLink('principal').'/create');

        return;
    }

}
?>