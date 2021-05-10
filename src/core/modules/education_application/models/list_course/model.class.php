<?php
namespace core\modules\education_application\models\list_course;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'list_course';
	
	public function __construct()
	{
		parent::__construct();
        return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->address_book_id = $_SESSION['address_book_id'];
		$this->course = [];
		$education_app_db = new \core\modules\education_application\models\common\db;
		$this->courses = $education_app_db->getAllCourse($this->address_book_id);

		$menu_register_ns = NS_APP_CLASSES.'\\menu_register\\menu_register';
		$menu_register = $menu_register_ns::getInstance();
		$education_link = $menu_register->getModuleLink('education_application');
		$this->link_education = "/".$education_link;
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('list_course');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->addViewVariables('address_book_id', $this->address_book_id);
		$this->view_variables_obj->addViewVariables('baseURL', $this->baseURL);
		$this->view_variables_obj->addViewVariables('courses', $this->courses);
		$this->view_variables_obj->addViewVariables('link_education', $this->link_education);

        return;
	}
		
}
?>