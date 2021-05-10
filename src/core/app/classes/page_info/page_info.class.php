<?php
namespace core\app\classes\page_info;


/**
 * Final page_info class.
 *
 * is a singleton
 * 
 * Everytime a person views a URL index.php page this class is used to handle 
 * the $_GET for the page
 *
 * @final
 * @package 	page_info
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
final class page_info {
	
	private static $_singleton;
    private static $_page_info_obj; // the actual class object for the menu register

    /*
     * constructor function
     * call private method _loadPageInfo every class initiation
     */
	private function __construct()
	{
        $this->_loadPageInfo();
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
			self::$_singleton = new page_info();
		}
		return self::$_page_info_obj;
	}
    
    public function getVariables()
	{
		return $this->_vars;
	}

	/*
	 * Call the read page info object class
	 */
    private function _loadPageInfo()
    {
        self::$_page_info_obj = new page_info_obj();
		return;
    }
    
}
?>