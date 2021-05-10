<?php
namespace core\modules\partner\models\home;

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
        $this->view_variables_obj->useEkkoLightBox();
        $this->view_variables_obj->addViewVariables('link_create', $this->link_id.'/create');
        $this->view_variables_obj->addViewVariables('link_edit', $this->link_id.'/edit');
        $this->view_variables_obj->addViewVariables('link_delete', $this->link_id.'/edit/delete');
        $this->view_variables_obj->addViewVariables('link_disable', $this->link_id.'/edit/disable');
        $this->view_variables_obj->addViewVariables('link_enable', $this->link_id.'/edit/enable');
        $this->view_variables_obj->addViewVariables('register_link', $this->menu_register->getModuleLink('register'));
        return;
    }

}
?>