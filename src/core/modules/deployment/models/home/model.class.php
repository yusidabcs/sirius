<?php
namespace core\modules\deployment\models\home;

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
        $this->status = ['pending', 'processing', 'cancelled','deployed'];
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
        $this->view_variables_obj->useFlatpickr();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useCroppie();

        $this->view_variables_obj->addViewVariables('post',$this->myURL);
        $this->view_variables_obj->addViewVariables('status',$this->status);

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
		}

        return;
    }

}
?>