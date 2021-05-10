<?php
namespace core\modules\interview\models\detail_location;

/**
 * Final model class.
 *
 * @final
 * @package 	interview
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 October 2019
 */
final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'detail_location';
    protected $processPost = true;

    public function __construct()
    {
        parent::__construct();
        $this->interview_db = new \core\modules\interview\models\common\db();
        $this->core_db = new \core\app\classes\core_db\core_db();
        return;
    }

    //required function
    protected function main()
    {
        $this->authorize();
        if(empty($this->page_options[0])){
            die('No id set');
            exit();
        }
        $this->location = $this->interview_db->getInterviewLocationById($this->page_options[0]);

        if(!$this->location){
            die('No schedule location found');
            exit();
        }
        $country = $this->core_db->getCountry($this->location['countryCode_id']);
        $subcountry = $this->core_db->getSubCountry($this->location['countrySubCode_id']);

        $this->location['country'] = $country[$this->location['countryCode_id']];
        $this->location['subcountry'] = $subcountry[$this->location['countryCode_id']];

        $this->interview_common = new \core\modules\interview\models\common\common();
        $this->summary = $this->interview_common->getInterviewLocationSummary($this->location['interview_location_id']);
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('detail_location');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->useDatatable();
        $this->view_variables_obj->useSweetAlert();
        $this->view_variables_obj->useFlatpickr();


        //POST Variable
        $this->view_variables_obj->addViewVariables('base_url',$this->baseURL);
        $this->view_variables_obj->addViewVariables('location',$this->location);
        $this->view_variables_obj->addViewVariables('summary',$this->summary);

        return;
    }

}
?>
