<?php
namespace core\modules\education_application\models\home;


final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	
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
		// $education_app_db = new \core\modules\education_application\models\common\db;
		// $this->courses = $education_app_db->getAllCourseApp($this->address_book_id);
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
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();
		$this->view_variables_obj->addViewVariables('address_book_id', $this->address_book_id);
		//$this->view_variables_obj->addViewVariables('courses', $this->courses);
        $this->view_variables_obj->addViewVariables('baseURL', $this->baseURL);
		return;
	}
		
}
?>