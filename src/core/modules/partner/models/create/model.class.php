<?php
namespace core\modules\partner\models\create;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'create';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        return;
    }

    protected function main()
    {
        $this->authorize();
        $core_db = new \core\app\classes\core_db\core_db;
        $this->countries = $core_db->getAllCountryCodes();
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
        $this->view_variables_obj->useCroppie();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->addViewVariables('countries', $this->countries);
        $this->view_variables_obj->addViewVariables('back_link', '/'.$this->menu_register->getModuleLink('partner'));
        //POST Variable
		$this->view_variables_obj->addViewVariables('myURL',$this->myURL);

        if($this->input_obj)
        {
            if($this->input_obj->hasErrors())
            {
                $this->view_variables_obj->addViewVariables('errors',$this->input_obj->getErrors());
            }

            if($this->input_obj->hasInputs())
            {
                $array = $this->input_obj->getInputs();
                foreach($array as $key => $value)
                {
                    $this->view_variables_obj->addViewVariables($key,$value);
                }
            }
            if($this->input_obj->hasMessages())
            {
                $this->view_variables_obj->addViewVariables('messages',$this->input_obj->getMessages());
            }
        }
        return;
    }
    
}
?>