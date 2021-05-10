<?php
namespace core\app\classes\system_register;

/**
 * system register
 * is caschable
 * is a singleton
 * 
 * Keeps a register of the system variables and classes that do not change very often.
 * Is used to store, manipulate and retrieve the variables and subordinate classes
 *
 * This file is just the singleton and reads in the cache file. 
 * The actual object is system_register_obj
 */
class system_register {
	
	private static $_singleton;
    private static $_system_register_obj; // the actual class object for the system register
    
	private function __construct()
	{
        $this->_loadCacheObj();
		return;
	}
	
	public function __clone()
	{
		trigger_error('Cloning instances of this class is forbidden.', E_USER_ERROR);
	}
	
	public function __set($index,$value)
    {
		trigger_error('Setting variable for this register is not allowed.', E_USER_ERROR);
	}

	public function __get($index)
    {
		return $this->_vars[$index];
	}
	
	public function __isset($index)
    {
        return isset($this->_vars[$index]);
    }

    public function __unset($index)
    {
        trigger_error('Unsetting variable for this register is not allowed.', E_USER_ERROR);
    }

	public static function getInstance()
	{
		if (is_null(self::$_singleton))
		{
			self::$_singleton = new system_register();
		}
		return self::$_system_register_obj;
	}
    
    public function getVariables()
	{
		return $this->_vars;
	}
    
    private function _loadCacheObj()
    {
    	$cache = new system_register_cache();
    	self::$_system_register_obj = $cache->getCache();
		return;
    }
    
}
?>