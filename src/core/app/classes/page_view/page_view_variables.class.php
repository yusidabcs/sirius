<?php
namespace core\app\classes\page_view;


/**
 * Final page_view_variables class.
 * 
 * @final
 *
 * is a singleton
 * 
 * Everytime a person views a URL index.php page this class is used to handle 
 * the $_GET for the page
 *
 * @final
 * @package 	view
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 14 August 2019
 */

final class page_view_variables {
	
	private static $_singleton;
    private static $_view_variables_obj; // the actual class object for the menu register
    
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
			self::$_singleton = new page_view_variables();
		}
		return self::$_view_variables_obj;
	}
     
    private function _loadViewObj()
    {
        self::$_view_variables_obj = new page_view_variables_obj();
		return;
    }
    
}
?>