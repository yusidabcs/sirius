<?php
namespace core\app\classes\system_register;

/**
 * Final system_register_obj class.
 * 
 * This is the actual system register object itself.  Changes to its behaviour go here
 * It is cachable and changes to the caching go in system_register_cache
 *
 * @final
 * @package 	system_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 12 August 2019
 */
final class system_register_obj {

	private $_site_info;
	private $_system_info;
	private $_site_term;
	private $_module_info;
	private $_module_array;
	private $_security_level;
	private $_group_config;
	private $_site_ini_a;
	
    /**
     * __construct function.
     * 1. it will load siteinfo config
     * 2. it will load group config
     * 3. it will load module info config
     * 4. it will load security level config
     * 4. it will load site term
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
    	//load up site config file
    	$this->_setSiteInfo();
    	
		//load up system config file
    	$this->_setSystemInfo();

    	//make group config
        $this->_setGroupConfig();
        
        //make modules information
        $this->_setModuleInfo();
        
        //make security levels
        $this->_setSecurityLevel();
        
    	//load up site term file
    	$this->_setSiteTerm();
    	
		return;
    }

	/**
	* All the following are private functions that load in classes that 
	* handle one aspect of the information that collectively fall under 'system register'.  
	* The system register is cachable however these individual classes do not have to be.
	*
	* Most of these classes remain constant over a lot of their lifetime.
	*/
	
    /**
     * _setSiteInfo function.
     * 
     * Loads the site informaton using the site_info class
     *
     * @access private
     * @return void
     */
    private function _setSiteInfo()
    {
	    $site_info_file = NS_APP_CLASSES.'\\system_register\\site_info\\site_info';
	    $this->_site_info = new $site_info_file();
	    return;
    }

	private function _setSystemInfo()
    {
	    $system_info_file = NS_APP_CLASSES.'\\system_register\\system_info\\system_info';
	    $this->_system_info = new $system_info_file();
	    return;
    }
    
    /**
     * _setGroupConfig function.
     * 
     * Loads the site informaton using the group_config class
     *
     * @access private
     * @return void
     */
    private function _setGroupConfig()
    {	
		$group_config_file = NS_APP_CLASSES.'\\system_register\\group_config\\group_config';
        $this->_group_config = new $group_config_file();
        return;
    }
    
     /**
     * _setModuleInfo function.
     * 
     * Loads the site wide terms using the module_info classs
     *
     * @access private
     * @return void
     */
    private function _setModuleInfo()
    {	
		$group_module_info = NS_APP_CLASSES.'\\system_register\\module_info\\module_info';
		$this->_module_info = new $group_module_info();
        return;
    }

	 /**
     * _setSecurityLevel function.
     * 
     * Loads the site wide terms using the security_level classs
     *
     * Note: you must run this function AFTER _setModuleInfo() because it relies on information that is set from it.
     *
     * @access private
     * @return void
     */
    private function _setSecurityLevel()
    {	
		$security_level_info = NS_APP_CLASSES.'\\system_register\\security_level\\security_level';
        $this->_security_level = new $security_level_info($this->_module_info->getModuleSecurity());
        return;
    }

    /**
     * _setSiteTerm function.
     * 
     * Loads the site wide terms using the site_terms classs
     *
     * @access private
     * @return void
     */
    private function _setSiteTerm()
    {
	    $security_level_info = NS_APP_CLASSES.'\\system_register\\site_term\\site_term';
		$this->_site_term = new $security_level_info();
	    return;
    }
    
    //!SITE INFO FUNCTION
    
    /**
    * The following functions that are called with system_register->site_info('SALT') or system_register->site_info('salt')
    **/
    public function site_info($item)
    {
	    $item = strtoupper($item);
	    return $this->_site_info->$item;
    }
    
	public function system_info($item)
    {
	    $item = strtoupper($item);
	    return $this->_system_info->$item;
    }

	//!GROUP CONFIG FUNCTIONS
	
	public function getGroupArray()
    {
		return $this->_group_config->getGroupArray();
	}
	
	public function getGroups($group_id)
	{
		return $this->_group_config->getGroups($group_id);
	}
	
	public function getGroupTitle($group_id)
	{
		return $this->_group_config->getGroupTitle($group_id);
	}
	
	public function permittedGroupArray()
	{
		return $this->_group_config->permittedGroupArray();
	}
	
	//!MODULE INFO FUNCTIONS
	
	public function getModuleNamesArray()
    {
		return $this->_module_info->getModuleNamesArray();
	}
	
	public function getModuleActiveArray()
    {
		return $this->_module_info->getModuleActiveArray();
	}
	
	public function getModuleSecurityLevel($module,$level)
    {
		$security_level_id = $this->_module_info->getModuleSecurityLevelId($module,$level);
		return $this->getSecurityLevel($security_level_id);
	}
	
	public function getModuleActiveFlag($module)
	{
		return $this->_module_info->getModuleActiveFlag($module);
	}
	
	public function getModuleIsInstalled($moduleName)
	{
		return $this->_module_info->getModuleIsInstalled($moduleName);
	}
	
	//!SECURITY LEVEL FUNCTIONS
	
	/**
     * security_array function.
     * 
     * A function that returns an array of the security levels that are relevant
     * to this instance of the software based on the ini file.
     *
     * 	Array
     * 		(
     * 		    [NONE] => 1
     * 		    [USER] => 10
     * 		    [ADMIN] => 90
     * 		    [SYSADMIN] => 100
     * 		)
     *
     * @access public
     * @return array security information
     */
    public function getSecurityArray()
    {
		return $this->_security_level->getSecurityArray();
	}
	
	public function getSecurityLevel($security_level_id)
	{
		return $this->_security_level->getSecurityLevel($security_level_id);
	}
	
	public function getSecurityTitle($security_level)
	{
		return $this->_security_level->getSecurityTitle($security_level);
	}
	
	public function getSecurityTitleFromId($security_level_id)
	{
		return $this->_security_level->getSecurityTitleFromId($security_level_id);
	}
	
	public function permittedSecurityArray()
	{
		return $this->_security_level->permittedSecurityArray();
	}
	
	public function permittedSecurityLevelIds()
	{
		return $this->_security_level->permittedSecurityLevelIds();
	}
	
	//!SITE TERMS FUNCTIONS
    
	/**
	 * site_term function.
	 * 
	 * @access public
	 * @param string $term the placeholder string
	 * @return string the correct text for the given place holder
	 */
	public function site_term($term)
	{
		$out = $this->_site_term->$term;
		if(empty($out))
		{
			$out = "{$term} - TERM_NOT_SET";
		} 		
		return $out;
	}
	
	/**
	 * addSiteTerm function.
	 *
	 * Allows the module to add site terms specific to the module - used in base module controller
	 * 
	 * @access public
	 * @param mixed $index
	 * @param mixed $term
	 * @return void
	 */
	public function addSiteTerm($index,$term)
	{
		$this->_site_term->addTerm($index,$term);	
		return;
	}
	
}
?>