<?php

namespace core\modules\job_application\models\cv;

/**
 * Final model class.
 *
 * @final
 * @extends        module_model
 * @package    profile
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright    Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model
{

    protected $model_name = 'cv';
    protected $processPost = false;

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

        $address_book_id = $this->page_options[0];
        $personal_db = new \core\modules\personal\models\common\db;
        $this->cv = $personal_db->getCurriculumVitae($address_book_id);

        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('cv');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->addViewVariables('myURL', $this->myURL);
        $this->view_variables_obj->addViewVariables('status', $this->status);
        $this->view_variables_obj->addViewVariables('cv', $this->cv);

        return;
    }

}

?>