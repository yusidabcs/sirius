<?php
namespace core\modules\address_book\models\common;

/**
 * Final address_book_db class.
 * 
 * @final
 *
 * is a singleton
 * 
 * Once common address book class is needed because it may or may not be used by model input
 *
 * @final
 * @package 	address_book
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 01 January 2017
 */

final class address_book_db {
	
	private static $_singleton;
    private static $_address_book_db_obj; // the actual class object for the menu register
    
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
			self::$_singleton = new address_book_db();
		}
		return self::$_address_book_db_obj;
	}
     
    private function _loadViewObj()
    {
        try 
        {
            self::$_address_book_db_obj = new address_book_db_obj();
        } catch (\Exception $e) {
            $htmlOutput = new \core\app\classes\html\htmlmsg($e,DEBUG);
            echo $htmlOutput->getHtmlOutput();
            exit();
        }
		return;
    }
    
}
?>