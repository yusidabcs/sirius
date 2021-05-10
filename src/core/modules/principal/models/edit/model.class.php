<?php
namespace core\modules\principal\models\edit;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'edit';
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
        $this->view_variables_obj->setViewTemplate('edit');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->addViewVariables('principal_id', $this->page_options[0]);
        $this->view_variables_obj->addViewVariables('principal_link', '/'.$this->menu_register->getModuleLink('principal'));
        $this->view_variables_obj->addViewVariables('page_link', $this->modelURL);

        return;
    }

}
?>