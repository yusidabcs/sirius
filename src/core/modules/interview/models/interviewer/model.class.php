<?php
namespace core\modules\interview\models\interviewer;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'interviewer';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->user_db = new \core\modules\user\models\common\user_db();
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        $this->users = $this->user_db->getNonInterviewerArray();
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('interviewer');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSortable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useDatatable();

        $this->view_variables_obj->addViewVariables('users',$this->users);

        return;
    }

}
?>
