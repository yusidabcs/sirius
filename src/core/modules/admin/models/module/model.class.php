<?php
namespace core\modules\admin\models\module;

/**
 * Final model class.
 *
 * @final
 * @package 	admin
 *�@author		Martin O'Dee�<martin@iow.com.au>
 *�@copyright	Martin O'Dee�17 August 2019
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'module';
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
		//get the additional iow data
	    if (is_file(DIR_APP_INI.'/site_module_config_required.ini')) {
	    	$iow_modules_info_a = parse_ini_file(DIR_APP_INI.'/site_module_config_required.ini',true);     
	    } else {
	    	$msg = 'The IOW Modules INI file can not be found!';
	    	throw new \RuntimeException($msg); 
	    }
		
		$configured_modules = $this->system_register->getModuleActiveArray();
			
		//get all the visible modules
		foreach( $iow_modules_info_a as $module_name => $value )
		{
			if( array_key_exists($module_name,$configured_modules))
			{
				$this->configured_modules_a[$module_name]['configured'] = 1;
				$this->configured_modules_a[$module_name]['visible'] = $value['visible'];
				$this->configured_modules_a[$module_name]['allow_multiple'] = $value['allow_multiple'];
				$this->configured_modules_a[$module_name]['security_access'] = $configured_modules[$module_name]['security_access'];
				$this->configured_modules_a[$module_name]['security_admin'] = $configured_modules[$module_name]['security_admin'];
			} else {
				$this->configured_modules_a[$module_name]['configured'] = 0;
				$this->configured_modules_a[$module_name]['visible'] = $value['visible'];
				$this->configured_modules_a[$module_name]['allow_multiple'] = $value['allow_multiple'];
				$this->configured_modules_a[$module_name]['security_access'] = '';
				$this->configured_modules_a[$module_name]['security_admin'] = '';
			}
		}
		
		ksort($this->configured_modules_a);
		
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('module');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//post
		$this->view_variables_obj->addViewVariables('post',$this->modelURL);
		
		$this->view_variables_obj->addViewVariables('configured_modules_a',$this->configured_modules_a);
		$this->view_variables_obj->addViewVariables('security_level_id_a',$this->system_register->getSecurityArray());
		
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
		
}
?>