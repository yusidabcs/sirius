<?php
namespace core\modules\admin\models\defaults;


/**
 * Final model class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 24 November 2014
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'defaults';
	protected $processPost = true;
	
	private $_system_ini_a;
	
	public function __construct()
	{
		parent::__construct();
		
		//this model uses a number of different functions
		
		return;
	}
	
	//required function
	protected function main()
	{
		$this->_setModuleDefaults();
		
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		//template
		$this->view_variables_obj->setViewTemplate('defaults');
		
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
	
	/**
	 * _setModuleDefaults function.
	 * 
	 * Used to load two arrays into variables for view.  One for the defaults and the other
	 * for the local overwrite of variables
	 *
	 * @access private
	 * @return void
	 */
	private function _setModuleDefaults()
	{
		//set variable
		$all_defaults = array();
		$overwrite_defaults =  array();
		
		//load the site ini file
	    if(is_file(DIR_SECURE_INI.'/site_module_config.ini'))
	    {
	    	$a_site_module_config = parse_ini_file(DIR_SECURE_INI.'/site_module_config.ini',true);     
	    } elseif (is_file(DIR_APP_INI.'/site_module_config.ini')) {
	    	$a_site_module_config = parse_ini_file(DIR_APP_INI.'/site_module_config.ini',true);     
	    } else {
	    	$msg = 'The INI file site_module_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
	    
	    //load the local defaults that overwrite the preset defaults
    	if(is_file(DIR_SECURE_INI.'/site_module_local_defaults.ini'))
	    {
	    	$overwrite_defaults = parse_ini_file(DIR_SECURE_INI.'/site_module_local_defaults.ini',true);
	    }
	    
		//key sort 
	    ksort($a_site_module_config);
	    
	    //load the system defaults for each module
    	foreach ($a_site_module_config as $moduleName => $info)
    	{
			if(is_file(DIR_MODULES.'/'.$moduleName.'/defaults.ini')) {
				
		    	$module_defaults = parse_ini_file(DIR_MODULES.'/'.$moduleName.'/defaults.ini',true);
		    	
		    	foreach($module_defaults as $default_name => $default_info)
		    	{
			    			    	
			    	$all_defaults[$moduleName][$default_name]['default'] = $default_info['default'];
			    	$all_defaults[$moduleName][$default_name]['help'] = $default_info['help'];
			    	
			    	//process options
			    	if($default_info['options'] == 'TEXT')
			    	{
				    	//input
			    		$options[0] = $default_info['options'];
			    	} else {
				    	//select list
				    	$options_array = explode('|', $default_info['options']);
				    	
				    	foreach($options_array as $option_str)
				    	{
					    	$option_a = explode(',', $option_str);
					    	$options[$option_a[0]] = $option_a[1];
				    	}
			    	}	
			    	
			    	$all_defaults[$moduleName][$default_name]['options'] = $options;
			    	
			    	if(isset($overwrite_defaults[$default_name]))
			    	{
				    	$all_defaults[$moduleName][$default_name]['local_default'] = $overwrite_defaults[$default_name];
			    	} else {
				    	$all_defaults[$moduleName][$default_name]['local_default'] = $default_info['default'];
			    	}
			    	
			    	//unset options
			    	unset($options);
		    	}   
		    } 
	    }
	    
		//add the infor for use in the view
		$this->view_variables_obj->addViewVariables('all_defaults',$all_defaults);
		$this->view_variables_obj->addViewVariables('overwrite_defaults',$overwrite_defaults);
		
		return;
	}
		
}
?>