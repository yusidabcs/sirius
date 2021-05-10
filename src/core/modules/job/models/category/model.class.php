<?php
namespace core\modules\job\models\category;

/**
 * Final model class.
 *
 * @final
 * @package 	job
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'category';
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
        $this->view_variables_obj->setViewTemplate('category');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useSortable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useCroppie();

        $this->view_variables_obj->addViewVariables('myURL',$this->myURL);

        return;
    }

}
?>
