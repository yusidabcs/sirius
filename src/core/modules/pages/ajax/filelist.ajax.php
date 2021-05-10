<?php
namespace core\modules\pages\ajax;

/**
 * Final filelist class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class filelist extends \core\app\classes\module_base\module_ajax {
		
	private $_link_id;
	
	public function __construct($options_a)
	{
		//set up option for fileId
		$this->_link_id = $options_a[0];
		
		if( empty($this->_link_id) )
		{
			die('Hmmm .. that does not look right to me');
		} 

		return;
	}
		
	public function run()
	{	
		$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		$file_manager = $file_manager_ns::getInstance();

		//now get all the files for this link_id
		$fileInfo_a = $file_manager->getFilesArray($this->_link_id);

		if(!empty($fileInfo_a))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($fileInfo_a);
		} else {
			return ;
		}
	}
	
}
?>