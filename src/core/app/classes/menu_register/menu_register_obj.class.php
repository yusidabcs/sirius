<?php
namespace core\app\classes\menu_register;

/**
 * Final menu_register_obj class.
 * 
 * This is the actual menu register object itself.  
 *
 * @final
 * @package 	menu_register
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 13 August 2019
 */
final class menu_register_obj {

	private $_menu_data = array(); //stores the full menu details
	private $_link_detail = array(); //stores all the link_ids as keys and various values for security
	
	private $_clipped_menu = array(); //is used to buile the menu arrays
	private $_treed_menu = array(); //is used to buile the menu arrays
	private $_activated_menu = array(); //is used to buile the menu arrays
	
    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        //every it called, it will call _loadData methods
	    $this->_loadData(); 
        return;
    }
    
    /**
     * _loadData function.
     * 
     * Loads the core information from the ini files and session information
     *
     * @access private
     * @return void
     */
    private function _loadData()
    {
	    /**
		 * Make sure that the ini file is built in sequence, title order!
		**/
	    $site_menu_file_local = DIR_SECURE_INI.'/site_menu.ini';
    	$site_menu_file_original = DIR_APP_INI.'/site_menu_required.ini';
	    
	    //load the site menu file
	    if(is_file($site_menu_file_local))
	    {
		    
	    	$this->_menu_data = parse_ini_file($site_menu_file_local,true); 
	    	    
	    } elseif (is_file($site_menu_file_original)) {
		    
	    	$this->_menu_data = parse_ini_file($site_menu_file_original,true);
	    	   
	    } else {
		    
	    	$msg = 'The MENU file can not be found anywhere!';
	    	throw new \RuntimeException($msg); 	
	    }
	    
	    foreach( $this->_menu_data as $key => $value)
	    {
	    	$this->_link_detail[ $value['link_id'] ] = array(
		    												'module_id' => $value['module_id'],
												    		'security_level_id' => $value['security_level_id'],
												    		'group_id' => $value['group_id'],
												    		'title_page' => $value['title_page'],
												    		'template_name' => $value['template_name']
											    	);
	    }
	
        return;
    }
    
    //!Public functions
        
    /**
     * checkLink function.
     * 
     * Checks if the requested link is real and if not it redirects to our 404 page
     *
     * @access public
     * @return void
     */
    public function checkLink($link)
    {
	    if(!array_key_exists($link, $this->_link_detail))
	    {
		    $html_ns = NS_HTML.'\\htmlpage';
	    	$htmlpage = new $html_ns(404);
			exit();
	    }
	    	    
	    return true;
    }
    
	/**
	 * getModuleLink function.
	 * 
	 * Will give back the link of the given module
	 *
	 * @access public
	 * @param mixed $module
	 * @return void
	 */
	public function getModuleLink($module)
    {
	    $link = false;
	    
	    foreach($this->_link_detail as $link_id => $value)
	    {
		    if($value['module_id'] == $module) $link = $link_id;
	    }
	    
	    // if($link === false)
	    // {
		//     $msg = 'The link for '.$module.' can not be found.';
		// 	throw new \RuntimeException($msg);
		// 	exit();
	    // }
	    
        return $link;
    }
     
    /**
     * linkSecurityAll function.
     * check if the link is has security level = none / all so it can accessed by all user
     * @access public
     * @param mixed $link
     * @return void
     */
    public function linkSecurityAll($link)
    {
	    $out = false;
	    
	    if($this->_link_detail[$link]['security_level_id'] == 'NONE' && $this->_link_detail[$link]['group_id'] == 'ALL')
	    {
		    $out = true;   
	    }
	    
	    return $out;
    }
    
    /**
     * getLinkSecurityLevelId function.
     *
     * to get the security level of the link
     *
     * @access public
     * @param mixed $link
     * @return void
     */
    public function getLinkSecurityLevelId($link)
    {
		return $this->_link_detail[$link]['security_level_id'];
    }
    
    /**
     * getLinkGroupId function.
     * 
     * @access public
     * @param mixed $link
     * @return void
     */
    public function getLinkGroupId($link)
    {
		return $this->_link_detail[$link]['group_id'];
    }
    
    /**
     * getModuleId function.
     * get the module id of the link
     * @access public
     * @param mixed $link
     * @return void
     */
    public function getModuleId($link)
    {
	    $module = false;
	    
	    foreach($this->_link_detail as $link_id => $value)
	    {
		    if($link_id == $link) $module = $value['module_id'];
	    }
	    
	    if($module === false)
	    {
		    $msg = 'The module for '.$link.' can not be found.';
			throw new \RuntimeException($msg);
			exit();
	    }
	    
	    return $module;
    }

    /**
     * getPageTitle function.
     * 
     * Gets the site page title - used in module_model
     *
     * @access public
     * @param mixed $this->link_id
     * @return void
     */
    public function getPageTitle($link)
    {
	    return $this->_link_detail[$link]['title_page'];
    }
    
    /**
     * getTemplateName function.
     * 
     * @access public
     * @param mixed $link
     * @return void
     */
    public function getTemplateName($link)
    {
		return $this->_link_detail[$link]['template_name'];   
    }
    
    public function getDefaultableLinks()
    {
	    $out = array();
	    
	    foreach($this->_link_detail as $link => $value)
	    {
		    if($value['security_level_id'] == 'NONE' && $value['group_id'] == 'ALL')
		    {
			    $out[] = $link;
		    }
	    }
	    
	    return $out;
    }
    
        //!Get Main Menu or Links Array   
    
    /**
     * getMenuArray function.
     * 
     * @access public
     * @param mixed $currentLinkId
     * @param mixed $menu_section
     * @return void
     */
    public function getMenuArray($link,$menu)
    {
	    //clear _clipped_menu
	    $this->_clippped_menu = array();
	    
		//if currentLinkId is empty there is a problem!
    	if(empty($link))
    	{
	    	$msg = 'There is no current link for menu!';
			throw new \RuntimeException($msg);
			exit();
    	}
    	
		//build _clipped_menu
		$this->_clipMenu($menu);
		//set active)
    	$this->_setActiveMenu($link);
    	//setting based on section
    	switch ($menu) 
		{
		    case 'main':
		    	$menu_array = $this->_treed_menu = $this->_treeMenu('menuRoot', $menu);
		        break;
		        
		    case 'quick':
		    	$menu_array = $this->_clipped_menu;
		        break;
		        
		    case 'bottom':
		    	$menu_array = $this->_treed_menu = $this->_treeMenu('menuRoot', $menu);
		        break;
		        
		    default:
		    	$msg = 'Bad section id for menu!';
				throw new \RuntimeException($msg);
				exit();
		}
		//return $menu_array;
		return $this->_checkPermission($menu_array);
	}
	
	/**
	 * _clipMenu function.
	 * 
	 * @access private
	 * @param mixed $menu_section
	 * @return void
	 */
	private function _clipMenu($menu)
	{
		$permissions = [];

		//process
		$count = 0;
		foreach($this->_menu_data as $key => $value)
		{
			//this mean they not show in main, bottom or quick menu
			if ($value['main_link'] == 0 && $value['bottom_link'] == 0 && $value['quick_link'] == 0) {
				continue;
			}
			//if this menu is public
			if( empty($_SESSION['user_id'])){
				if( $value['security_level_id'] == 'NONE')
				{
					if($menu === 'main' && $value['main_link'] == 1 )
					{
						$this->_clipped_menu[$menu][$count]['link_id'] = $value['link_id'];
						$this->_clipped_menu[$menu][$count]['parent_id'] = $value['parent_id'];
						$this->_clipped_menu[$menu][$count]['redirect_url'] = $value['redirect_url'];
						$this->_clipped_menu[$menu][$count]['title_menu'] = $value['title_menu'];
						$this->_clipped_menu[$menu][$count]['active'] = 0;
						$this->_clipped_menu[$menu][$count]['group_id'] = $value['group_id'];
						$this->_clipped_menu[$menu][$count]['security_level_id'] = $value['security_level_id'];

						$count++;
					} 
					else if($menu === 'bottom' && $value['bottom_link'] == 1) {
						$this->_clipped_menu[$menu][$count]['link_id'] = $value['link_id'];
						$this->_clipped_menu[$menu][$count]['parent_id'] = $value['parent_id'];
						$this->_clipped_menu[$menu][$count]['redirect_url'] = $value['redirect_url'];
						$this->_clipped_menu[$menu][$count]['title_menu'] = $value['title_menu'];
						$this->_clipped_menu[$menu][$count]['active'] = 0;
						$this->_clipped_menu[$menu][$count]['group_id'] = $value['group_id'];
						$this->_clipped_menu[$menu][$count]['module_id'] = $value['module_id'];
						$count++;
					} else {
						$this->_clipped_menu[$value['link_id']] = $value['redirect_url'];
						$this->_clipped_menu[$value['link_id']] = $value['title_menu'];
						$this->_clipped_menu[$value['link_id']] = 0;
					}
				}
			}else{
				if ($menu === 'main' && $value['main_link'] == 1) {
					$this->_clipped_menu[$menu][$count]['link_id'] = $value['link_id'];
					$this->_clipped_menu[$menu][$count]['parent_id'] = $value['parent_id'];
					$this->_clipped_menu[$menu][$count]['redirect_url'] = $value['redirect_url'];
					$this->_clipped_menu[$menu][$count]['title_menu'] = $value['title_menu'];
					$this->_clipped_menu[$menu][$count]['active'] = 0;
					$this->_clipped_menu[$menu][$count]['group_id'] = $value['group_id'];
					$this->_clipped_menu[$menu][$count]['module_id'] = $value['module_id'];
					$this->_clipped_menu[$menu][$count]['security_level_id'] = $value['security_level_id'];
					$count++;
				} 
				else if($menu === 'bottom' && $value['bottom_link'] == 1) {
					$this->_clipped_menu[$menu][$count]['link_id'] = $value['link_id'];
					$this->_clipped_menu[$menu][$count]['parent_id'] = $value['parent_id'];
					$this->_clipped_menu[$menu][$count]['redirect_url'] = $value['redirect_url'];
					$this->_clipped_menu[$menu][$count]['title_menu'] = $value['title_menu'];
					$this->_clipped_menu[$menu][$count]['active'] = 0;
					$this->_clipped_menu[$menu][$count]['group_id'] = $value['group_id'];
					$this->_clipped_menu[$menu][$count]['module_id'] = $value['module_id'];
					$count++;
				}
				else {
					$this->_clipped_menu[$menu][$value['link_id']] = $value['redirect_url'];
					$this->_clipped_menu[$menu][$value['link_id']] = $value['title_menu'];
					$this->_clipped_menu[$menu][$value['link_id']] = 0;
				}
			}
		}
		return;
	}

	private function _checkPermission($menu){
		if (isset($_SESSION['user_id'])) {
			$permissions = $_SESSION['permissions'];
			$final_menu = [];
			foreach($menu as $index => $item){
				$module_ini_a = @parse_ini_file(DIR_MODULES.'/'.$item['module_id'].'/module.ini',true);
				$key = $item['module_id'] . '.' . $module_ini_a['config']['defaultModel'] . '.index';
				if( (isset($permissions[$key]) && $permissions[$key] === 'allow'  )){

					if(count($item['children']) > 0){
						$childs = [];
						foreach($item['children'] as $index_child => $item_child){
							
							$module_ini_a_child = @parse_ini_file(DIR_MODULES.'/'.$item_child['module_id'].'/module.ini',true);
							$key_child = $item_child['module_id'] . '.' . $module_ini_a_child['config']['defaultModel'] . '.index';
							
							if( (isset($permissions[$key_child]) && $permissions[$key_child] === 'allow'  )){
								
								$childs[] = $item_child;			

							}
						}

						if(count($childs) > 0){
							$item['children'] = $childs;
							$final_menu[] = $item;
						}
					}else{
						$final_menu[] = $item;
					}
					
				}
			
			}
			return $final_menu;
		}else{
			return $menu;
		}
	}
	
	/**
	 * _setActiveMenu function.
	 * 
	 * @access private
	 * @param mixed $link
	 * @return void
	 */
	private function _setActiveMenu($link)
    {	
    	foreach($this->_clipped_menu as $key => $value)
    	{
			if(!is_array($value));
				continue;
			foreach($value as $key2 => $menu)
    		{
				//do this one
				
				if(($menu['link_id'] == $link))
				{
					$this->_clipped_menu[$key][$key2]['active'] = 1;
			  
					//do the parent
					if($this->_clipped_menu[$key][$key2]['parent_id'] != 'menuRoot')
					{
						$this->_setActiveMenu($this->_clipped_menu[$key][$key2]['parent_id']);
					}
					
					break;
				}
			}
	    	
    	}
    		
    	return;
    }
 
    /**
     * _getMainMenuItems function.
     * 
     * @access private
     * @param string $level starting at menuRoot each level is defined by the parent menu title
     * @return array items at that level
     */
    private function _treeMenu($level,$link)
    {	
	    //must define it because it might be blank   
	    $level_array = array();
	    $count = 0;
	    
	    foreach($this->_clipped_menu[$link] as $key => $value )
	    {
	    	if($value['parent_id'] == $level)
	    	{
		    	$level_array[$count] = $value;
		    	unset($this->_clipped_menu[$key]);
		    	$level_array[$count]['children'] = $this->_treeMenu($value['link_id'],$link);
		    	$count++;    	
	    	}
	    }
	    
	    return $level_array;
    }
    
}
?>