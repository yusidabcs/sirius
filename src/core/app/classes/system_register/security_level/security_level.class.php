<?php
namespace core\app\classes\system_register\security_level;

/**
 * Final security_level class.
 * 
 * The actual security level class which contains specific security level objects
 *
		[NONE]
		level = 1
		title = "Guest"
		desc = "Access to non-restricted areas only"
		fixed = 1
		;
		[USER]
		level = 10
		title = "User Access"
		desc = "Access for users of the website"
		fixed = 1
		;
		....
		;
		[SYSADMIN]
		level = 100
		title = "System Administrator Access"
		desc = "Access for the system administrators"
		fixed = 1
 *
 * @final
 * @package 	system_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
 
final class security_level {

	private $_securityLevelIdArray = array(); //the array of actual security level objects
	private $_securityLevelArray = array();
	
	private $_permitted_security_array = array(); //array of permitted
	private $_permitted_security_level_ids = ''; // 'NONE','USER' ....
	
    /**
     * __construct function.
     * 
     * @access public
     * @param array $security_required The security definitions that are required by the modules
     * @return void
     */
    public function __construct($security_required = array())
    {	
    	$this->_loadSecurityLevelIni();
	    
	    $this->_checkRequiredOk($security_required);
	    	
	    return;
    }
    
    private function _loadSecurityLevelIni()
    {
    	$site_security_level_config_file_local = DIR_SECURE_INI.'/site_security_level_config.ini';
    	$site_security_level_config_file_original = DIR_APP_INI.'/site_security_level_config.ini';
    		    
	    //load the site ini file
	    if(is_file($site_security_level_config_file_local))
	    {
		    
	    	$this->_securityLevelIdArray = parse_ini_file($site_security_level_config_file_local,true);
	    	
	    } elseif (is_file($site_security_level_config_file_original)) {
		    
	    	$this->_securityLevelIdArray = parse_ini_file($site_security_level_config_file_original,true);   
	    	  
	    } else {
	    	$msg = 'The INI file site_security_level_config can not be found anywhere!';
	    	throw new \RuntimeException($msg); 
	    }
	    
	    foreach($this->_securityLevelIdArray as $key => $value)
	    {
		    $this->_securityLevelArray[$value['level']]['level_id'] = $key;
		    $this->_securityLevelArray[$value['level']]['title'] = $value['title'];
		    $this->_securityLevelArray[$value['level']]['desc'] = $value['desc'];
		    
	    }
	    	
	    return;
    }
    
    private function _checkRequiredOk($security_required)
    {
    	//there should always be some security levels required by the modules
    	if(empty($security_required))
    	{
	    	$msg = 'No security levels given by module!';
	        throw new \RuntimeException($msg);
    	}
    	
    	foreach($security_required as $security_level_id)
    	{
	    	if( empty($this->_securityLevelIdArray[$security_level_id]) )
	    	{
		    	$msg = 'The required security level id '.$security_level_id.' is not in the ini file!';
				throw new \RuntimeException($msg); 
	    	}
    	}

    	return;  
    }
            
    /**
     * getSecurityArray function.
     * 
     * get a simple array of security levels and their number
     *
     * @access public
     * @return array of the security levels
     */
    public function getSecurityArray()
    {
	    return $this->_securityLevelIdArray;
    }
    
    public function getSecurityLevel($security_level_id)
    {
	    $out = '';
	    if(isset($this->_securityLevelIdArray[$security_level_id]))
	    {
		    $out = $this->_securityLevelIdArray[$security_level_id]['level'];
		} else {
			
			echo "<pre>\n";
			echo "\n";
			print_r($this->_securityLevelIdArray);
			echo "</pre>\n";
			die("END");
			
			$msg = "You requested a security level for '{$security_level_id}' but that does not exist!";
		    throw new \RuntimeException($msg);
		}
		return $out;
    }
    
    public function getSecurityTitle($security_level)
    {
	    $out = '';
	    if(isset($this->_securityLevelArray[$security_level]))
	    {
		    $out = $this->_securityLevelArray[$security_level]['title'];
		} else {
			$msg = "You requested a security title for {$security_level} but that does not exist!";
		    throw new \RuntimeException($msg);
		}
		return $out;
    }
    
    public function getSecurityTitleFromId($security_level_id)
    {
	    $out = '';
	    if(isset($this->_securityLevelIdArray[$security_level_id]))
	    {
		    $out = $this->_securityLevelIdArray[$security_level_id]['title'];
		} else {
			$msg = "You requested a security title for {$security_level_id} but that does not exist!";
		    throw new \RuntimeException($msg);
		}
		return $out;
    }
    
    public function permittedSecurityArray()
	{
		if(empty($this->_permitted_security_array))
		{
			foreach ($this->_securityLevelArray as $level => $value)
			{
				if($level <= $_SESSION['user_security_level'])
				{
					$this->_permitted_security_array[] = $value['level_id'];
				}
			}
		}
		return $this->_permitted_security_array;
	}
    
    public function permittedSecurityLevelIds()
	{
		if(empty($this->_permitted_security_level_ids))
		{
			if(empty($this->_permitted_security_array))
			{
				$this->permittedSecurityArray();
			}
			
			//add the quotes
			foreach($this->_permitted_security_array as $key => $value)
			{
				$quoted_array[$key] = "'".$value."'";
			}
			
			$this->_permitted_security_level_ids = implode(',', $quoted_array);
		
		}
		return $this->_permitted_security_level_ids;
	}

}
?>