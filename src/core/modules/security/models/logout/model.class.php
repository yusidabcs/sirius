<?php
namespace core\modules\security\models\logout;

/**
 * Final logout_model class.
 * 
 * @final
 * @package 	security
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 16 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {
	
	protected $model_name = 'logout';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	//required function
	protected function main()
	{
		$this->view_variables_obj->setViewTemplate('logout');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//overwrite the page title
		$this->view_variables_obj->setPageTitle('Log Out');
		
		//POST Variable
		$link_id = $this->menu_register->getModuleLink('security');
		$this->view_variables_obj->addViewVariables('post',"/{$link_id}/logout");
		return;
	}
		
}
?>