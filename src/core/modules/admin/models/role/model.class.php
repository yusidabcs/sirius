<?php
namespace core\modules\admin\models\role;


/**
 * Final model class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee�20 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'role';
	protected $processPost = true;
	
	private $_system_ini_a;
	private $_site_ini_a;
	
	public function __construct()
	{
		parent::__construct();
		
		//this model uses a number of different functions
		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->authorize();
		$this->view_variables_obj->setViewTemplate('role');
	    
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//model stuff
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->useSweetAlert();

		$user_db = new \core\modules\user\models\common\user_db();
		
		$this->view_variables_obj->addViewVariables('module_permissions', $this->_getModulePermissions());
		$this->view_variables_obj->addViewVariables('roles', $user_db->getAllRoles());
		$this->view_variables_obj->addViewVariables('users', $user_db->getUserArray());
		//POST Variable
		$this->view_variables_obj->addViewVariables('post',$this->modelURL);
		
		if($this->input_obj)
		{
			if($this->input_obj->hasInputs())
			{
				$array = $this->input_obj->getInputs();
				foreach($array as $key => $value)
				{
					$this->view_variables_obj->addViewVariables($key,$value);
				}
			}
			
		}

		return;
	}

	private function _getModulePermissions()
	{
		$modules = $this->system_register->getModuleActiveArray();
		$availableModulePermissions = [];

		foreach ($modules as $key => $module) {
			
			$permission_ini_dir = DIR_MODULES . '/' . $key . '/permission.ini';
			if (file_exists($permission_ini_dir)) {
				# code...
				$permission_config = @parse_ini_file($permission_ini_dir, true);
				$availableModulePermissions[$key]['permission_config'] = $permission_config;
			}
		}


		return $availableModulePermissions;
	}
		
}
?>