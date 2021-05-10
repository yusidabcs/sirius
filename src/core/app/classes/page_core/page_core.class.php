<?php
namespace core\app\classes\page_core;

/**
 * Final page_core class.
 *
 * Singleton
 *
 * @package 	page_core
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 19 August 2019
 */
class page_core {
	
	private static $_singleton; // the actual class object for the messages
    
	private function __construct()
	{
		//trigger_error('This is a singleton use ::getInstance.', E_USER_ERROR);
		return;
	}
	
	public function __clone()
	{
		trigger_error('Cloning instances of this class is forbidden.', E_USER_ERROR);
	}
	
    public function __set($index,$value)
    {
		trigger_error('Setting variable for this messages register is not allowed.', E_USER_ERROR);
	}

	public function __get($index)
    {
		trigger_error('Getting variable for this messages register is not allowed.', E_USER_ERROR);
	}
	
	public function __isset($index)
    {
        trigger_error('Setting variable for this messages register is not allowed.', E_USER_ERROR);
    }

    public function __unset($index)
    {
        trigger_error('Unsetting variable for this messages register is not allowed.', E_USER_ERROR);
    }

	public static function getInstance()
	{
		if (is_null(self::$_singleton))
		{
			self::$_singleton = new page_core_obj;
		}
		return self::$_singleton;
	}
	
}
?>