<?php
namespace core\modules\recruitment\models\question;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'interview';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->core_db = new \core\app\classes\core_db\core_db;
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        $this->questions = $this->core_db->getPreIntreviewQuestion();
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('question');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSortable();
        $this->view_variables_obj->useSweetAlert();


        //POST Variable
        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);
        $this->view_variables_obj->addViewVariables('questions',$this->questions);

        return;
    }

}
?>
