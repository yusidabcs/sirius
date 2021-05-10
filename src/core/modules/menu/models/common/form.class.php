<?php
namespace core\modules\menu\models\common;

/**
 * Final form class.
 * 
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 * @final
 */
final class form {
	
	private $_depth; // integer used in spacer to tell the depth of the array
	
	private $_system_register;
	private $_site_modules_required_info;

	public function __construct()
	{
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$this->_system_register = $system_register_ns::getInstance() ;
		
		//load the site_modules_required to get all the setup things - like noRedirect
		$site_modules_required_file = DIR_APP_INI.'/site_module_config_required.ini';
		
	    if (is_file($site_modules_required_file)) {
		    
	    	$this->_site_modules_required_info = parse_ini_file($site_modules_required_file,true);  
	    	   
	    } else {
	    	$msg = 'The site module config required ini file can not be found!';
	    	throw new \RuntimeException($msg); 
	    }	
		return;
	}
	
	/**
	 * getAllowRedirect function.
	 * 
	 * Find out if we allow a redirect on this module type
	 *
	 * @access public
	 * @param mixed $module_id
	 * @return void
	 */
	public function getAllowRedirect($module_id)
	{
		$out = true;
		
		if(isset($this->_site_modules_required_info[$module_id]) && $this->_site_modules_required_info[$module_id]['noRedirect'] == 1)
		{
			$out = false;
		}
		
		return $out;
	}
	
	/**
	 * makeTemplateOptionArray function.
	 * 
	 * @access public
	 * @param string $module_id (default: '')
	 * @param string $template_id (default: '')
	 * @return void
	 */
	public function makeTemplateOptionArray($module_id = '',$template_id = '')
	{
		$out = array();
	    if( empty($template_id) || (isset($this->_site_modules_required_info[$module_id]) && $this->_site_modules_required_info[$module_id]['changeTemplate'] == 1) ) 
	    {
		    $fp_a = glob( DIR_PAGEVIEWS.'/*' , GLOB_ONLYDIR);
		    foreach($fp_a as $fp)
			{
				$dir = basename($fp);
				$out[$dir] = $dir;
			}
			
	    } else {
		    $out[$template_id] = $template_id;
	    }
		ksort($out);
		return $out;
	}
	
	/**
	 * makeModuleOptionArray function.
	 * 
	 * @access public
	 * @param string $module (default: '')
	 * @return void
	 */
	public function makeModuleOptionArray($module = '')
	{
		$moduleOptions = $this->_system_register->getModuleActiveArray();
		$out = array();
		
		if(empty($module))
		{
			//return the modules that are visable only
			foreach($moduleOptions as $name => $value)
			{
				if( isset($value['visible']) &&  $value['visible'] == 1)
				{
					if( isset($value['allow_multiple']) &&  $value['allow_multiple'] == 1 )
					{
						$out[$name] = $name ;
					} else {
						
						//is it being used - if not add it
						if(!$this->_moduleIsUsed($name))
						{
							$out[$name] = $name;
						}
					}
				}
			}
			ksort($out);
						
		} else if(array_key_exists($module, $moduleOptions)) {
			
			$out[$module] = $module;
			
		} else {
			die('The module given to menu form is not in the module array!');
		}
		
		return $out;
	}
	
	/**
	 * _moduleIsUsed function.
	 * 
	 * @access private
	 * @param mixed $moduleName
	 * @return void
	 */
	private function _moduleIsUsed($moduleName)
	{	
		$menu_db_ns =  NS_MODULES.'\\menu\\models\\common\\db';
		$menu_db = $menu_db_ns::getInstance();
		$out = $menu_db->moduleIsUsed($moduleName);
		return $out;
	}
	
	/**
	 * makeGroupOptionArray function.
	 * 
	 * @access public
	 * @param string $module_id (default: '')
	 * @param string $group_id (default: '')
	 * @return void
	 */
	public function makeGroupOptionArray($module_id = '',$group_id = '')
	{
		$groupOptions = $this->_system_register->getGroupArray();
		
		if( empty($group_id) || (isset($this->_site_modules_required_info[$module_id]) && $this->_site_modules_required_info[$module_id]['changeGroup'] == 1 ) ) 
		{
		    foreach($groupOptions as $name => $value)
			{
				$out[$name] = array( 'name' => $name, 'title' => $value['title'] );
			}
	    } else {
		    $out[$group_id] = array( 'name' => $group_id, 'title' => $groupOptions[$group_id]['title'] );
	    }
		ksort($out);
		return $out;
	}
	
	/**
	 * makeSecurityOptionArray function.
	 * 
	 * @access public
	 * @param string $module_id (default: '')
	 * @param string $security_level_id (default: '')
	 * @return void
	 */
	public function makeSecurityOptionArray($module_id = '',$security_level_id = '')
	{
		$secuirtyOptions = $this->_system_register->getSecurityArray();
		
		if(empty($security_level_id) || (isset($this->_site_modules_required_info[$module_id]) && $this->_site_modules_required_info[$module_id]['changeSecurityLevel'] == 1 )) 
		{
		    foreach($secuirtyOptions as $name => $value)
			{
				$out[$value['level']] = array( 'name' => $name, 'title' => $value['title'] );
			}
	    } else {
		    $out[$security_level_id] = array( 'name' => $security_level_id, 'title' => $secuirtyOptions[$security_level_id]['title'] );
	    }
		ksort($out);
		return $out;
	}
	
	/**
	 * makeParentOptionArray function.
	 * 
	 * This does the TOP level only then _makeParentOptArray does teh rest of the levels
	 *
	 * @access public
	 * @param string $link_id (default: '')
	 * @return void
	 */
	public function makeParentOptionArray($link_id = '')
	{
		//clear any existing
		$this->_parentOptArray = array();
		
		//get the full menu array
		$menu_common_ns = NS_MODULES.'\\menu\\models\\common\\common';
		$menu_common = $menu_common_ns::getInstance();
		
		$fullMenu = $menu_common->getMenuListArray();
		
		//an array of all the link_ids that could be children
		foreach($fullMenu as $id => $value)
		{
			$children[] = $value['link_id'];
		}
		
		if(isset($children))
		{
			//so if this link_id is in the children then this is the parent
			$selected = in_array($link_id, $children) ? 1 : 0;
		} else {
			$selected = 1;
		}
		
		//set the MenuRoot
		$this->_parentOptArray[] = array('spacer' => '','link_id' => 'menuRoot','menu_title' => 'TOP','selected' => $selected);
		
		$this->_makeParentOptArray($fullMenu,1,$link_id);
		
		return $this->_parentOptArray;
	}
	
	/**
	 * _makeParentOptArray function.
	 * 
	 * @access private
	 * @param mixed $current_array
	 * @param mixed $depth
	 * @param mixed $link_id
	 * @return void
	 */
	private function _makeParentOptArray($current_array,$depth,$link_id)
	{
		//set spacer
		$spacer = $this->_spacer($depth,'--');
		$spacer .= '|';
		
		foreach($current_array as $id => $value)
		{
			//I can not be my own parent
			if($value['link_id'] == $link_id) continue;
		
			$children_array = $value['children'];
			
			//this loops so you need to clear the child links each loop!
			unset($child_links);
				
			//no place to go if we don't have children
			if(!empty($children_array))
			{			
				//put the children in an array
				foreach($children_array as $id => $child)
				{
					$child_links[] = $child['link_id'];
				}
	
				//check this one is the parent of the link_id
				$selected = in_array($link_id, $child_links) ? 1 :0;
				$this->_parentOptArray[] = array('spacer' => $spacer,'link_id' => $value['link_id'],'menu_title' => $value['menu_title'],'selected' => $selected);
				
				$depth++;
				$this->_makeParentOptArray($children_array,$depth,$link_id);
				
			} else {
				//no children therefore can not be a parent
				$this->_parentOptArray[] = array('spacer' => $spacer,'link_id' => $value['link_id'],'menu_title' => $value['menu_title'],'selected' => 0);
			}
		}
		
		return;
	}
	
	/**
	 * _spacer function.
	 * 
	 * @access private
	 * @param mixed $depth
	 * @param string $s (default: "  ")
	 * @param mixed $x (default: null)
	 * @return void
	 */
	private function _spacer($depth,$s="  ",$x=null)
	{
		settype($depth,"integer");
		settype($s,"string");
		settype($x,"integer");
		return str_repeat($s,$depth+$x);
	}

}
?>