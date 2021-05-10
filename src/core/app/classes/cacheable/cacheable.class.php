<?php
namespace core\app\classes\cacheable;

abstract class cacheable {
    
    //leave these
    protected $cache_file;
    protected $cache = false;
    
    //needed to be set by the extended classes
    protected $cache_name; //need to define the name of the cache
    protected $cache_time = false; //should be the defined in seconds 0 means never
    
    public function __construct()
    {
        if(!ctype_alnum($this->cache_name))
        {
            throw new \RuntimeException("You need to set the cache name properly, it can only be alphnum!");
        }
        
        $this->cache_file = DIR_SECURE_CACHE.'/'.$this->cache_name.'.cache';
        
        if( !is_readable( $this->cache_file ) )
        { 
            $this->makeCache();
            $this->saveCache();
        }
    }
    
    abstract protected function makeCache();
    
    public function getCache()
    {
        if(is_readable($this->cache_file))
        {
            $str_content = file_get_contents($this->cache_file);
            if(!$this->cache = unserialize($str_content))
            {
                throw new \RuntimeException("Unable to unserialize the cache file: {$this->cache_file}");
            }
            $this->checkCache();
        } else {
            throw new \RuntimeException("There should be a cache file: {$this->cache_file}. Check the makeCache function.");
        }
        return $this->cache;
    }
    
    public function rebuildCache()
    {
        if( !is_readable($this->cache_file) )
        {
        	$this->makeCache();
            $this->saveCache();
        } else {
        	unlink($this->cache_file);
        	$this->makeCache();
        	$this->saveCache();
        }
    }
    
    private function checkCache()
    {
        if(false === $this->cache_time)
        {
            throw new \RuntimeException("You need to set a cache time for {$this->cache_name}.");
        } elseif (0 == $this->cache_time) {
	        return;
        } else {
        	if(is_file($this->cache_file))
        	{
            	if( (filectime($this->cache_file) + $this->cache_time) < time() )
	            {
	                unlink($this->cache_file);
	                $this->makeCache();
	                $this->saveCache();
	            } 
			}
        }
        return;
    }
    
    private function saveCache()
    {
        if($this->cache)
        {
            if($str_content = serialize($this->cache))
            {
                if(!file_put_contents($this->cache_file, $str_content))
                {
                    throw new \RuntimeException("Attempt to save cache file {$this->cache_file} failed unable to output to the file!");
                }
            } else {
                throw new \RuntimeException("Attempt to save cache file {$this->cache_file} failed because cache could not be serialized!");
            }
        } else {
            throw new \RuntimeException("Attempt to save cache file {$this->cache_file} failed because no cache exists!");
        }
        return;
    }
    
}
?>