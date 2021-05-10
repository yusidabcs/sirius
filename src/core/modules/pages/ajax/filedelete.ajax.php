<?php
namespace core\modules\pages\ajax;

/**
 * Final filedelete class.
 * 
 * Ajax to allow for files to be uploaded
 *
 * @final
 * @package 	pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class filedelete extends \core\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		$file_manager = $file_manager_ns::getInstance();
		
		$file_manager->deleteFile($this->option);

        $out['success'] = true;
        $out['message'] = 'Successfully delete file.';
		
		if(!empty($out))
		{
			header('Content-Type: application/json; charset=utf-8');
			return json_encode($out);
		} else {
			return ;
		}				
	}
	
}
?>