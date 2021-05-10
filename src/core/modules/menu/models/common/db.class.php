<?php
namespace core\modules\menu\models\common;

/**
 * Final db class.
 * 
 * @final
 *
 * is a singleton
 * 
 * Once common address book class is needed because it may or may not be used by model input
 *
 * @final
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */

final class db {
	
	private static $_singleton;
    private static $_db_obj; // the actual class object for the menu register
    
	private function __construct()
	{
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
			self::$_singleton = new db();
		}
		return self::$_db_obj;
	}
     
    private function _loadViewObj()
    {
        self::$_db_obj = new db_obj();
		return;
    }
    
}
?>