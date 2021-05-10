<?php
namespace core\app\classes\system_register\group_config;

/**
 * Final group_config class.
 * 
 * The class that holds all the groups within the system_register and uses
 * group_config_obj for each actual group
 *
 *	site_group_config:
 *		[ALL]
 *			title = 'All Users'
 *			desc = 'A group to which all users belong'
 *			members = 'ALL'
 *		[IOW]
 *			title = 'IOW Administrators'
 *			desc = 'A group to which only IOW Administrators belong'
 *			members = 'NONE'
 *
 * @final
 * @package 	system_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 12 August 2019
 */
final class group_config {
	
	private $_groupConfigArray; //array holding the group config
	private $_permitted_group_array = array();
	
    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {	
    	$this->_loadSiteGroupIni();

	    return;
    }
    
    private function _loadSiteGroupIni()
    {
	    $site_group_config_file_local = DIR_SECURE_INI.'/site_group_config.ini';
    	$site_group_config_file_original = DIR_APP_INI.'/site_group_config.ini';
    	
	    //load the site ini file 
	    if(is_file($site_group_config_file_local))
	    {
		    
	    	$this->_groupConfigArray = parse_ini_file($site_group_config_file_local,true); 
	    	    
	    } elseif (is_file($site_group_config_file_original)) {
		    
	    	$this->_groupConfigArray = parse_ini_file($site_group_config_file_original,true); 
	    	    
	    } else {
		    
	    	$msg = 'The INI file site_group_config can not be found anywhere!';
	    	throw new \RuntimeException($msg);
	    	
	    }

	    return;
    }
	
    public function getGroupArray()
    {
	    return $this->_groupConfigArray;
    }
    
    public function getGroups($group_id)
    {
	    return explode('|', $this->_groupConfigArray[$group_id]['members']);
    }
    
    public function getGroupTitle($group_id)
    {
	    return $this->_groupConfigArray[$group_id]['title'];
    }
    
    public function permittedGroupArray()
	{
		//build it only if it is empty
		if(empty($this->_permitted_group_array))
		{
			foreach ($this->_groupConfigArray as $group => $value)
			{
				if($_SESSION['user_security_level'] == 100 && $value['members'] == 'NONE')
				{
					$this->_permitted_group_array[] = $group;
				} else if($value['members'] != 'NONE') {
					$this->_permitted_group_array[] = $group;
				}
			}
		}
		
		return $this->_permitted_group_array;
	}
}
?>