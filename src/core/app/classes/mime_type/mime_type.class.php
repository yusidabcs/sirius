<?php
namespace core\app\classes\mime_type;
/**
 * Final mime_type class.
 *
 * Singleton
 *
 * @package 	mime_type
 */
class mime_type {
	
	private static $_singleton; // the actual class object for the messages
	private static $_mime_type_obj;

	/*
	 * Constructor
	 * it will call _loadCacheObj method
	 */
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
			self::$_singleton = new mime_type();
		}
		return self::$_mime_type_obj;
	}

    /*
     * Call mime_type_cache
     * to put mime_type_obj class object in cache file
     */
	private function _loadCacheObj()
    {
            $cache = new mime_type_cache();
            self::$_mime_type_obj = $cache->getCache();
		return;
    }
}
?>