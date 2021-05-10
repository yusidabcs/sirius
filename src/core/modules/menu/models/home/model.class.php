<?php
namespace core\modules\menu\models\home;

/**
 * Final model class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'home';
	protected $processPost = false;
	
	private $_menu;
	
	public function __construct()
	{
		parent::__construct();	
		return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		//setup menu
		$menu_common_ns = NS_MODULES.'\\menu\\models\\common\\common';
		$menu_common = $menu_common_ns::getInstance();
		
		$this->_menuListArray = $menu_common->getMenuListArray();
		
		//it should not be empty but if it is load the details form the ini file.
		if(empty($this->_menuListArray))
		{
			$menu_common->moveIni2Db();
			$this->_menuListArray = $menu_common->getMenuListArray();
		}
		
		$this->view_variables_obj->setViewTemplate('home');
		
		return;
	}
		
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->addViewVariables('fullMenu',$this->_menuListArray);
		$this->view_variables_obj->addViewVariables('baseURL',$this->baseURL);
		$this->view_variables_obj->addViewVariables('updateURL',$this->baseURL.'/update');
		return;
	}
			
}
?>