<?php
namespace core\modules\admin\models\common;

/**
 * Final admin_module class.
 *
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class admin_module {
	
	private $current_modules_config = array(); //current module configuration
	private $iniFile; //the system INI file
		
	public function __construct()
	{	
		//read in the config file
		$this->iniFile = DIR_SECURE_INI.'/site_module_config.ini';
		
		$this->_setCurrentModulesConfigurationArray();
		
		return;
	}
	
	private function _setCurrentModulesConfigurationArray()
	{
		//load the site ini file
	    if(is_file($this->iniFile))
	    {
	    	$this->current_modules_config = parse_ini_file($this->iniFile,true);     
	    } else {
	    	$msg = 'The site INI file site_module_config can not be found anywhere - please sync ini files BEFORE adding modules!';
	    	throw new \RuntimeException($msg); 
	    }
	    
	    return;
	}
	
	/**
	 * updateModules function.
	 *
	 * @example of a module $module_security_array
	 *		 Array
	 *		(
	 *		    [address_book] => Array
	 *		        (
	 *		            [security_access] => ADMIN
	 *		            [security_admin] => ADMIN
	 *		        )
	 *			 [survey_hierarchy] => Array
	 *		        (
	 *		            [security_access] => ADMIN
	 *		            [security_admin] => ADMIN
	 *		            [install] => 1
	 *		        )
	 *		)
	 *
	 * @access public
	 * @param array $module_array
	 * @return void
	 */
	public function installUpdateModules($module_security_array)
	{  	
		//run over the module security array
		foreach($module_security_array as $module => $value)
		{
			//if it is currently configured then update the config
			if(!empty($this->current_modules_config[$module]))
			{
			   
				
				if( isset($value['uninstall']) && $value['uninstall'] == 1 ) {
					unset($this->current_modules_config[$module]);
				}else{
					$this->current_modules_config[$module]['security_access'] = $module_security_array[$module]['security_access'];
					$this->current_modules_config[$module]['security_admin'] = $module_security_array[$module]['security_admin'];
				}
			} else if( isset($value['install']) && $value['install'] == 1 ) {
			
				//add the new installed modules
				if(is_file((DIR_MODULES.'/'.$module.'/module.ini')))
				{
					$this->current_modules_config[$module]['security_access'] = $module_security_array[$module]['security_access'];
					$this->current_modules_config[$module]['security_admin'] = $module_security_array[$module]['security_admin'];
				} else {
					$msg = "Module Admin told to install new module '{$module}' but it has NO module INI file!";
					throw new \RuntimeException($msg);
				}
			}
			
		}
		
		//update the system
		$this->updateModules();
		
	    return;
	}
	
	public function updateModules()
	{  	
		//check required
		$this->_checkRequiredModules();
		
		//write new module config INI
		$this->_writeModuleConfigINI();
		
		//update all tables
		// $this->_updateModuleTables();
		
		//delete the current cache file
		unlink(DIR_SECURE_CACHE.'/systemRegister.cache');
		
	    return;
	}
	
	private function _checkRequiredModules()
	{
		foreach($this->current_modules_config as $module => $value)
		{
			//don't try to do anything with site_down
			if($module == 'site_down') continue;
			if($module == 'interview_security_tracker') continue;
			
			if(is_file(DIR_MODULES.'/'.$module.'/module.ini') ) 
			{
		    	$module_config = parse_ini_file(DIR_MODULES.'/'.$module.'/module.ini',true);
		    	$required = $module_config['config']['required'];
		    	if(!empty($required) || $required != 'none')
		    	{
			    	$required_array = explode('|', $required);
			    	foreach($required_array as $required_module)
			    	{
				    	//if it is new then install it
				    	if($required_module != 'none' && empty($this->current_modules_config[$required_module]))
				    	{
					    	if(is_file((DIR_MODULES.'/'.$required_module.'/module.ini')))
					    	{
						    	//update the original module config
							    $this->current_modules_config[$required_module]['security_access'] = 'ADMIN';
							    $this->current_modules_config[$required_module]['security_admin'] = 'ADMIN';
							} else {
								$msg = "Module Admin told to install new module '{$required_module}' required for '{$module}' but it has NO module INI file!";
								throw new \RuntimeException($msg);
							}
				    	} 
			    	}
		    	}
		    	 
		    } else {
		    	$msg = "Went to install required {$module} but the module INI file can not be found!";
		    	throw new \RuntimeException($msg); 
		    }
	    }
	
	    return;
	}
	
	private function _writeModuleConfigINI()
	{
		$writeIni = new \core\app\classes\ini\write_ini();
		$writeIni->write_php_ini($this->current_modules_config, $this->iniFile);
		
		return;
	}
	
	private function _updateModuleTables()
	{	
		foreach($this->current_modules_config as $module => $value)
		{
			//don't try to do anything with site_down, admin or menu (they don't have setup files!
			if($module == 'site_down' || $module == 'admin' || $module == 'menu') continue;
			
			$setupClass = NS_MODULES.'\\'.$module.'\setup\setup';
			$setup = new $setupClass();
		}
		
		return;
	}
	
}

?>