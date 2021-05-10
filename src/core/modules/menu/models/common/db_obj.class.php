<?php
namespace core\modules\menu\models\common;

/**
 * Final menu_db class.
 *
 * @final
 * @package 	menu
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */
final class db_obj extends \core\app\classes\module_base\module_db {

	public function __construct()
	{
		parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables		
		return;
	}
	
	//!Menu Module Functions
	
	//home
	
	/**
	 * getAllMenuItems function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getAllMenuItems()
	{
		$out = array();
	
		$sql = "SELECT
					`menu`.`link_id`,
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`,
					`menu`.`module_id`,
					`menu`.`security_level_id`,
					`menu`.`group_id`,
					`menu_tree`.`main_link`,
					`menu_tree`.`quick_link`,
					`menu_tree`.`bottom_link`,
					`menu`.`sitemap`,
					`menu`.`status`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				ORDER BY 
					`menu_tree`.`parent_id`,`menu_tree`.`sequence_no`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$main_link,$quick_link,$bottom_link,$sitemap,$status);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
				'link_id' => $link_id,
				'parent_id' => $parent_id,
				'redirect_url' => $redirect_url,
				'sequence_no' => $sequence_no,
				'title_menu' => $title_menu,
				'title_page' => $title_page,
				'template_name' => $template_name,
				'module_id' => $module_id,
				'security_level_id' => $security_level_id,
				'group_id' => $group_id,
				'main_link' => $main_link,
				'quick_link' => $quick_link,
				'bottom_link' => $bottom_link,
				'sitemap' => $sitemap,
				'status' => $status
			);
		}
		$stmt->close();
		
		return $out;
	}

	/**
	* Get frontend menu
	*/
	public function getFrontendMenuItems()
	{
		$out = array();
	
		$sql = "SELECT
					`menu`.`link_id`,
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`security_level_id`,
					`menu`.`group_id`,
					`menu_tree`.`main_link`,
					`menu_tree`.`quick_link`,
					`menu_tree`.`bottom_link`,
					`menu`.`status`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				WHERE 
					`menu`.`security_level_id` = 'NONE'
				AND
					`menu`.`group_id` = 'ALL'
				ORDER BY 
					`menu_tree`.`parent_id`,`menu_tree`.`sequence_no`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$security_level_id,$group_id,$main_link,$quick_link,$bottom_link,$status);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
				'link_id' => $link_id,
				'parent_id' => $parent_id,
				'redirect_url' => $redirect_url,
				'sequence_no' => $sequence_no,
				'title_menu' => $title_menu,
				'title_page' => $title_page,
				'security_level_id' => $security_level_id,
				'group_id' => $group_id,
				'main_link' => $main_link,
				'quick_link' => $quick_link,
				'bottom_link' => $bottom_link,
				'status' => $status
			);
		}
		$stmt->close();
		
		return $out;
	}

	public function getMenuItem($link_id) 
	{
		$out = [];
		$sql = "SELECT
					`menu`.`link_id`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`
				FROM
					`menu`
				WHERE
					`menu`.`link_id` = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s', $link_id);
		$stmt->bind_result($link_id,$title_menu,$title_page,$template_name);

		$stmt->execute();

		while ($stmt->fetch()) {
			$out['link_id'] = $link_id;
			$out['title_menu'] = $title_menu;
			$out['title_page'] = $title_page;
			$out['template_name'] = $template_name;
		}
		$stmt->fetch();

		return $out;
	}


	//!Add
	
	/**
	 * addMenuItem function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $redirect_url
	 * @param mixed $title_menu
	 * @param mixed $title_page
	 * @param mixed $template_name
	 * @param mixed $module_id
	 * @param mixed $security_level_id
	 * @param mixed $group_id
	 * @param mixed $sitemap
	 * @param mixed $status
	 * @return void
	 */
	public function addMenuItem($link_id,$redirect_url,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$sitemap,$status)
	{
		$out = '';
	
		$sql = "INSERT INTO
					`menu`
				SET
					`link_id` = ?,
					`redirect_url` = ?,
					`title_menu` = ?,
					`title_page` = ?,
					`template_name` = ?,
					`module_id` = ?,
					`security_level_id` = ?,
					`group_id` = ?,
					`sitemap` = ?,
					`status` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ssssssssii",$link_id,$redirect_url,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$sitemap,$status);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * addMenuLink function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $parent_id
	 * @param mixed $sequence_no
	 * @param mixed $main_link
	 * @param mixed $quick_link
	 * @param mixed $bottom_link
	 * @return void
	 */
	public function addMenuLink($link_id,$parent_id,$sequence_no,$main_link,$quick_link,$bottom_link)
	{
		$out = '';
			
		$sql = "INSERT INTO
					`menu_tree`
				SET
					`link_id` = ?,
					`parent_id` = ?,
					`sequence_no` = ?,
					`main_link` = ?,
					`quick_link` = ?,
					`bottom_link` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ssiiii",$link_id,$parent_id,$sequence_no,$main_link,$quick_link,$bottom_link);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	//!Edit
	
	//main
	
	/**
	 * getAllMenuDetailsForLinkId function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 */
	public function getAllMenuDetailsForLinkId($link_id)
	{
		$link_id = trim(strtolower($link_id));
		
		$out = array();
	
		$sql = "SELECT
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`,
					`menu`.`module_id`,
					`menu`.`security_level_id`,
					`menu`.`group_id`,
					`menu_tree`.`main_link`,
					`menu_tree`.`quick_link`,
					`menu_tree`.`bottom_link`,
					`menu`.`sitemap`,
					`menu`.`status`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				WHERE
					`menu_tree`.`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$main_link,$quick_link,$bottom_link,$sitemap,$status);

		$stmt->execute();
		if($stmt->fetch())
	    {
		    $out['link_id'] = $link_id;
		    $out['parent_id'] = $parent_id;
		    $out['redirect_url'] = $redirect_url;
		    $out['sequence_no'] = $sequence_no;
		    $out['title_menu'] = $title_menu;
		    $out['title_page'] = $title_page;
		    $out['template_name'] = $template_name;
	    	$out['module_id'] = $module_id;
	    	$out['security_level_id'] = $security_level_id;
	    	$out['group_id'] = $group_id;
	    	$out['main_link'] = $main_link;
	    	$out['quick_link'] = $quick_link;
	    	$out['bottom_link'] = $bottom_link;
	    	$out['sitemap'] = $sitemap;
	    	$out['status'] = $status;
	    } else {
		    $out['link_id'] = '';
		    $out['parent_id'] = '';
		    $out['redirect_url'] = '';
		    $out['sequence_no'] = '';
		    $out['title_menu'] = '';
		    $out['title_page'] = '';
		    $out['template_name'] = '';
	    	$out['module_id'] = '';
	    	$out['security_level_id'] = '';
	    	$out['group_id'] = '';
	    	$out['main_link'] = '';
	    	$out['quick_link'] = '';
	    	$out['bottom_link'] = '';
	    	$out['sitemap'] = '';
	    	$out['status'] = '';
	    }
		$stmt->close();
		
		return $out;
	}
	
	//input
	
	/**
	 * updateMenuLinkId function.
	 * 
	 * @access public
	 * @param mixed $link_id_orig
	 * @param mixed $link_id
	 * @return void
	 */
	public function updateMenuLinkId($link_id_orig,$link_id)
	{
		$out = '';
		
		$sql = "UPDATE
					`menu`
				SET
					`link_id` = ?
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ss",$link_id,$link_id_orig);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
	
	return $out;

	}
	
	/**
	 * updateMenuTreeLinkId function.
	 * 
	 * @access public
	 * @param mixed $link_id_orig
	 * @param mixed $link_id
	 * @return void
	 */
	public function updateMenuTreeLinkId($link_id_orig,$link_id)
	{
		//update link
		$sql = "UPDATE
					`menu_tree`
				SET
					`link_id` = ?
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ss",$link_id,$link_id_orig);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
	
		return $out;

	}
	
	/**
	 * updateMenuTreeParentIds function.
	 * 
	 * @access public
	 * @param mixed $link_id_orig
	 * @param mixed $link_id
	 * @return void
	 */
	public function updateMenuTreeParentIds($link_id_orig,$link_id)
	{	
		//update parent
		$sql = "UPDATE
					`menu_tree`
				SET
					`parent_id` = ?
				WHERE
					`parent_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ss",$link_id,$link_id_orig);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;

	}
	
	/**
	 * updateMenuLinkParentId function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $parent_id
	 * @param mixed $parent_id_orig
	 * @return void
	 */
	public function updateMenuLinkParentId($link_id,$parent_id,$parent_id_orig)
	{
		$out = '';
	
		$sql = "UPDATE
					`menu_tree`
				SET
					`parent_id` = ?
				WHERE
					`link_id` = ?
				AND
					`parent_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("sss",$parent_id,$link_id,$parent_id_orig);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * updateMenuItem function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $redirect_url
	 * @param mixed $title_menu
	 * @param mixed $title_page
	 * @param mixed $template_name
	 * @param mixed $module_id
	 * @param mixed $security_level_id
	 * @param mixed $group_id
	 * @param mixed $sitemap
	 * @param mixed $status
	 * @return void
	 */
	public function updateMenuItem($link_id,$redirect_url,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$sitemap,$status)
	{
		$out = '';
	
		$sql = "UPDATE
					`menu`
				SET
					`redirect_url` = ?,
					`title_menu` = ?,
					`title_page` = ?,
					`template_name` = ?,
					`module_id` = ?,
					`security_level_id` = ?,
					`group_id` = ?,
					`sitemap` = ?,
					`status` = ?
				WHERE
					`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("sssssssiis",$redirect_url,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$sitemap,$status,$link_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * updateMenuLink function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $parent_id
	 * @param mixed $sequence_no
	 * @param mixed $main_link
	 * @param mixed $quick_link
	 * @param mixed $bottom_link
	 * @return void
	 */
	public function updateMenuLink($link_id,$parent_id,$sequence_no,$main_link,$quick_link,$bottom_link)
	{
		$out = '';
	
		$sql = "UPDATE
					`menu_tree`
				SET
					`sequence_no` = ?,
					`main_link` = ?,
					`quick_link` = ?,
					`bottom_link` = ?
				WHERE
					`link_id` = ?
				AND
					`parent_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("iiiiss",$sequence_no,$main_link,$quick_link,$bottom_link,$link_id,$parent_id);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
	}

	//Delete
	
	/**
	 * getParentId function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 */
	public function getParentId($link_id)
    {
		$out = false;
	
		$sql = "SELECT
					`parent_id`
				FROM
					`menu_tree`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($parent_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $parent_id;
		} 
		$stmt->close();
		
		return $out;
    }
    
    /**
     * deleteMenuItem function.
     * 
     * @access public
     * @param mixed $link_id
     * @return void
     */
    public function deleteMenuItem($link_id)
	{
		$sql = "DELETE 
					`menu`,`menu_tree`
				FROM
					`menu`
				LEFT JOIN
					`menu_tree`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
                WHERE
					`menu`.`link_id` = ?
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->execute();
		$stmt->close();		
		return;
	}

	//form check
	
	/**
	 * menuIsReal function.
	 * 
	 * @access public
	 * @param mixed $title_menu
	 * @return void
	 */
	public function menuIsReal($title_menu)
	{
		$out = false;
	
		$sql = "SELECT
					`link_id`
				FROM
					`menu`
				WHERE
					`title_menu` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$title_menu);
		$stmt->bind_result($link_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * getModuleFromLinkId function.
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 **/
	public function getModuleFromLinkId($link_id)
	{
		$out = false;
	
		$sql = "SELECT
					`module_id`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($module_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $module_id;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * pageIsReal function.
	 * 
	 * @access public
	 * @param mixed $title_page
	 * @return void
	 */
	public function pageIsReal($title_page)
    {
    	$out = false;
	
		$sql = "SELECT
					`link_id`
				FROM
					`menu`
				WHERE
					`title_page` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$title_page);
		$stmt->bind_result($link_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		} 
		$stmt->close();
		
		return $out;
    }

	//!Form
	
	/**
	 * moduleIsUsed function.
	 * 
	 * @access public
	 * @param mixed $module_id
	 * @return void
	 */
	public function moduleIsUsed($module_id)
    {
	    $out = false;
	
		$sql = "SELECT
					`link_id`
				FROM
					`menu`
				WHERE
					`module_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$module_id);
		$stmt->bind_result($link_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		} 
		$stmt->close();
	    
		return $out;
    }
	
	//!Site Map 
	public function getSiteMapURLs()
	{
		$out = array();
	
		$sql = "SELECT
					`link_id`
				FROM
					`menu`
                WHERE
                	`redirect_url` = ''
                AND
                	`security_level_id` = 'NONE'
                AND
                	`group_id` = 'ALL'
                AND
                	`sitemap` = 1
                AND
                	`status` = 1
                ";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = $link_id;
		}
		$stmt->close();
		
		return $out;
	}
		
    //!Menu Common ini2sql
    public function updateSitemapStatus()
    {
		$sql = "
				UPDATE 
					`menu`
				SET 
					`sitemap` = 1
                WHERE
                	`redirect_url` = ''
                AND
                	`security_level_id` = 'NONE'
                AND
                	`group_id` = 'ALL'
                AND
                	`status` = 1
				";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
    }

	/**
	 * getBuildMenuItems function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getBuildMenuItems()
	{
		$out = array();
	
		$sql = "SELECT
					`menu`.`link_id`,
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`,
					`menu`.`module_id`,
					`menu`.`security_level_id`,
					`menu`.`group_id`,
					`menu_tree`.`main_link`,
					`menu_tree`.`quick_link`,
					`menu_tree`.`bottom_link`,
					`menu`.`sitemap`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				WHERE
					`menu`.`status` = 1
				ORDER BY 
					`menu_tree`.`parent_id`,`menu_tree`.`sequence_no`,`menu`.`title_menu`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id,$security_level_id,$group_id,$main_link,$quick_link,$bottom_link,$sitemap);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
				'link_id' => $link_id,
				'parent_id' => $parent_id,
				'redirect_url' => $redirect_url,
				'sequence_no' => $sequence_no,
				'title_menu' => $title_menu,
				'title_page' => $title_page,
				'template_name' => $template_name,
				'module_id' => $module_id,
				'security_level_id' => $security_level_id,
				'group_id' => $group_id,
				'main_link' => $main_link,
				'quick_link' => $quick_link,
				'bottom_link' => $bottom_link,
				'sitemap' => $sitemap
			);
		}
		$stmt->close();
		
		return $out;
	}	
	
	/**
		CHECKING 27 AUGUST 2019
		
	    
        
    //!Admin 
	public function getAllOpenLinks()
    {
	    $out = array();
	
		$sql = "SELECT
					`link_id`
				FROM
					`menu`
				WHERE
					`security_level_id` = 'NONE'
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id);
		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = $link_id;
		} 
		$stmt->close();
		
		return $out;
    }
    
    **/
    
    
    /**
	*
		REMOVE THIS SOME TIME 28 Jan 2019
    
    //!Upgrading to Menu Tree Function
    public function hasMenuTreeItems()
    {
	    $out = false;
	
		$sql = "SELECT
					COUNT(*)
				FROM
					`menu_tree`
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($count);
		$stmt->execute();
		if($stmt->fetch())
		{
			if($count > 0)
			{
				$out = true;
			}
		} 
		$stmt->close();
		
		return $out;

    }
   
    public function moveMenuToMenuTree()
    {
	    $out = '';
		
		$sql = "INSERT INTO `menu_tree` (`link_id`,`parent_id`,`sequence_no`,`main_link`,`quick_link`,`bottom_link`)
					SELECT `link_id`,`parent_id`,`sequence_no`,`main_link`,`quick_link`,`bottom_link` FROM	`menu`		
				";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$out = $stmt->affected_rows;
		$stmt->close();
		
		return $out;
    }
    
   
    
    *
    **/
    
    /**
	    
	    DELETED 22 AUGUST to Clean things up
	    
	    //!Used by Menu Register

	/**
	 * getMainMenuItems function.
	 * 
	 * @access public
	 * @param mixed $permitted_secuirty
	 * @param mixed $permitted_group
	 * @return void
	 
	public function getMainMenuItems($permitted_secuirty,$permitted_group)
	{
		$out = array();	
	
		$sql = "SELECT
					`menu`.`link_id`,
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`,
					`menu`.`module_id`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				WHERE
					`menu`.`security_level_id` IN ( $permitted_secuirty )
				AND
					`menu`.`group_id` IN ( $permitted_group )
				AND
					`menu_tree`.`main_link` = 1
				AND
					`menu`.`status` = 1
				ORDER BY 
					`menu_tree`.`parent_id`,`menu_tree`.`sequence_no`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
				'link_id' => $link_id,
				'parent_id' => $parent_id,
				'redirect_url' => $redirect_url,
				'sequence_no' => $sequence_no,
				'title_menu' => $title_menu,
				'title_page' => $title_page,
				'template_name' => $template_name,
				'module_id' => $module_id
			);
		}
		$stmt->close();
		
		return $out;
	}
	
	public function getQuickMenuItems($permitted_secuirty,$permitted_group)
	{
		$out = array();	
	
		$sql = "SELECT
					`menu`.`link_id`,
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`,
					`menu`.`module_id`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				WHERE
					`menu`.`security_level_id` IN ( $permitted_secuirty )
				AND
					`menu`.`group_id` IN ( $permitted_group )
				AND
					`menu_tree`.`quick_link` = 1
				AND
					`menu`.`status` = 1
				ORDER BY 
					`menu_tree`.`parent_id`,`menu_tree`.`sequence_no`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
				'link_id' => $link_id,
				'parent_id' => $parent_id,
				'redirect_url' => $redirect_url,
				'sequence_no' => $sequence_no,
				'title_menu' => $title_menu,
				'title_page' => $title_page,
				'template_name' => $template_name,
				'module_id' => $module_id
			);
		}
		$stmt->close();
		
		return $out;
	}

	public function getBottomMenuItems($permitted_secuirty,$permitted_group)
	{
		$out = array();	
	
		$sql = "SELECT
					`menu`.`link_id`,
					`menu_tree`.`parent_id`,
					`menu`.`redirect_url`,
					`menu_tree`.`sequence_no`,
					`menu`.`title_menu`,
					`menu`.`title_page`,
					`menu`.`template_name`,
					`menu`.`module_id`
				FROM
					`menu_tree`
				LEFT JOIN
					`menu`
				ON
					`menu`.`link_id` = `menu_tree`.`link_id`
				WHERE
					`menu`.`security_level_id` IN ( $permitted_secuirty )
				AND
					`menu`.`group_id` IN ( $permitted_group )
				AND
					`menu_tree`.`bottom_link` = 1
				AND
					`menu`.`status` = 1
				ORDER BY 
					`menu_tree`.`parent_id`,`menu_tree`.`sequence_no`
				";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($link_id,$parent_id,$redirect_url,$sequence_no,$title_menu,$title_page,$template_name,$module_id);

		$stmt->execute();
		while($stmt->fetch())
		{
			$out[] = array(
				'link_id' => $link_id,
				'parent_id' => $parent_id,
				'redirect_url' => $redirect_url,
				'sequence_no' => $sequence_no,
				'title_menu' => $title_menu,
				'title_page' => $title_page,
				'template_name' => $template_name,
				'module_id' => $module_id
			);
		}
		$stmt->close();
		
		return $out;
	}

		
	/**
	 * getSecurityFromLinkId function.
	 * 
	 * Used by Menu Register to get the menu array
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 
	public function getSecurityFromLinkId($link_id)
	{
		$out = array();
	
		$sql = "SELECT
					`security_level_id`,
					`group_id`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($security_level_id,$group_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out['security_level'] = $security_level_id;
			$out['group_id'] = $group_id;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * allowAccessFromLinkId function.
	 * 
	 * Used by Menu Register to get the menu array
	 * 
	 * @access public
	 * @param mixed $permitted_secuirty
	 * @param mixed $permitted_group
	 * @param mixed $link_id
	 * @return void
	 
	public function allowAccessFromLinkId($permitted_secuirty,$permitted_group,$link_id)
	{
		$out = false;
		
		$sql = "SELECT
					`module_id`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				AND
					`security_level_id` IN ( $permitted_secuirty )
				AND
					`group_id` IN ( $permitted_group )
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($module_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = true;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * getLinkIdFromModuleId function.
	 * 
	 * Used by Menu Register to get the menu array
	 *
	 * @access public
	 * @param mixed $module_id
	 * @return void
	 
	public function getLinkIdFromModuleId($module_id)
	{
		$out = false;
	
		$sql = "SELECT
					`link_id`
				FROM
					`menu`
				WHERE
					`module_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$module_id);
		$stmt->bind_result($link_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $link_id;
		} 
		$stmt->close();
		
		return $out;
	}

	/**
	 * getTemplateFromLinkId function.
	 * 
	 * Used by Menu Register to get the menu array
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 
	public function getTemplateFromLinkId($link_id)
	{
		$out = false;
	
		$sql = "SELECT
					`template_name`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($template_name);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $template_name;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * getPageTitleFromLinkId function.
	 * 
	 * Used by Menu Register to get the menu array
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 
	public function getPageTitleFromLinkId($link_id)
	{
		$out = false;
	
		$sql = "SELECT
					`title_page`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($title_page);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $title_page;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * getSecurityLevelIdFromLinkId function.
	 * 
	 * Used by Menu Register to get the menu array
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 
	public function getSecurityLevelIdFromLinkId($link_id)
	{
		$out = false;
	
		$sql = "SELECT
					`security_level_id`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($security_level_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $security_level_id;
		} 
		$stmt->close();
		
		return $out;
	}
	
	/**
	 * getGroupIdFromLinkId function.
	 * 
	 * Used by Menu Register to get the menu array
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @return void
	 
	public function getGroupIdFromLinkId($link_id)
	{
		$out = false;
	
		$sql = "SELECT
					`group_id`
				FROM
					`menu`
				WHERE
					`link_id` = ?
				";
				
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param('s',$link_id);
		$stmt->bind_result($group_id);
		$stmt->execute();
		if($stmt->fetch())
		{
			$out = $group_id;
		} 
		$stmt->close();
		
		return $out;
	}
		
    
    **/
}
?>