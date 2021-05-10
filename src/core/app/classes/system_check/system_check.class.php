<?php
namespace core\app\classes\system_check;

/**
 * Final system_check class.
 *
 * @final
 * @package 	system_check
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 12 August 2019
 */
final class system_check {

	private $_error_array;

    /**
     * _system_config_checkObj function.
     * Check if all the site_config that required by system is all available and correct
     * @access private
     * @return void
     */
    private function _system_config_checkObj()
    {
	    $site_config_file_local = DIR_SECURE_INI.'/site_config.ini';
    	
	    if(is_file($site_config_file_local))
	    {
		    
	    	$site_a = parse_ini_file($site_config_file_local);
	    	  
	    } else {
		    
	    	$this->error_array['NO SITE CONFIG INI'] = 'The INI file site_config can not be found in secure ini!';
	    	return;
	    	
	    }
	    	    
	    if(empty($site_a['SALT']))
	    {
		    $this->error_array['SITE CONFIG INI - SALT EMPTY'] = 'Salt can not be empty';
	    }
	    
	    if(empty($site_a['USERNAME']))
	    {
		    $this->error_array['SITE CONFIG INI - USERNAME EMPTY'] = 'Username can not be empty';
	    }
	    
	    if(empty($site_a['PASSWORD']))
	    {
		    $this->error_array['SITE CONFIG INI - PASSWPRD EMPTY'] = 'Password can not be empty';
	    }
	    
	    //site title
	    if(empty($site_a['SITE_TITLE']))
	    {
		    $this->error_array['SITE CONFIG INI - SITE TITLE'] = 'Site Tile can not be empty';
	    }
	        	
		//client name
	    if(empty($site_a['CLIENT_NAME']))
	    {
		    $this->error_array['SITE CONFIG INI - CLIENT NAME EMPTY'] = 'Client Name can not be empty';
	    }
	    
	    //site email address
	    if(empty($site_a['SITE_EMAIL_ADD']))
	    {
		    $this->error_array['SITE CONFIG INI - SITE EMAIL ADD EMPTY'] = 'Site Email Address can not be empty';
	    }
	    
	    //site email name
	    if(empty($site_a['SITE_EMAIL_NAME']))
	    {
		    $this->error_array['SITE CONFIG INI - SITE EMAIL NAME EMPTY'] = 'Site Email Name can not be empty';
	    }
	    
	    //site email subject 
	    if(empty($site_a['SITE_EMAIL_SUBJECT']))
	    {
		    $this->error_array['SITE CONFIG INI - SITE EMAIL SUBJECT EMPTY'] = 'Site Email Subject can not be empty';
	    }
	    
	    //default link
	    if(empty($site_a['DEFAULT_LINK']))
	    {
		    $this->error_array['SITE CONFIG INI - DEFAULT LINK EMPTY'] = 'Default link can not be empty';
	    }

	    return ;
    }
    
    /**
     * _checkObj function.
     * 
     * This function checks the system integrity. A specific site can have many groups
     * but it must always have these groups defined because they are used in the core.
     *
     * Check if all the site group config that require by system is all required in .ini file
     * @access private
     * @return void
     */
    private function _site_group_config_checkObj()
    {
	    $site_group_config_required = DIR_APP_INI.'/site_group_config_required.ini';
	    
    	//load the site ini file  
	    if ($site_group_config_required) {
		    
	    	$groups_required = parse_ini_file($site_group_config_required);
	    	   
	    } else {
		    
		    $this->error_array['NO SITE GROUP CONFIG INI'] = 'The INI file site_group_config_required can not be found in app ini!';
	    	return; 
	    }
	    
	    $site_group_config_file_local = DIR_SECURE_INI.'/site_group_config.ini';
    	
	    //load the site ini file 
	    if(is_file($site_group_config_file_local))
	    {
		    
	    	$groupConfigArray = parse_ini_file($site_group_config_file_local,true); 
	    	    
	    } else {
		    
	    	$this->error_array['NO LOCAL SITE GROUP CONFIG INI'] = 'The INI file site_group_config can not be found in app ini!';
	    	return; 
	    	
	    }
	    
	    //run checks
	    foreach($groupConfigArray as $key => $value)
	    {
		    if(!isset($value['title'])) $this->error_array["LOCAL SITE GROUP CONFIG $key - TITLE"] = "$key - is missing the title directive!";
		    if(!isset($value['desc'])) $this->error_array["LOCAL SITE GROUP CONFIG $key - DESC"] = "$key - is missing the desc directive!";
		    if(!isset($value['members'])) $this->error_array["LOCAL SITE GROUP CONFIG $key - MEMBERS"] = "$key - is missing the members directive!";
		    unset($groups_required[$key]);
	    }
	    
	    foreach($groups_required as $key => $value)
	    {
		    $this->error_array["LOCAL SITE GROUP CONFIG $key"] = "$key - is a group that needs to be defined!";
	    }
	    
	    return;
    }

    /**
     * _checkObj function.
     * 
     * Checking to make sure that all the security levels defined by modules are defined in this object.
     * If not then we can not go any further.
     *
     * @access private
     * @param array $security_required
     * @return void
     */
    private function _security_level_checkObj($security_required)
    {
    	$defined_security = array_keys($this->_securityLevelIdArray);
    	
    	foreach( $security_required as $required)
    	{
	    	if(!in_array($required, $defined_security))
	    	{
	    		$defined = implode(',', $defined_security);
	    		
		    	$msg = "You have not defined all the security levels needed ($required)!";
		    	throw new \RuntimeException($msg);
	    	}
	    }
	    return;
    }

}
?>