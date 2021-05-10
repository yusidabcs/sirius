<?php
namespace core\app\classes\menu_register;

/**
 * menu_register class.
 *
 * is caschable
 * is a singleton
 * 
 * Keeps a register of the menu variables and classes that do not change very often.
 * Is used to store, manipulate and retrieve the variables and subordinate classes
 *
 * This file is just the singleton and reads in the casche file. 
 * The actual object is menu_register_obj
 *
 * @final
 * @package 	menu_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
final class menu_register {
	
	private static $_singleton;
    private static $_menu_register_obj; // the actual class object for the menu register
    
	private function __construct()
	{
	    //every it class called, it will automatically call _loadViewObj method
        $this->_loadViewObj();
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

	/*
	 * magic method to get private/protected variable
	 */
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

    /*
     * return a singleton of menu_register object
     */
	public static function getInstance()
	{
		if (is_null(self::$_singleton))
		{
			self::$_singleton = new menu_register();
		}
		return self::$_menu_register_obj;
	}
    
    public function getVariables()
	{
		return $this->_vars;
	}

	/*
	 * it will call new menu_register_cache class
	 * in menu_register_cache,it will create cache version of menu_register_obj class
	 * set menu_register_obj to $_menu_register_obj variable
	 */
	private function _loadViewObj()
    {
	    $cache = new menu_register_cache();
    	self::$_menu_register_obj = $cache->getCache();
		return;
    }


}
?>