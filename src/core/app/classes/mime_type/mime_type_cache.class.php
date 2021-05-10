<?php
namespace core\app\classes\mime_type;

/**
 * system_register_cache class.
 * 
 * system_register_cache is the access to the system_register_obj which is the actual object.
 * it is cached because it does not change a lot
 */
class mime_type_cache extends \core\app\classes\cacheable\cacheable {
    
    protected $cache_name = 'mimeType'; //need to define the name of the cache
    protected $cache_time = 0; //never trigger a rebuild automatically
    //protected $cache_time = 1; //trigger rebuild every time for now
    
    protected function makeCache()
    {
        $this->cache = new mime_type_obj;
        return;
    }
    
}
?>