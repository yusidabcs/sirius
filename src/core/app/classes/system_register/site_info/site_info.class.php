<?php
namespace core\app\classes\system_register\site_info;

/**
 * Final site_info class.
 *
 * This is the site object that holds site information
 *
 * @final
 * @package 	system_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 20 September 2014
 */
final class site_info {
 
    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {  
    	$this-> _loadSiteConfigIni();
	    return;
    }
    
    /**
     * _loadSiteConfigIni function.
     * 
     * @access private
     * @return void
     */
    private function _loadSiteConfigIni()
    {
	    $site_config_file_local = DIR_SECURE_INI.'/site_config.ini';
    	$site_config_file_original = DIR_APP_INI.'/site_config.ini';
    	
	    if(is_file($site_config_file_local))
	    {
		    
	    	$site_a = parse_ini_file($site_config_file_local);
	    	  
	    } elseif (is_file($site_config_file_original)) {
		    
	    	$site_a = parse_ini_file($site_config_file_original);
	    	  
	    } else {
		    
	    	$msg = 'The INI file site_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    	
	    }
	    
    	foreach ($site_a as $key => $value)
    	{
    		$var = '_';
    		$var .= $key;
    		$this->$var = $value;	
    	}

	    return;

    }
        
    /**
     * __get function.
     *
     * This function returns the private variables of this class
     * I use this here because it is a subordinate class to system_register
     * and there is no other direct access to this class.  So it is controlled.
     * 
     * @access public
     * @param string $property
     * @return mixed the value of the private variable
     */
    public function __get($property)
    {
    	$var = '_'.$property;
      	
    	if(!isset($this->$var))
    	{
	    	$msg = 'Requested Site Info Value '.$property.' does not exist!';
	    	throw new \RuntimeException($msg); 
    	}
    	
		return $this->$var;
	}
	
}
?>