<?php
namespace core\modules\cv\models\share;

final class model extends \core\app\classes\module_base\module_model {

    protected $model_name = 'share';
    protected $processPost = false;


    public function __construct()
    {
        parent::__construct();
        $this->personal_db = new \core\modules\personal\models\common\db;
        $this->cv_db = new \core\modules\cv\models\common\db;
        return;
    }

    //required function
    protected function main()
    {
        if (isset($this->page_options[0])){
            $address_book_id = 0;
            $hash_cv = $this->page_options[0];
            $data_personal_cv = $this->cv_db->getIDHashPersonalCV($hash_cv);
            if(count($data_personal_cv)>0) {
                $address_book_id = $data_personal_cv[0]['address_book_id'];
            }
            $user_verification = $this->personal_db->checkVerification($address_book_id);
            if(isset($user_verification['status']) && $user_verification['status']=='verified') {
                $data_cv = $this->personal_db->getCurriculumVitae($address_book_id);
                $this->cv = $this->generateCV($data_cv,$data_personal_cv[0]['template']);
            } else {
                $html_ns = NS_HTML.'\\htmlpage';
                $htmlpage = new $html_ns(404);
                exit();
            }
        } else {
            $html_ns = NS_HTML.'\\htmlpage';
	    	$htmlpage = new $html_ns(404);
			exit();
        }
        $this->defaultView();
        return;
    }

    protected function defaultView()
    {
        $this->view_variables_obj->setViewTemplate('share');
        return;
    }

    //required function
    protected function setViewVariables()
    {
        $this->view_variables_obj->addViewVariables('cv',$this->cv);
        return;
    }

    public function generateCV($data_cv,$template) {
		$out='';
        //$img = "/local/uploads/address_book/".$data_cv['address_book_id']."/".$data_cv['full_image'];
        $img = '/ao/show/'.$data_cv['full_image'];
        $file_template = DIR_MODULES."/cv/views/template/".$template.".php";
        if (file_exists($file_template)) {
            include $file_template;
        } else {
            $html_ns = NS_HTML.'\\htmlpage';
	    	$htmlpage = new $html_ns(404);
			exit();
        }
	 	return $out;
    }

}
?>