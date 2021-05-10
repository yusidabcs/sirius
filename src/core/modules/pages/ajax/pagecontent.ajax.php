<?php
namespace core\modules\pages\ajax;

/**
 * Final pagecontent class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class pagecontent extends \core\app\classes\module_base\module_ajax {
		
	//set by me
	private $_data; //the data array to send
	
	public function run()
	{	
		//!NEEDED check post values
		if($this->_processPostValues())
		{
			//update the server with the page information
			$pages_common_ns = NS_MODULES.'\\pages\\models\\common\\common';
			$pages_common = new $pages_common_ns();
			
			$updated = $pages_common->updateContentInfo($this->_data);
			if($updated)
			{		
				//json to send back
				$out['response'] = 'OK';
				$out['message'] = 'Page Content Updated on Server';
				$out['data'] = $pages_common->getPageContent($this->_data['link_id'],$this->_data['content_id']);
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
		//!need to add some error checking
		
		$this->_data['link_id'] = $_POST['link_id']; //check if the link is real!
		$this->_data['content_id'] = $_POST['content_id'];
		$this->_data['content_type'] = $_POST['content_type'];
		$this->_data['show_heading'] = $_POST['show_heading'] ? 1 : 0;
		$this->_data['heading'] = empty($_POST['heading']) ? 'No Content Heading' : $_POST['heading']; //check that it is not empty after stripping
		$this->_data['sdesc'] = $_POST['sdesc'];
		$this->_data['content'] = SECTION_TEXT; //need to keep html tags!  there is a filter called HTML_purfier .. should use that!
		
		$this->_data['to_name'] = $_POST['to_name'];
		$this->_data['to_email'] = $_POST['to_email'];
		$this->_data['to_subject'] = $_POST['to_subject'];
		$this->_data['submitted_heading'] = $_POST['submitted_heading'];
		$this->_data['submitted_sdesc'] = $_POST['submitted_sdesc'];
		$this->_data['submitted_content'] = empty($_POST['submitted_content']) ? '' : $_POST['submitted_content']; 
		$this->_data['image_position'] = empty($_POST['image_position']) ? NULL : $_POST['image_position'] ;

		$sequence_array = array_flip($_POST['sequence']);
		$this->_data['sequence'] = $sequence_array['entry-'.$_POST['content_id']];
			
		return true;
	}
	
}
?>