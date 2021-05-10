<?php
namespace core\modules\menu\models\common;

/**
 * Final form_check class.
 * 
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 * @final
 */
final class form_check {
	
	//set at construct
	private $_generic;
	private $_sytem_register;	
	private $_menu_common;
	private $_menu_db;
	
	//set by setInput
	private $_link_id;
	private $_link_id_orig;	
	private $_parent_id;
	private $_parent_id_orig;	
	private $_redirect_url;
	private $_sequence_no;
	private $_title_menu;
	private $_title_menu_orig;
	private $_title_page;
	private $_title_page_orig;
	private $_template_name;
	private $_module_id;
	private $_security_level_id;
	private $_group_id;
	private $_main_link;
	private $_quick_link;
	private $_bottom_link;
	private $_sitemap;
	private $_status;
	
	public function __construct()
	{
		$generic_ns = NS_APP_CLASSES.'\\generic\\generic';
		$this->_generic = $generic_ns::getInstance() ;
		
		$menu_common_ns = NS_MODULES.'\\menu\\models\\common\\common';
		$this->_menu_common = $menu_common_ns::getInstance();
		
		$menu_db_ns = NS_MODULES.'\\menu\\models\\common\\db';
		$this->_menu_db = $menu_db_ns::getInstance();
		
		return;
	}
	
	/**
	 * checkTitle function.
	 * 
	 * @access public
	 * @param mixed $title
	 * @return void
	 */
	public function checkTitle($title)
	{
		$normalized = $this->_generic->makeSafeString($title);
		
		//first check is to see if the value is normalized
		if( $title == $normalized )
		{
			$out = true;			
		} else {
			$out = false;
		}
		
		return $out;
	}
	
	/**
	 * checkMenuUnique function.
	 * 
	 * @access public
	 * @param mixed $title_menu
	 * @return void
	 */
	public function checkMenuUnique($title_menu)
	{
		if($this->_menu_db->menuIsReal($title_menu))
		{
			$out = false;
		} else {
			$out = true;
		}
		return $out;
	}
	
	/**
	 * makeLinkId function.
	 * 
	 * @access public
	 * @param mixed $title_menu
	 * @return void
	 */
	public function makeLinkId($title_menu)
	{
		return $this->_generic->safeLinkId($title_menu);
	}
	
	/**
	 * checkLinkIdUnique function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 */
	public function checkLinkIdUnique($link_id)
	{
	    if( $this->_menu_db->getModuleFromLinkId($link_id) )
	    {
		    $out = false;
		} else {
			$out = true;
		}
		
		return $out;
	}
	
	/**
	 * setInput function.
	 * 
	 * sets local values for input and adds a little bit of error correction
	 *
	 * @access public
	 * @return void
	 */
	public function setInput()
	{
		//sanitize the post inputs
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		
		$this->_link_id = trim($_POST['link_id']);
		$this->_link_id_orig = isset($_POST['link_id_orig']) ? $_POST['link_id_orig'] : $this->_link_id;
		
		$this->_parent_id = $_POST['parent_id'];
		$this->_parent_id_orig = isset($_POST['parent_id_orig']) ? $_POST['parent_id_orig'] : $this->_parent_id;
		
		$this->_redirect_url =  isset($_POST['redirect_url']) ?  trim($_POST['redirect_url']) : '';
		$this->_sequence_no =  empty($_POST['sequence_no']) ? 0 : $_POST['sequence_no'];

		$this->_title_menu = trim($_POST['title_menu']);
		$this->_title_menu_orig = isset($_POST['title_menu_orig']) ? $_POST['title_menu_orig'] : $this->_title_menu;
		
		$this->_title_page =  trim($_POST['title_page']);
		$this->_title_page_orig = isset($_POST['title_page_orig']) ? $_POST['title_page_orig'] : $this->_title_page;
		
		$this->_template_name =  $_POST['template_name'];
		$this->_module_id = isset($_POST['module_id']) ? $_POST['module_id'] : '';
		$this->_security_level_id = $_POST['security_level_id'];
		$this->_group_id = $_POST['group_id'];
		
		$this->_main_link = isset($_POST['main_link']) && $_POST['main_link'] == 1 ? 1 : 0;
		$this->_quick_link = isset($_POST['quick_link']) && $_POST['quick_link'] == 1 ? 1 : 0;
		$this->_bottom_link = isset($_POST['bottom_link']) && $_POST['bottom_link'] == 1 ? 1 : 0;
		$this->_sitemap = isset($_POST['sitemap']) && $_POST['sitemap'] == 1 ? 1 : 0;
		$this->_status = isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		
		return;
	}
	
	/**
	 * checkData function.
	 * 
	 * @access public
	 * @return void
	 */
	public function checkData()
	{
		$out = array();
		
		//we are going to need
		$common_form_ns = NS_MODULES.'\\menu\\models\\common\\form';
		$menuCommonForm = new $common_form_ns();

		//need to handle the link_id first ... it is the most important!
		if(empty($this->_link_id)) $out['Blank Link ID:'] = 'Link Id can not be blank';
		
		if($this->_link_id != $this->_link_id_orig) {
			
			if(!($this->checkLinkIdUnique($this->_link_id))) $out['Duplicate Link ID:'] = 'Duplicate Link Id';
			
			$safe_id = $this->_generic->safeLinkId($this->_link_id);
			if($safe_id != $this->_link_id) $out['Bad Link ID:'] = 'The link id is not correct';
		}

		//Page Parent Check
		if(empty($this->_parent_id)) $out['Blank Parent ID:'] = 'Parent Id can not be blank';
		
		$pageParentArray = $menuCommonForm->makeParentOptionArray($this->_link_id_orig);
		
		foreach($pageParentArray as $value)
		{
			$parent_a[] = $value['link_id'];
		}
		
		if(!in_array($this->_parent_id, $parent_a)) $out['Parent Id:'] = 'Not sure how that is possible but it is not a correct parent!';
		
		//Menu Title Check
		if(empty($this->_title_menu)) $out['Blank Menu Title:'] = 'Menu Title can not be blank';
		
		$normal_menu = $this->_generic->makeSafeString($this->_title_menu);
		if($normal_menu != $this->_title_menu) $out['Menu Title:'] = 'Menu Title contained bad characters or multiple spaces';
		
		if($this->_title_menu != $this->_title_menu_orig)
		{
			if($this->_menu_db->menuIsReal($this->_title_menu)) $out['Menu Title Duplicate:'] = 'Menu Title was being used elsewhere';
		}
		
		//Page Page Check
		if(empty($this->_title_page)) $out['Blank Page Title:'] = 'Page Title can not be blank';
		
		$normal_page = $this->_generic->makeSafeString($this->_title_page);
		if($normal_page != $this->_title_page) $out['Menu Page:'] = 'Page Title contained bad characters or multiple spaces';
		
		if($this->_title_page != $this->_title_page_orig)
		{
			if($this->_menu_db->pageIsReal($this->_title_page)) $out['Page Title Duplicate:'] = 'Page Title was being used elsewhere';
		}
		
		//URL Redirect Check
		
		//Template Name
		if(empty($this->_template_name)) $out['Blank Template Name:'] = 'Template can not be blank';
		
		//Module
		if(empty($this->_module_id)) $out['Blank Module ID:'] = 'Module Id can not be blank';
		
		//Security Level
		if(empty($this->_security_level_id)) $out['Blank Security Level:'] = 'Security Level can not be blank';
		
		//Group
		if(empty($this->_group_id)) $out['Blank Group ID:'] = 'Group Id can not be blank';
		
		//Sitemap Check
		if($this->_sitemap == 1)
		{
			if( $this->_security_level_id != 'NONE' ||  $this->_group_id != 'ALL' || $this->_status != 1)
			{
				$out['Sitemap:'] = 'You can not have this in the sitemap unless security is NONE, group is ALL and status is on';
			}
		}
		
		return $out;
	}
	
	/**
	 * addMenuItem function.
	 * 
	 * @access public
	 * @return void
	 */
	public function addMenuItem()
	{	
		$this->_menu_common->addMenuItem(
									$this->_link_id,
									$this->_parent_id,
									$this->_redirect_url,
									$this->_sequence_no,
									$this->_title_menu,
									$this->_title_page,
									$this->_template_name,
									$this->_module_id,
									$this->_security_level_id,
									$this->_group_id,
									$this->_main_link,
									$this->_quick_link,
									$this->_bottom_link,
									$this->_sitemap,
									$this->_status
								);
		return;
	}
	
	/**
	 * updateMenuItem function.
	 * 
	 * @access public
	 * @return void
	 */
	public function updateMenuItem()
	{	
		//make sure we should be updating
		if( empty($this->_link_id_orig) )
		{
			die('You are updating when you should be adding!');
		}
				
		//this will make the link id correct everywhere
		if($this->_link_id != $this->_link_id_orig)
		{
			//update link on system as well as any other items
			$this->_menu_common->updateLinkIdOnSystem(
											$this->_link_id_orig,
											$this->_link_id
										);
		}
			
		//now update the system	
		$this->_menu_common->updateMenuItem(
									$this->_link_id,
									$this->_parent_id,
									$this->_redirect_url,
									$this->_sequence_no,
									$this->_title_menu,
									$this->_title_page,
									$this->_template_name,
									$this->_module_id,
									$this->_security_level_id,
									$this->_group_id,
									$this->_main_link,
									$this->_quick_link,
									$this->_bottom_link,
									$this->_sitemap,
									$this->_status,
									$this->_parent_id_orig
								);	
		return;
	}
	
}
?>