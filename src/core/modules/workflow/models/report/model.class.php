<?php
namespace core\modules\workflow\models\report;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'report';
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
        $recruitment_db = new \core\modules\recruitment\models\common\db;

        $this->levels = ['team','management','supervisor'];
        $this->trackers = [
            'recruitment',
            'flight','hireright','medical','oktb','passport','police','principal','psf','seaman','security','stcw','travelpack','vaccination','visa','personal_reference','profesional_reference','english_test','premium_service','interview_ready','education'
        ];
        sort($this->trackers);

        $this->partners = $recruitment_db->getListPartner();

        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('report');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('levels',$this->levels);
        $this->view_variables_obj->addViewVariables('trackers',$this->trackers);
        $this->view_variables_obj->addViewVariables('partners',$this->partners);
        return;
    }

}
?>
