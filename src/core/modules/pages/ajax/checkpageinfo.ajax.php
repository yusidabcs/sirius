<?php
namespace core\modules\pages\ajax;

/**
 * Final checkpageinfo class.
 * 
 * Ajax to send the information about files on a page
 *
 * @final
 * @package pages
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 29 August 2019
 */
final class checkpageinfo extends \core\app\classes\module_base\module_ajax {
		
	public function run()
	{	
		if($this->option)
		{
			$page_core_ns = NS_APP_CLASSES.'\\page_core\\page_core';
			$page_core = $page_core_ns::getInstance();

			$link_id = $this->option;
			$data = $page_core->getPageCoreInfo($link_id);

			if(!empty($data))
			{		
				//json to send back
				$out['message'] = 'exist';
			} else {
				$out['message'] = 'not exist';
			}
		} else {
			$out['response'] = 'Failed';
			$out['message'] = 'Hmm, No option found.';
		}

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