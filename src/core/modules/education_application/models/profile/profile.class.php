<?php
namespace core\modules\education_application\models\profile;
	

final class profile extends \core\app\classes\module_base\module_profile {
	
	public function __construct($address_book_id)
	{	
		parent::__construct();

		$this->setViewVariables('show_profile',true);
		if(empty($_SESSION['address_book_id'])){
            $this->setViewVariables('show_profile',false);
            return;
        }
		$this->setViewJs('main');
		$course_info = [];
		$education_app_db = new \core\modules\education_application\models\common\db;
		$course_info = $education_app_db->getAllCourse($address_book_id,5);

		//get my course link
		//check if register module is installed
		$system_register = \core\app\classes\system_register\system_register::getInstance();
        if($system_register->getModuleIsInstalled('education_application'))
        {
			$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
			$menu_register = $menu_register_ns::getInstance();
            $education_application_link = $menu_register->getModuleLink('education_application');
        } else {
            $education_application_link = false;
        }

		$this->setViewVariables('course_info',$course_info);
		$this->setViewVariables('education_application_link',$education_application_link);
		return false;
	}
	
}
?>