<?php
namespace core\app\classes\system_register\system_info;


final class system_info {
 
    public function __construct()
    {  
    	$this-> _loadSystemConfigIni();
	    return;
    }
    

    private function _loadSystemConfigIni()
    {
	    $system_config_file_local = DIR_SECURE_INI.'/system_config.ini';
    	$system_config_file_original = DIR_APP_INI.'/system_config.ini';
    	
	    if(is_file($system_config_file_local))
	    {
		    
	    	$system_a = parse_ini_file($system_config_file_local);
	    	  
	    } elseif (is_file($system_config_file_original)) {
		    
	    	$system_a = parse_ini_file($system_config_file_original);
	    	  
	    } else {
		    
	    	$msg = 'The INI file system_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    	
	    }
	    
    	foreach ($system_a as $key => $value)
    	{
    		$var = '_';
    		$var .= $key;
    		$this->$var = $value;	
    	}

	    return;

    }
        

    public function __get($property)
    {
    	$var = '_'.$property;
      	
    	if(!isset($this->$var))
    	{
	    	$msg = 'Requested System Info Value '.$property.' does not exist!';
	    	throw new \RuntimeException($msg); 
    	}
    	
		return $this->$var;
	}
	
}
?>