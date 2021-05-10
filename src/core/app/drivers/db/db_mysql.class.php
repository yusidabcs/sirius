<?php
namespace core\app\drivers\db;

/**
 * db_mysql class.
 *
 * A singleton and sort of a factory
 *
 * @package 	db
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
class db_mysql {
	
	private static $_singleton;
    private static $_local;
    private static $_core;
    
	private function __construct()
	{
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

	public static function getInstance($loc)
	{
		$acceptable_a = array('local','core');
		
		if(in_array($loc, $acceptable_a))
		{			
			if(is_null(self::$_singleton))
	        {
				self::$_singleton = new db_mysql();
			}
		} else {
			$msg = "Database location {$loc} is not acceptable.";
            throw new \RuntimeException($msg);
		}
		
		switch ($loc) 
		{
		    case 'local':
		    	if(empty(self::$_local))
		    	{
			    	self::$_singleton->_loadDBFactory('local');
		    	}
		        return self::$_local;
		        break;
		    case 'core':
		    	if(empty(self::$_core))
		    	{
			    	self::$_singleton->_loadDBFactory('core');
		    	}
		        return self::$_core;
		        break;
	    }
	     
		return;
	}
    
    public function getVariables()
	{
		return $this->_vars;
	}
    
    private function _loadDBFactory($loc)
    {
	    $classVariableName = '_'.$loc;
		self::$$classVariableName = new db_connect($loc);
		return;
    }
    
}
?>