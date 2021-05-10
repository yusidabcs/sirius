<?php
namespace core\modules\menu\models\common;

/**
 * Final menu_common class.
 *
 * @final
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */
final class common_obj {

	private $_menu_db;
	
	public function __construct()
	{
		$menu_db_ns = NS_MODULES.'\\menu\\models\\common\\db';
		$this->_menu_db = $menu_db_ns::getInstance();
		return;
	}

	//!Menu Setup Functions
	
	/**
	 * moveIni2Db function.
	 * 
	 * The first time we run home if it sees an empty menu it will load the default one
	 *
	 * @access public
	 * @return void
	 */
	public function moveIni2Db()
	{
		if( !$this->_menu_db->getAllMenuItems() )
		{	
			$out = "<p style=\"color:red\">RUNNING UPDATE TO MENU</p>";
			
			//read in the menu.ini file
		    if(is_file(DIR_APP_INI.'/site_menu_required.ini'))
		    {
		    	$menu_a = parse_ini_file(DIR_APP_INI.'/site_menu_required.ini',true);     
		    } else {
		    	$msg = 'The site menu required INI file can not be found!';
		    	throw new \RuntimeException($msg); 
		    }
		    
		    $sitemap = 0; //secure, admin and menu are not in the sitemap anyway
		    
		    foreach($menu_a as $key => $value)
		    {	
			    //adds the page to the menu    
			    $this->_menu_db->addMenuItem($value['link_id'],$value['redirect_url'],$value['title_menu'],$value['title_page'],$value['template_name'],$value['module_id'],$value['security_level_id'],$value['group_id'],$sitemap,1);
			    //adds the actual menu links (technically it can be linked in more than one spot)
			    $this->_menu_db->addMenuLink($value['link_id'],$value['parent_id'],$value['sequence_no'],$value['main_link'],$value['quick_link'],$value['bottom_link']);
		    }
		    
		    $this->_menu_db->updateSitemapStatus();
		    
		    $out .= "<p>Menu updated from ini and sitemap status updated</p>";
		    
		} else {
			$out = "<p>Menu DB has items in it - no further action required</p>";
		}

		return $out;
	}
	
	//!Home
	
	/**
	 * getMenuListArray function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getMenuListArray()
    {   
	    $this->full_menu_array = $this->_menu_db->getAllMenuItems();
	    
	    $f_menu = $this->_getMenuList('menuRoot');
        return $f_menu;
	}
	
	/**
	 * getMenuListArray function.
	 * 
	 * @access public
	 * @return void
	 */
    public function getFrontendMenuListArray()
    {
    	return $this->_menu_db->getFrontendMenuItems();
	}
	
	/**
	 * getMenuItem
	 * 
	 * @access public
	 * @return void
	 */
	public function getMenuItem($link_id)
	{
		return $this->_menu_db->getMenuItem($link_id);
	}
    
    /**
     * _getMenuList function.
     * 
     * @access private
     * @param mixed $level_id
     * @return void
     */
    private function _getMenuList($level_id)
    {
    	$level_array = array();
    		
	    foreach($this->full_menu_array as $id => $menu_item )
	    { 
	    	if($menu_item['parent_id'] == $level_id)
	    	{
		    	$level_array[$id]['link_id'] = $menu_item['link_id'];
		    	$level_array[$id]['menu_title'] = $menu_item['title_menu'];
		    	$level_array[$id]['module_id'] = $menu_item['module_id'];
		    	$level_array[$id]['redirect_url'] = $menu_item['redirect_url'];
		    	$level_array[$id]['security_level_id'] = $menu_item['security_level_id'];
		    	$level_array[$id]['group_id'] = $menu_item['group_id'];
		    	$level_array[$id]['main_link'] = $menu_item['main_link'];
		    	$level_array[$id]['quick_link'] = $menu_item['quick_link'];
		    	$level_array[$id]['bottom_link'] = $menu_item['bottom_link'];
		    	$level_array[$id]['sitemap'] = $menu_item['sitemap'];
		    	$level_array[$id]['status'] = $menu_item['status'];
		    	$level_array[$id]['sequence_no'] = $menu_item['sequence_no'];
		    	
		    	//ok take this one out
		    	unset($this->full_menu_array[$id]);
		    	
		    	//now check for children
		    	$level_array[$id]['children'] = $this->_getMenuList($menu_item['link_id']);
	    	}
	    }
	    return $level_array;
    }

	//!add
	
    /**
     * addMenuItem function.
     * 
     * @access public
     * @param mixed $link_id
     * @param mixed $parent_id
     * @param mixed $redirect_url
     * @param mixed $sequence_no
     * @param mixed $title_menu
     * @param mixed $title_page
     * @param mixed $template_name
     * @param mixed $module_id
     * @param mixed $security_level_id
     * @param mixed $group_id
     * @param mixed $main_link
     * @param mixed $quick_link
     * @param mixed $bottom_link
     * @param mixed $sitemap
     * @param mixed $status
     * @return void
     */
    public function addMenuItem($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$main_link,$quick_link,$bottom_link,$sitemap,$status)
    {
	    //adds the item to the menu - techncially this can happen without it being added to the menu tree
    	$this->_menu_db->addMenuItem($link_id,$redirect_url,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$sitemap,$status);
    	//adds the menu item to the actual menu tree - technically the item can be in two places on the menu    
		$this->_menu_db->addMenuLink($link_id,$parent_id,$sequence_no,$main_link,$quick_link,$bottom_link);	
		return;
    }
    
    //!edit

    /**
     * updateLinkIdOnSystem function.
     * 
     * @access public
     * @param mixed $link_id_orig
     * @param mixed $link_id
     * @return void
     */
    public function updateLinkIdOnSystem($link_id_orig,$link_id)
    {
	    //run the models admin update for menu links in admin
	    $module_id = $this->_menu_db->getModuleFromLinkId($link_id_orig);
	    
	    if(!empty($module_id))
	    {  
		    //set the admin delete object
			$model_name = NS_MODULES.'\\'.$module_id.'\\admin\\admin';
			$admin_obj = new $model_name();
			
			//update the link id in the module if needed
	    	$admin_obj->updateMenuLink($link_id, $link_id_orig);
	    	
	    } else {
		    $msg = 'Can not update Link Id on System in menu object as we can not find a module id!';
		    throw new \RuntimeException($msg); 
	    }
	    
	    //get file_manager to fix up all the links
	    $file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
	    $file_manager = $file_manager_ns::getInstance();
		$file_manager->updateLinkIds($link_id,$link_id_orig);
		
		//now update the menu
		$this->_menu_db->updateMenuLinkId($link_id_orig,$link_id);
		$this->_menu_db->updateMenuTreeLinkId($link_id_orig,$link_id);
		$this->_menu_db->updateMenuTreeParentIds($link_id_orig,$link_id);
		
		return;
    }
    
    /**
     * updateMenuItem function.
     * 
     * @access public
     * @param mixed $link_id
     * @param mixed $parent_id
     * @param mixed $redirect_url
     * @param mixed $sequence_no
     * @param mixed $title_menu
     * @param mixed $title_page
     * @param mixed $template_name
     * @param mixed $module_id
     * @param mixed $security_level_id
     * @param mixed $group_id
     * @param mixed $main_link
     * @param mixed $quick_link
     * @param mixed $bottom_link
     * @param mixed $sitemap
     * @param mixed $status
     * @param mixed $parent_id_orig
     * @return void
     */
    public function updateMenuItem($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$main_link,$quick_link,$bottom_link,$sitemap,$status,$parent_id_orig)
    {
	    //this will up the parent id if required
		if($parent_id != $parent_id_orig) 
		{
			$this->_menu_db->updateMenuLinkParentId($link_id,$parent_id,$parent_id_orig);
		}
	    
    	$this->_menu_db->updateMenuItem($link_id,$redirect_url,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$sitemap,$status);	    
		$this->_menu_db->updateMenuLink($link_id,$parent_id,$sequence_no,$main_link,$quick_link,$bottom_link);
			
		return;
    }
    
    //!Update	
	
	public function updateMenuIni()
	{
		$site_menu_file = DIR_SECURE_INI.'/site_menu.ini';
		$menu_array = $this->_menu_db->getBuildMenuItems();
		
		//touch the file if it is not there
		if(!is_file($site_menu_file))
		{
			if(!touch($site_menu_file))
			{
				$msg = 'The secure ini directory is not writable!';
				throw new \RuntimeException($msg);
				exit();
			}
		}
		
		//normal process .. it should be writable and the array should be empty
		if(is_writable($site_menu_file) && !empty($menu_array))
		{
			//write the ini file
			$write_ini_ns = NS_APP_CLASSES.'\\ini\\write_ini';
			$write_ini = new $write_ini_ns();
			$write_ini->write_php_ini($menu_array, $site_menu_file);
			
			//delete the caches
			system('rm '.DIR_SECURE_CACHE.'/*',$retval);
	
		} else {
			$msg = 'Either the site_menu.ini file is not writable or the menu array is empty!';
		    throw new \RuntimeException($msg); 
		}
		
		return;
	}
	
	/**
	 * updateSitemapFiles function.
	 * 
	 * @access public
	 * @return void
	 */
	public function updateSitemapFiles()
	{
		//put the home url in
		$sitemap_a[] =  HTTP_TYPE.SITE_WWW;
		
		//get all the link_id's and put them in also
		foreach($this->_menu_db->getSiteMapURLs() as $link_id)
		{
			$sitemap_a[] = HTTP_TYPE.SITE_WWW.'/'.$link_id;
		}
		
		//set the file
		$sitemapTxtFile = DIR_BASE.'/sitemaps/sitemap.txt';
		$sitemapXmlFile = DIR_BASE.'/sitemaps/sitemap.xml';
		$sitemapGzxFile = DIR_BASE.'/sitemaps/sitemap.gz'; //gzip of the xml file

		//make txt and xml data
		$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
		$xml .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
		
		$txt = '';
		
		foreach ($sitemap_a as $url)
		{
			$xml .= '  <url>'."\r\n";
			$xml .= '    <loc>'.$url.'</loc>'."\r\n";
			$xml .= '  </url>'."\r\n";
			
			$txt .= $url."\r\n";
		}
		
		$xml .= '</urlset>';
		
		//write the txt file
		if(!empty($txt))
		{
			$txthandle = fopen($sitemapTxtFile, 'w+');
			fwrite($txthandle,$txt);
			fclose($txthandle);
		}
		
		//write the xml file
		if(!empty($xml))
		{
			//plain
			$xmlhandle = fopen($sitemapXmlFile, 'w+');
			fwrite($xmlhandle,$xml);
			fclose($xmlhandle);
			
			//xml
			$gz = gzopen($sitemapGzxFile,'w9');
			gzwrite($gz, $xml);
			gzclose($gz);
		}
		
		//update the ts for the sitemap date file
		$sitemap_date = DIR_BASE.'/sitemaps/sitemap.date';
		file_put_contents($sitemap_date, time());

		$out = 'Sitemaps rebuilt';
		
		return $out;
	}
		
	//!Submit

	/**
	 * submitSitemap function.
	 * 
	 * Used in daily.php to submit the maps if allowed and required
	 *
	 * @access public
	 * @return void
	 */
	public function submitSitemap()
    {
    	//sitemap date
		$sitemap_date = DIR_BASE.'/sitemaps/sitemap.date';
		if(file_exists($sitemap_date))
		{
			$sitemap_ts = file_get_contents($sitemap_date);
		} else {
			$sitemap_ts = time();
			file_put_contents($sitemap_date, $sitemap_ts);
		}
		
		//sumit date
		$submit_date = DIR_BASE.'/sitemaps/submit.date';
		if(file_exists($submit_date))
		{
			$submit_ts = file_get_contents($submit_date);
		} else {
			$submit_ts = 1;
		}
			
		//see if we need to update the various search engines
		if($sitemap_ts > $submit_ts)
		{
			$log = '<h4>Submitting Sitemap</h4>'."\n";
			
			//submit to search engines
			$sitemap_url = HTTP_TYPE.SITE_WWW.'/sitemap.gz';
			
			//google
			$log .= '<h5>Google</h5>'."\n";
			$log .= '<p>'."\n";
			
			$pingurl = "https://www.google.com/webmasters/sitemaps/ping?sitemap=" . urlencode($sitemap_url);
			$fp = fopen($pingurl, "r");
			$data = fread ($fp, 2048);
			fclose($fp);
			$result = preg_replace("/.*<h2[^>]*>|<\/h2>.*/si", "", $data);
			unset($data);
			$log .= $result;
			
			$log .= '</p>'."\n";
			
			//bing
			$log .= '<h5>Bing</h5>'."\n";
			$log .= '<p>'."\n";
			
			$pingurl = "https://www.bing.com/webmaster/ping.aspx?siteMap=" . urlencode($sitemap_url);
			$fp = fopen($pingurl, "r");
			$data = fread ($fp, 2048);
			fclose($fp);
			$result = substr((preg_replace("/.*<body[^>]*>|<\/body>.*/si", "", $data)),0,35);
			unset($data);
			$log .= $result;
			
			$log .= '</p>'."\n";
							
			//write the time stamp of the submit
			file_put_contents($submit_date, time());
			
		} else {
			$log = '<h4>Sitemap</h4>'."\n";
			$log .= '<p>No need to submit</p>'."\n";
		}
			
		return $log;
    }
    
    //!Delete
    public function deleteLinkFromMenu($link_id)
    {
		//need the module id for this link
		$module_id = $this->_menu_db->getModuleFromLinkId($link_id);
		$parent_id = $this->_menu_db->getParentId($link_id);
		
		if($module_id && $parent_id)
		{
			//set if you can delete this menu item or not
			$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
			$system_register = $system_register_ns::getInstance();	
			
			$canDelete = $system_register->getModuleActiveFlag($module_id);

			if($canDelete)
			{
				//get file_manager to delete all the files associated with this link_id
				$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		    	$file_manager = $file_manager_ns::getInstance();
		    	
				$file_manager->deleteLinkIds($link_id);
				
				//tell the module to delete stuff
				$module_admin_ns = NS_MODULES.'\\'.$module_id.'\\admin\\admin';
				if (class_exists($module_admin_ns)) {
					$admin_obj = new $module_admin_ns();
		    		$admin_obj->deleteMenuLink($link_id);
				}
				
		    	
		    	//update ALL old referrences to this link_id as a parent in other links!
				//effectively this moves all the children up a level - better than deleting the tree!
				$this->_menu_db->updateMenuTreeParentIds($link_id,$parent_id);
				
				//remove this actual menu item
				$this->_menu_db->deleteMenuItem($link_id);
			}
	    }
		return;
    }

	
	/**
	*
		REMOVE THIS SOME TIME 28 Jan 2019

	public function move2MenuTree()
	{
		if( $this->_menu_db->hasMenuTreeItems() )
		{	
			$out = "<p>Menu Tree has records - no further action required</p>";
			
		} else {
				
			$out = "<p style=\"color:red\">RUNNING UPDATE TO MENU TREE</p>";
			
			$this->_menu_db->moveMenuToMenuTree();
		        
		    if( $this->_menu_db->hasMenuTreeItems() )
			{	
				$out .= "<p>Menu Tree has records - no further action required</p>";
				
			} else {
				$out = "<p style=\"color:red\">MENU TREE IS STILL EMPTY!!!!</p>";
			}
			
		}

		return $out;
	}
	
	*
	**/
	
}
?>