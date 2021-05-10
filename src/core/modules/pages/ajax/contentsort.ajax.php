<?php
namespace core\modules\pages\ajax;

/**
 * Final contentsort class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class contentsort extends \core\app\classes\module_base\module_ajax {
	
	//set by me
	private $_link_id = '';
	private $_sequence = 0;
	
	public function run()
	{	
		//!NEEDED check post values
		if($this->_processPostValues())
		{
			//update the server with the page information
			$pages_common_ns = NS_MODULES.'\\pages\\models\\common\\common';
			$pages_common = new $pages_common_ns();
			
			if($pages_common->updateContentSort($this->_link_id,$this->_sequence))
			{		
				//json to send back
				$out['response'] = 'OK';
				$out['message'] = 'Sort order updated on Server';
			} else {
				$out['response'] = 'Failed';
				$out['message'] = 'Sort order update failed on Server';
			}
		} else {
			$out['response'] = 'Failed';
			$out['message'] = 'Hmm, I do not know what is up with this!';
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
		$this->_link_id = $_POST['link_id'];
		$sequence_array = $_POST['sequence'];
		$this->_sequence = $sequence_array;
			
		return true;
	}
	
}
?>