<?php
namespace core\modules\cv\models\home;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'home';
    protected $processPost = true;


    public function __construct()
    {
        parent::__construct();
        
        $this->job_db = new \core\modules\job\models\common\db;
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
        $this->view_variables_obj->useSimpleXlsx();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->addViewVariables('create_job_link', '/'.$this->menu_register->getModuleLink('job').'/create');

        $this->view_variables_obj->addViewVariables('job_speedy', $this->job_db->getAllJobSpeedy());

        return;
    }

}
?>