<?php
namespace iow\modules\xMODULEx\ajax;

/**
 * Final main class.
 * 
 * @final
 * @extends		module_ajax
 * @package 	xMODULEx
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee xDATEx
 */
final class main extends \iow\app\classes\module_base\module_ajax {
		
	protected $optionRequired = true;
	
	public function run()
	{	
		$out = null;
		
		switch($this->option) 
		{	
			case 'TEST':			
				
				echo "<pre>";
				print_r($_POST);
				echo "</pre>";
				die('END');

				break;
		
			default:
				throw new \Exception('Unsupported operation: ' . $this->option);
				break;
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