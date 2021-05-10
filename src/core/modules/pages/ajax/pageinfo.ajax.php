<?php
namespace core\modules\pages\ajax;

/**
 * Final filelist class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class pageinfo extends \core\app\classes\module_base\module_ajax {
		
	//set by me
	private $_link_id = '';
	private $_page_heading = 'No Page Heading';
	private $_page_sdesc = '';
	private $_page_keywords = '';
	private $_page_text = '';
	private $_show_anchors = false;
	
	public function run()
	{
		//!NEEDED check post values
		if($this->_processPostValues())
		{
			//update the server with the page information
			$page_core_ns = NS_APP_CLASSES.'\\page_core\\page_core';
			$page_core = $page_core_ns::getInstance();
			
			if($page_core->updatePageCoreInfo($this->_link_id,$this->_show_heading,$this->_page_heading,$this->_page_sdesc,$this->_page_keywords,$this->_page_text,$this->_show_anchors))
			{		
				//json to send back
				$out['response'] = 'OK';
				$out['message'] = 'Page Information Updated on Server';
			} else {
				$out['response'] = 'Failed';
				$out['message'] = 'Page Information did not update on the Server';
			}
		} else {
			$out['response'] = 'Failed';
			$out['message'] = 'Hmm, the information was not good!';
		}

		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
	private function _processPostValues()
	{
		$this->_link_id = $_POST['link_id']; //check if the link is real!
		$this->_show_heading = isset($_POST['show_heading']) ? 1 : 0;
		$this->_page_heading = $_POST['page_heading']; //check that it is not empty after stripping
		$this->_page_sdesc = $_POST['page_sdesc'];
		$this->_page_keywords = $_POST['page_keywords'];
		$this->_page_text = PAGE_TEXT;
		$this->_show_anchors = isset($_POST['show_anchors']) ? 1 : 0;

		return true;
	}
	
}
?>