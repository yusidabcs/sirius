<?php
namespace core\modules\pages\ajax;

/**
 * Final pagecontentdelete class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class pagecontentdelete extends \core\app\classes\module_base\module_ajax {
	
	//set by me
	private $_link_id = '';
	private $_content_id = '';
	
	public function run()
	{	
		$this->authorizeAjax('pagecontentdelete');
		//!NEEDED check post values
		if($this->_processPostValues())
		{
			//update the server with the page information
			$pages_common_ns = NS_MODULES.'\\pages\\models\\common\\common';
			$pages_common = new $pages_common_ns();
			
			if($pages_common->deleteContentInfo($this->_link_id,$this->_content_id))
			{		
				//json to send back
				$out['response'] = 'OK';
				$out['message'] = 'Page Content Updated on Server';
			} else {
				$out['response'] = 'Failed';
				$out['message'] = 'Page Content did not update on the Server';
			}
		} else {
			$out['response'] = 'Failed';
			$out['message'] = 'Hmm, the content information was not good!';
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
		$this->_content_id = $_POST['content_id'];			
		return true;
	}
	
}
?>