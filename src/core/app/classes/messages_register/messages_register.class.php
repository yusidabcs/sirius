<?php
/**
 * messages_register
 * is a singleton
 * 
 * Keeps a log of everything that needs to be logged from the site access to changes 
 * with the data.
 */
namespace core\app\classes\messages_register;

class messages_register {
	
	private static $_singleton; // the actual class object for the messages 
    
	public function __construct()
	{
		trigger_error('Messages register is a singleton call getInstance!.', E_USER_ERROR);
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
			self::$_singleton = new messages_register_obj();
		}
		return self::$_singleton;
	}
	
}
?>