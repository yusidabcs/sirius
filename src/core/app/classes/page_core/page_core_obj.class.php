<?php
namespace core\app\classes\page_core;

/**
 * Final page_core class.
 *
 * @final
 * @package 	page_core
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 19 August 2019
 */
final class page_core_obj {

	private $_page_core_db;

	public function __construct()
	{	    
		//just check you have a database! It should always be on the local database
		$pcClass_ns = NS_APP_CLASSES.'\\page_core\\page_core_db';
		$this->_page_core_db = new $pcClass_ns();
					
		return;
	}
		
	/**
     * getPageCoreInfo function.
     * 
     * @access public
     * @param mixed $link_id
     * @return void
     */
    public function getPageCoreInfo($link_id)
	{
		return $this->_page_core_db->getPageCoreInfo($link_id);		
	}
	
	/**
	 * updateCoreLinkIds function.
	 * 
	 * @access public
	 * @param mixed $to_link_id
	 * @param mixed $from_link_id
	 * @return void
	 */
	public function updateCoreLinkIds($to_link_id,$from_link_id)
	{ 
		return $this->_page_core_db->updateCoreLinkIds($to_link_id,$from_link_id);	
	}

    /**
     * deletePageCore function.
     * 
     * @access public
     * @param mixed $link_id
     * @return void
     */
    public function deletePageCore($link_id)
	{
		return $this->_page_core_db->deletePageCore($link_id);
	}
	
	/**
	 * updatePageCoreInfo function.
	 *
	 * Used to update the page core information
	 * 
	 * @access public
	 * @param mixed $link_id
	 * @param mixed $show_heading
	 * @param mixed $page_heading
	 * @param mixed $page_sdesc
	 * @param mixed $page_keywords
	 * @param mixed $page_text
	 * @param mixed $show_anchors
	 * @return void
	 */
	public function updatePageCoreInfo($link_id,$show_heading,$page_heading,$page_sdesc,$page_keywords,$page_text,$show_anchors=0)
	{
		$out = false;
		
		//!should put in some more sanity checking but we will do that later
		if(empty($link_id))
		{
			$out = false;
		} else {
			$this->_page_core_db->updatePageCoreInfo($link_id,$show_heading,$page_heading,$page_sdesc,$page_keywords,$page_text,$show_anchors);
			$out = true;
		}
		
		return $out;
	}


    /**
     * get all page core data.
     *
     * @access public
     * @param mixed $link_id
     * @return void
     */
    public function getAllPageCore()
    {
        return $this->_page_core_db->getAllPageCore();
	}
	
}
?>