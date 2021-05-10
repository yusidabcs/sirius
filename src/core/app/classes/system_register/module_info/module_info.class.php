<?php
namespace core\app\classes\system_register\module_info;

/**
 * Final module_info class.
 * 
 * This is the class that handle module info.  Each element within the class is an object of module_info_obj.
 *		
 * @final
 * @package 	system_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 18 August 2019
 */
final class module_info {

	private $_site_module_config_array; //the array from the config file
	private $_a_required_security = array(); //this is an array of the minimum defined security levels needed for this instance
    
    public function __construct()
    {
    	$this->_loadSiteModuleConfigIni();		    	
	    return;
    }
    
    /**
     * _loadSiteModuleConfigIni function.
     * 
     * @access private
     * @return void
     */
    private function _loadSiteModuleConfigIni()
    {
		$site_module_config_required_file = DIR_APP_INI.'/site_module_config_required.ini';
	    
	    if (is_file($site_module_config_required_file)) {
		    
	    	$site_module_config_required_a = parse_ini_file($site_module_config_required_file,true);
	    	   
	    } else {
	    	$msg = 'The Site Module Config Required INI file can not be found!';
	    	throw new \RuntimeException($msg); 
	    }
	    
	    $site_module_config_file_local = DIR_SECURE_INI.'/site_module_config.ini';
		$site_module_config_file_original = DIR_APP_INI.'/site_module_config.ini';
	    
	    //load the site ini file
	    if(is_file($site_module_config_file_local))
	    {
		    
	    	$site_module_config = parse_ini_file($site_module_config_file_local,true); 
	    	    
	    } elseif (is_file($site_module_config_file_original)) {
		    
	    	$site_module_config = parse_ini_file($site_module_config_file_original,true);
	    	   
	    } else {
		    
	    	$msg = 'The INI file site_module_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 	
	    }
	    
	    //key sort 
		ksort($site_module_config);
		
	    //add in the iow information about the specific modules and get a list of the required security objects 
    	foreach ($site_module_config as $moduleName => $info)
    	{
	    	if( !empty($site_module_config_required_a[$moduleName]) )
	    	{		    	
		    	$this->_site_module_config_array[$moduleName] = array_merge($site_module_config_required_a[$moduleName],$site_module_config[$moduleName]);
	    		
	    		//we need to get a set of all the required security levels to make sure they are defined
		    	$required_security_array[ $info['security_access'] ] = 1;
		    	$required_security_array[ $info['security_admin'] ] = 1;
	
		    } else {
			    
			    $msg = "$moduleName is in the local module config file but not in the core required module config file!";
				throw new \RuntimeException($msg); 
	    	}
    	}
    	
    	$this->_a_required_security = array_keys($required_security_array);
    		    	
	    return;
    }
    	
    /**
     * getModuleSecurity function.
     * 
     * This function returns the array of the required security values and is used in the
     * security settings (within system_register) to make sure that we have a security definition
     * for every required security definition for the config file.
     *
     * @access public
     * @return array the actual security array
     */
    public function getModuleSecurity()
    {
    	if(is_array($this->_a_required_security) && array_count_values($this->_a_required_security) > 0)
    	{
	    	$out = $this->_a_required_security;
    	} else {
	    	$msg = "There is no required security information to return!";
	    	throw new \RuntimeException($msg);
    	}
		return $out;
	}
	
	//A list of the modules that are defined
	public function getModuleNamesArray()
    {
		return array_keys($this->_site_module_config_array);
	}
	
	//A list of the modules that are defined (installed)
	public function getModuleActiveArray()
    {
		return $this->_site_module_config_array;
	}
	
	//True if the module is installed on this site
	public function getModuleIsInstalled($moduleName)
    {
		return isset($this->_site_module_config_array[$moduleName]);
	}
	
	public function getModuleSecurityLevelId($module,$level)
	{
		$acceptable_levels_a = array('security_access','security_admin');
		
		if(in_array($level, $acceptable_levels_a))
		{
			$out = $this->_site_module_config_array[$module][$level];
		} else {
			$out = 'SYSADMIN';
		}
		return $out;
	}
	
	public function getModuleActiveFlag($module)
	{
		if(isset($this->_site_module_config_array[$module]['visible']))
		{
			$out = $this->_site_module_config_array[$module]['visible'];
		} else {
			$out = 0;
		}
		return $out;
	}

}
?>