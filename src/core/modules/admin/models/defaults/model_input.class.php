<?php
namespace core\modules\admin\models\defaults;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'defaults';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		if($_POST['action'] == 'update_default_module_config')
		{
			$this->_processModuleDefaults();
		} else {
			die("Sorry I can not do anything with that!");
		}
		return;
	}
	
	private function _processModuleDefaults()
	{
		//output nothing at all
		$new_local_defaults = array();
		
		//local default file
		$local_default_module_file = DIR_SECURE_INI.'/site_module_local_defaults.ini';
		
		//load the site ini file
	    if(is_file(DIR_SECURE_INI.'/site_module_config.ini'))
	    {
	    	$a_site_module_config = parse_ini_file(DIR_SECURE_INI.'/site_module_config.ini',true);     
	    } elseif (is_file(DIR_IOW_APP_INI.'/site_module_config.ini')) {
	    	$a_site_module_config = parse_ini_file(DIR_IOW_APP_INI.'/site_module_config.ini',true);     
	    } else {
	    	$msg = 'The INI file site_module_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
	    
	    //load the local defaults that overwrite the preset defaults
    	if(is_file($local_default_module_file))
	    {
	    	$local_defaults = parse_ini_file($local_default_module_file,true);
	    	
	    	foreach($local_defaults as $local_name => $local_info)
	    	{
		    	$overwrite_defaults[$local_name] = $local_info;
	    	}
	    }
	    
	    //key sort 
	    ksort($a_site_module_config);
	    
	    foreach($_POST as $key => $value)
	    {
		    $posted[$key] = $value;
	    }
	    
	    //load the system defaults for each module
    	foreach ($a_site_module_config as $moduleName => $info)
    	{
			//DIR_IOW_MODULES
			if(is_file(DIR_MODULES.'/'.$moduleName.'/defaults.ini')) {
				//DIR_IOW_MODULES
		    	$module_defaults = parse_ini_file(DIR_MODULES.'/'.$moduleName.'/defaults.ini',true);
		    	
		    	foreach($module_defaults as $default_name => $default_info)
		    	{
			    	$all_defaults[$default_name] = $default_info['default'];
			    	
			    	if(isset($posted[$default_name]) && $posted[$default_name] != $all_defaults[$default_name] )
			    	{
				    	$new_local_defaults[$default_name] = $posted[$default_name];
			    	} else {
				    	$new_local_defaults[$default_name] = $all_defaults[$default_name];
			    	}
		    	}   
		    } 
	    }
	    
	    $this->_iniProcess = new \core\app\classes\ini\write_ini();
	    
	    if(!$this->_iniProcess->write_php_ini($new_local_defaults, $local_default_module_file))
		{
			$this->_content_a['error_a']['ERROR - DEFAULT MODOULE'] = "There was a problem with the site config file {$local_default_module_file}";
		}

		return;
	}
	
}
?>