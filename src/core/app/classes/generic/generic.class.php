<?php
namespace core\app\classes\generic;

/**
 * Final generic class.
 * 
 * Is a Singleton - the object is generic_obj
 * @final
 * @package 	generic
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 *
 *
 */
final class generic {
	
	private static $_singleton;
    private static $_generic_obj; // the actual class object for the menu register
    
	private function __construct()
	{
        $this->_loadGeneric();
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
			self::$_singleton = new generic();
		}
		return self::$_generic_obj;
	}
    
    public function getVariables()
	{
		return $this->_vars;
	}
    
    private function _loadGeneric()
    {
        try 
        {
        	self::$_generic_obj = new generic_obj;
        } catch (\Exception $e) {
            $htmlmsg_class = NS_APP_CLASSES.'\\html\\htmlmsg';
            $htmlOutput = new $htmlmsg_class($e,DEBUG);
            echo $htmlOutput->getHtmlOutput();
            exit();
        }
		return;
    }
    
}
?>