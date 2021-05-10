<?php
namespace core\modules\menu\ajax;

/**
 * Final default class.
 * 
 * @final
 * @package 	menulist
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 August 2019
 */
final class menulist extends \core\app\classes\module_base\module_ajax {
	
	public function __construct()
    {

        return;
    }
		
	public function run()
	{
		$this->authorizeAjax('menulist');
		//we are going to need
		$menu_common_ns = NS_MODULES.'\\menu\\models\\common\\common';
		$menu_common = $menu_common_ns::getInstance();
		
		$out = $menu_common->getFrontendMenuListArray();
		
		if( !empty($out) ) 
		{
			$out = json_encode($out);
		} else {
			$out = json_encode("Singing in the rain '{$this->option}'");
		}
		header('Content-Type: application/json; charset=utf-8');
		return $out;
	}
		
}
?>